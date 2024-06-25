<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $userPicture): Response
    {
        $userPicture = $userPicture->findAll();
        
        return $this->render('profil/profil.html.twig', [
           
        ]);
    }
}
