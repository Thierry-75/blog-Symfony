<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\IntraController;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(IntraController $intra): Response
    {
        if($intra->confirmEmail($this->getUser())){
            $this->addFlash('alert-warning','Vous devez confirmer votre adresse email');
            return $this->redirectToRoute('app_check_email');
        }
        if($intra->completeCoordonnees($this->getUser()))
        {
            $this->addFlash('alert-warning','Vous devez indiquez votre profil !');
            return $this->redirectToRoute('app_avatar');
        }

        
        return $this->render('main/index.html.twig', [
  
        ]);
    }
}
