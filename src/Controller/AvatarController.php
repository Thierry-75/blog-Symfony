<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Form\AvatarForm;
use App\Form\UpdateAvatarForm;
use App\Message\SendEmailNotification;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AvatarController extends AbstractController
{
    /**
     * show profile function
     */
    #[Route('/profil/show/{id}', name: 'app_avatar_profil', methods: ['GET'])]
    public function showProfil(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('avatar/index.html.twig', ['user' => $user]);
    }

    /**
     * create profil function
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param PhotoService $photoService
     * @param EntityManagerInterface $em
     * @param MessageBusInterface $messageBus
     * @return Response
     * @throws Exception
     */
    #[Route('/profil/add', name: 'app_avatar', methods: ['POST', 'GET'])]
    public function addAvatar(Request $request, ValidatorInterface $validator, PhotoService $photoService, EntityManagerInterface $em, MessageBusInterface $messageBus
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $avatar = new Avatar();
        $avatarForm = $this->createForm(AvatarForm::class, $avatar);
        $avatarForm->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($request);
            if (count($errors) > 0) {
                return $this->render('avatar/add.html.twig', ['avatarForm' => $avatarForm, 'errors' => $errors]);
            }
            if ($avatarForm->isSubmitted()  && $avatarForm->isValid()) {
                try {
                    $photo = $avatarForm->get('image')->getData();
                    if (($photo->getClientOriginalExtension()) == 'png') {
                        $user = $em->getRepository(User::class)->find($this->getUser());
                        $fichier = $photoService->add($photo, $user->getEmail(), $user->getFolder(), 128, 128);
                        $avatar->setName($fichier);
                        $avatar->setSubscriber($user);
                        $user->setIsFull(true);
                        $em->persist($avatar);
                        $em->persist($user);
                        $em->flush();
                        $webmaster ='webmaster@my-domain.org';
                        $url = $this->generateUrl('app_main', [], UrlGeneratorInterface::ABSOLUTE_URL);
                        $messageBus->dispatch(new SendEmailNotification($webmaster,$user->getEmail(),'Profil complet','confirmation',['user'=> $user,'url'=>$url]));
                        $this->addFlash('alert-success', 'Votre profil a été ajouté !');
                        return $this->redirectToRoute('app_avatar_profil', ['id' => $user->getId()]);
                    }
                } catch (EntityNotFoundException $e) {
                    return $this->redirectToRoute('app_error',['exception'=>$e]);
                }
            }
        }
        return $this->render('avatar/add.html.twig', [
            'avatarForm' => $avatarForm
        ]);
    }

    /**
     * update profil function
     *
     * @param User $user
     * @param Avatar $avatar
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param PhotoService $photoService
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     */
    #[Route('/profil/update/{id}', name: 'app_avatar_update', methods: ['GET', 'POST'])]
    public function updateAvatar(User $user, Avatar $avatar, Request $request, ValidatorInterface $validator, PhotoService $photoService, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form_update = $this->createForm(UpdateAvatarForm::class, $avatar);
        $form_update->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($request);
            if (count($errors) > 0) {
                return $this->render('avatar/update.html.twig', ['Form_update' => $form_update, 'errors' => $errors]);
            }
            if ($form_update->isSubmitted() && $form_update->isValid()) {
                try {
                    $photo = $form_update->get('image')->getData();
                    if ($photo->getClientOriginalExtension() == 'png') {

                        $photoService->delete($avatar->getName(), $user->getFolder(), 128, 128);
                        $fichier = $photoService->add($photo, $user->getEmail(), $user->getFolder(), 128, 128);
                        $avatar->setName($fichier);
                        $avatar->setSubscriber($user);
                        $user->setIsFull(true);
                        $em->persist($avatar);
                        $em->flush();
                        $this->addFlash('alert-success', 'votre profil a été modifié !');
                        return $this->redirectToRoute('app_avatar_profil', ['id' => $user->getId()]);
                    }
                } catch (EntityNotFoundException $e) {
                    return $this->redirectToRoute('app_error', ['exception' => $e]);
                }
            }
        }
        return $this->render('avatar/update.html.twig', ['Form_update' => $form_update, 'user' => $user]);
    }
}
