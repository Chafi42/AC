<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Form\CarsType;
use App\Form\ProfilType;
use App\Repository\CarsRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    // #[Route('/profil', name: 'app_profil')]
    // public function index(UserRepository $users, Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     // /**
    //     //  * @var User $changeProfile
    //     //  */
    //     // // $users = $this->getUser();

    //     // // $form = $this->createForm(RegistrationFormType::class, $users);
    //     // // $form->handleRequest($request);

    //     // // if ($form->isSubmitted() && $form->isValid()) {
    //     // //     $entityManager->flush();
    //     // //     $this->addFlash('success', 'Profil mis à jour');
    //     // //     $this->redirectToRoute('profil/profil.html.twig');
    //     // // }

    //     return $this->render('profil/profil.html.twig');
    // }


    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $users, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Create the form
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        // Process the form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour');

            return $this->redirectToRoute('app_profil');
        }

        // Render the form in the template
        return $this->render('profil/profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // #[Route('/annonce', name: 'app_annonce')]
    // public function annonce(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    // {
    //     // Get the currently logged-in user
    //     $car = $this->getUser();
    //     $form = $this->createForm(CarsType::class, $car);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();
    //         $this->addFlash('success', 'Annonce ajoutée');
    //         return $this->redirectToRoute('app_annonce');
    //     }

    //     return $this->render('profil/anouncemment.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }


    #[Route('/annonce', name: 'app_cars_achat', methods: ['GET', 'POST'])]
    public function annonce(CarsRepository $carsRepository, Request $request, PictureRepository $pictureRepository): Response
    {
        $cars = $carsRepository->findBy(['user' => $this->getUser()]);

        if (empty($cars)) {
            $cars = $carsRepository->findAll();
        }

        $pictures = $pictureRepository->findAll();

        return $this->render('cars/achat.html.twig', [
            'cars' => $cars,
            'pictures' => $pictures,
        ]);
    }
}
