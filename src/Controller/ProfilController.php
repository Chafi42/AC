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
    public function profil(): Response
    {
        return $this->render('profil/profil.html.twig');
    }

    // #[Route('/profil/{id}', name: 'app_profil_id')]
    // public function profil($id, UserRepository $userRepository, PictureRepository $pictureRepository): Response
    // {
    //     $user = $userRepository->find($id);
    //     $pictures = $pictureRepository->findBy(['user' => $user]);

    //     return $this->render('profil/profil.html.twig', [
    //         'user' => $user,
    //         'pictures' => $pictures
    //     ]);
    // }

    #[Route('/profil/annonce', name: 'app_profile_announcement')]
    public function announcement(): Response
    {
        return $this->render('profil/anouncemment.html.twig');
    }

    #[Route('/profil/favoris', name: 'app_profil_favorite')]
    public function favorite(): Response
    {
        return $this->render('profil/favorite.html.twig');
    }
}

    
    

