<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Form\AvatarForm;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AvatarController extends AbstractController
{

    #[Route('/profil/show/{id}',name:'app_avatar_profil',methods:['GET'])]
    public function showProfil(User $user): Response
    {
        if ($this->denyAccessUnlessGranted('ROLE_USER')) {
            $this->addFlash('alert-success', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('avatar/index.html.twig',['user'=>$user]);
    }

    #[Route('/profil/add', name: 'app_avatar', methods: ['POST', 'GET'])]
    public function add(Request $request, ValidatorInterface $validator, PhotoService $photoService, EntityManagerInterface $em): Response
    {
        if ($this->denyAccessUnlessGranted('ROLE_USER')) {
            $this->addFlash('alert-danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('login');
        }
        // form
        $avatar = new Avatar();
        $avatarForm = $this->createForm(AvatarForm::class, $avatar);
        $avatarForm->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($request);
            if (count($errors) > 0) {
                return $this->render('avatar/add.html.twig', ['avatarForm' => $avatarForm, 'errors' => $errors]);
            }
            if ($avatarForm->isSubmitted()  && $avatarForm->isValid()) {
                $photo = $avatarForm->get('image')->getData();
                $folder = 'avatars';
                $fichier = $photoService->add($photo, $folder, 32, 32);
                $avatar->setName($fichier);
                $user = $em->getRepository(User::class)->find($this->getUser());
                $avatar->setSubscriber($user);
                $user->setIsFull(true);
            }
            try{
            $em->persist($avatar);
            $em->persist($user);
            $em->flush();
            $this->addFlash('alert-success', 'Votre avatar a été ajouté !');
            return $this->redirectToRoute('app_avatar_profil',['id'=>$user->getId()]); // $this->redirecToRoute('app_avatar_profil',['id'=>$avatar->getId()])
            } catch(EntityNotFoundException $e){
                return $this->redirectToRoute('app_error',['exception'=>$e]);
            }

        }
        return $this->render('avatar/add.html.twig', [
            'avatarForm' => $avatarForm
        ]);
    }
}
