<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cars')]
class CarsController extends AbstractController
{
    #[Route('/achat', name: 'app_cars_achat', methods: ['GET'])]
    public function index(CarsRepository $carsRepository): Response
    {
        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findAll(),
        ]);
    }

    #[Route('/vente', name: 'app_cars_vente', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cars = new Cars();
        $form = $this->createForm(CarsType::class, $cars);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cars);
            $entityManager->flush();

            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cars/new.html.twig', [
            'cars' => $cars,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_show', methods: ['GET'])]
    public function show(Cars $car): Response
    {
        return $this->render('cars/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cars_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cars/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
