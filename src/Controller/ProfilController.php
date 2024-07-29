<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Form\CarsType;
use App\Form\ProfilType;
use App\Repository\CarsRepository;
use App\Repository\MessageRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications dans la base de données
            $entityManager->flush();
            // $this->addFlash('success', 'Profil mis à jour');
            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_profil');
        }

        // Afficher le formulaire
        return $this->render('profil/profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/profil/delete', name: 'app_profil_delete')]
    public function supp(EntityManagerInterface $entityManager, MessageRepository $messageRepository, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();
        if (!$user) {
            // Handle the case where no user is logged in
            $this->addFlash('error', 'Aucun utilisateur connecté.');
            return $this->redirectToRoute('app');
        }

        // Get the messages receive by the user
        $messages = $messageRepository->findBy(['receiver' => $user]);
        // Remove the messages
        foreach ($messages as $message) {
            $entityManager->remove($message);
        }

        // Invalidate the session and remove the security token
        $tokenStorage->setToken(null);
        $session->invalidate();

        // Remove the user
        $entityManager->remove($user);
        $entityManager->flush();

        // Add a flash message to indicate that the account has been deleted
        $this->addFlash('success', 'Compte supprimé.');

        // Redirect to the home page
        return $this->redirectToRoute('app');
    }


    #[Route('/annonce', name: 'app_annonce', methods: ['GET', 'POST'])]
    public function annonce(CarsRepository $carsRepository): Response
    {
        // Obtenir l'utilisateur actuellement connecté
        $user = $this->getUser();
        // $carsRepository par id de l'utilisateur
        $cars = $carsRepository->findBy(['user' => $user]);

        // Afficher le formulaire
        return $this->render('profil/anouncemment.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/annonce/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        // Créer le formulaire
        $form = $this->createForm(CarsType::class, $car);
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications dans la base de données
            $entityManager->flush();
            // Ajouter un message flash pour confirmer la mise à jour de l'annonce
            $this->addFlash('success', 'Annonce modifiée');
            // Rediriger l'utilisateur vers la page des annonces
            return $this->redirectToRoute('app_annonce');
        }
        // Afficher le formulaire
        return $this->render('profil/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/annonce/{id}/delete', name: 'app_annonce_delete')]
    public function delete(Cars $car , EntityManagerInterface $entityManager): Response
    {
        
        // Supprimer la voiture
        $entityManager->remove($car);
        $entityManager->flush();

        // Ajouter un message flash pour indiquer que l'annonce a été supprimée
        $this->addFlash('success', 'Annonce supprimée');

        // Rediriger vers la liste des annonces ou une autre route après la suppression
        return $this->redirectToRoute('app_annonce');
    }
}
