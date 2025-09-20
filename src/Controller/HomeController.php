<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app', methods: ['GET'])]
    public function index(CarsRepository $carsRepository): Response
    {
        $cars = $carsRepository->findBy([], ['id' => 'DESC'], 6); // affiche les 6 derniers par ex.

        return $this->render('home/home.html.twig', [
            'cars' => $cars,
        ]);
    }
}
