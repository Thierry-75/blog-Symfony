<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckEmailController extends AbstractController
{
    #[Route('/check/email', name: 'app_check_email')]
    public function index(): Response
    {
        return $this->render('check_email/index.html.twig', [
            'controller_name' => 'CheckEmailController',
        ]);
    }
}
