<?php

namespace App\Controller;

use App\Security\EmailVerifier;
use App\Service\IntraController;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



final class MainController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier) {}

    #[Route('/', name: 'app_main')]
    public function index(IntraController $intraController): Response
    {
        // force to validate email
        if ($intraController->confirmEmail($this->getUser())) {
            if(is_object($this->getUser())){
                $this->validateEmail();
            }
        }
        // force to upload avatar
        if (!$intraController->confirmEmail($this->getUser()) && $intraController->completeCoordonnees($this->getUser())) {
            $this->addFlash('alert-warning', 'Vous devez indiquer votre avatar !');
            return $this->redirectToRoute('app_avatar');
        }
        //page
        return $this->render('main/index.html.twig',['user'=>$this->getUser()]);
    }

    private function validateEmail(): Response
    {
        $user =$this->getUser();
        if(isset($user)){
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            new TemplatedEmail()
                ->from(new Address('webmaster@my-domain.org', 'webmaster'))
                ->to((string) $user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );}
        $this->addFlash('alert-warning', 'Vous devez confirmer votre adresse email');
        return $this->redirectToRoute('app_avatar');
    }
}
