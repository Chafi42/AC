<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(CarsRepository $carsRepository): Response

    {
        $cars = $carsRepository->findAll();
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'cars' => $cars,
        ]);
    }
}
