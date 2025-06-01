<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AvatarController extends AbstractController
{
    #[Route('/profil', name: 'app_avatar')]
    public function index(): Response
    {
        return $this->render('avatar/index.html.twig', [
            'controller_name' => 'AvatarController',
        ]);
    }
}
