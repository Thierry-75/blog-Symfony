<?php

namespace App\Controller;

use App\Form\ChangePasswordForm;
use App\Form\ResetPasswordRequestForm;
use App\Message\SendEmailNotification;
use App\Repository\UserRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_main');
        
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path:'/oubli-pass',name:'forgotten_password',methods: ['GET','POST'])]
    public function forgottenPassword(Request $request,ValidatorInterface $validator,UserRepository $userRepository,
    TokenGeneratorInterface $tokenGenerator,EntityManagerInterface $entityManager,MailService $mailService): Response
    {
        $form = $this->createForm(ResetPasswordRequestForm::class);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                $this->render('security/reset_password_request.html.twig',['requestForm'=>$form->createView(),'errors'=>$errors]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $user = $userRepository->findOneByEmail($form->get('email')->getData());
                if($user){
                    //jeton
                    $token = $tokenGenerator->generateToken();
                    try {
                        //record data
                        $user->setResetToken($token);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        //lien
                        $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                        // datas mail
                        $context = ['url' => $url, 'user' => $user];
                        $webmaster = 'webmaster@my-domain.org';
                        $mailService->sendMail($webmaster, $user->getEmail(), 'Réinitialisation de mot de passe', 'password_reset', $context);
                        $this->addFlash('alert-success', 'lien email envoyé !');
                        return $this->redirectToRoute('app_login');
                    }catch (EntityNotFoundException $e){
                        return $this->redirectToRoute('app_error',['exception'=>$e]);
                    }
                }
                $this->addFlash('alert-danger','Un problème est survenu');
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/reset_password_request.html.twig',['requestForm'=>$form->createView()]);
    }

    #[Route('/oubli-pass/{token}',name:'reset_pass')]
    public function resetPass(string $token, Request $request, UserRepository $userRepository,ValidatorInterface $validator,
    EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher,MessageBusInterface $messageBus): Response
    {
        // control jeton
        $user = $userRepository->findOneByResetToken($token);
        if(isset($user)){
            $form = $this->createForm(ChangePasswordForm::class);
            $form->handleRequest($request);
            if($request->isMethod('POST')){
                $errors = $validator->validate($request);
                if(count($errors)>0){
                    return $this->render('/security/reset.html.twig',['resetForm'=>$form->createView(),'errors'=>$errors]);
                }
                if($form->isSubmitted() && $form->isValid()){
                    $user->setResetToken('');
                    $user->setPassword(
                        $userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData())
                    );
                    try{
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $webmaster ='webmaster@my-domain.org';
                        $url = $this->generateUrl('app_main', [], UrlGeneratorInterface::ABSOLUTE_URL);
                        $messageBus->dispatch(new SendEmailNotification($webmaster,$user->getEmail(),'Nouveau mot de passe','new_password',['user'=>$user,'url'=>$url]));
                        $this->addFlash('alert-success','Votre mot de passe a été modifié !');
                        return $this->redirectToRoute('app_login');
                    }catch (EntityNotFoundException $e){
                        return $this->redirectToRoute('app_error',['exception'=>$e]);
                    }
                }
            }
        }
        return $this->render('/security/reset.html.twig',['resetForm'=>$form->createView()]);
    }
}
