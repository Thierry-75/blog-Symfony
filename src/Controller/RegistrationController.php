<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($request);
            if (count($errors) > 0) {
                return $this->render('registration/register.html.twig', ['errors' => $errors]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();

                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword))
                    ->setRoles(['ROLE_USER']);
                try {
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // generate a signed url and email it to the user
                    $this->emailVerifier->sendEmailConfirmation(
                        'app_verify_email',
                        $user,
                        (new TemplatedEmail())
                            ->from(new Address('webmaster@my-domain.org', 'webmaster'))
                            ->to((string) $user->getEmail())
                            ->subject('Please Confirm your Email')
                            ->htmlTemplate('registration/confirmation_email.html.twig')
                    );

                    // do anything else you need here, like send an email
                    $this->addFlash("alert-success", "Veuillez consulter votre boite mail !");
                    return $this->redirectToRoute('app_main');
                } catch (EntityNotFoundException $e) {
                    return $this->redirectToRoute('app_error', ['exception' => $e]);
                }
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/confirm/email', name: 'app_confirm_email')]
    public function confirmUserEmail($user): Response
    {
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('webmaster@my-domain.org', 'webmaster'))
                ->to((string) $user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        // do anything else you need here, like send an email
        $this->addFlash("alert-success", "Veuillez consulter votre boite mail !");
        return $this->redirectToRoute('app_main');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('alert-success', 'Adresse email vérifiée !');

        return $this->redirectToRoute('app_login');
    }
}
