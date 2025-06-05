<?php

namespace App\Controller;

use App\Security\EmailVerifier;
use App\Service\IntraController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MainController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/', name: 'app_main')]
    public function index(IntraController $intra): Response
    {
        // force to validate email
        if ($intra->confirmEmail($this->getUser())) {
            $this->validateEmail();
        }
        // force to upload avatar
        if (!$intra->confirmEmail($this->getUser()) && $intra->completeCoordonnees($this->getUser())) {
            $this->addFlash('alert-warning', 'Vous devez indiquer votre avatar !');
            return $this->redirectToRoute('app_avatar');
        }
        //page
        return $this->render('main/index.html.twig', []);
    }

    public function validateEmail(): Response
    {
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $this->getUser(),
            (new TemplatedEmail())
                ->from(new Address('webmaster@my-domain.org', 'webmaster'))
                ->to((string) $this->getUser()->Email)
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        $this->addFlash('alert-warning', 'Vous devez confirmer votre adresse email');
        return $this->redirectToRoute('app_avatar');
    }
}
