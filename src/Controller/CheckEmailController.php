<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class CheckEmailController extends AbstractController
{

    public function __construct(private readonly EmailVerifier $emailVerifier)
    {
    }

    #[Route('/check', name: 'app_check_email')]
    public function index($user): Response
    {
                    // generate a signed url and email it to the user
                    $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    new TemplatedEmail()
                        ->from(new Address('webmaster@my-domain.org', 'webmaster'))
                        ->to((string) $user->getEmail())
                        ->subject('Veuillez confirmer votre adresse mail')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
    
                // do anything else you need here, like send an email
                $this->addFlash("alert-success","Veuillez consulter votre boite mail !");
                return $this->redirectToRoute('app_main');
    }
}
