<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(CarsRepository $carsRepository, PictureRepository $pictureRepository): Response
    {
        $cars = $carsRepository->findAll();
        $pictures = $pictureRepository->findAll();
        // dd($picture);
        return $this->render('home/home.html.twig', [
            'cars' => $cars,
            'pictures' => $pictures,
        ]);
    }

    #[Route('/chat/{id}', name: 'app_chat')]
    public function chat(): Response
    {
        return $this->render('cars/chat.html.twig',);
    }
}