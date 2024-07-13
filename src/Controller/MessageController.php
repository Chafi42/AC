<?php

namespace App\Controller;

use App\Entity\carss;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\CarsRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class MessageController extends AbstractController
{


    #[Route('/messages', name: 'app_message', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $currentUser = $this->getUser();
        // Récupération de tous les utilisateurs
        $users = $userRepository->findAll();
        // Filtrage des utilisateurs pour ne pas afficher l'utilisateur connecté
        $filteredUsers = array_filter($users, function ($user) use ($currentUser) {
            return $user !== $currentUser;
        });

        return $this->render('message/index.html.twig', [
            'users' => $filteredUsers,
        ]);
    }

    #[Route('/messages/{receiverId}', name: 'app_message_conversation', methods: ['GET', 'POST'])]
    public function conversation(int $receiverId, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MessageRepository $messageRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $currentUser = $this->getUser();
        // Récupération de tous les utilisateurs
        $users = $userRepository->findAll();
        // Filtrage des utilisateurs pour ne pas afficher l'utilisateur connecté
        $filteredUsers = array_filter($users, function ($user) use ($currentUser) {
            return $user !== $currentUser;
        });
        // Récupération de l'utilisateur connecté
        $sender = $this->getUser();
        $receiver = $userRepository->find($receiverId);

        if (!$receiver) {
            throw $this->createNotFoundException('Receiver not found');
        }

        // Vérification pour empêcher l'utilisateur de s'envoyer un message à lui-même
        /**
         * @var User $currentUser
         */
        if ($currentUser->getId() === $receiver->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas vous envoyer de message à vous-même.');
            return $this->redirectToRoute('app_message');
        }

        // Création d'un nouveau message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Message entre l'expéditeur et le destinataire
            $message->setSender($sender);
            $message->setReceiver($receiver);

            $message->setCreatedAt(new \DateTimeImmutable());
            $message->setUpdatedAt(new \DateTimeImmutable());

            // Enregistrement du message
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_message_conversation', ['receiverId' => $receiverId]);
        }

        // Récupération des messages échangés entre l'expéditeur et le destinataire
        $messages = $messageRepository->findBySenderAndReceiver($sender, $receiver);

        return $this->render('message/conversation.html.twig', [
            'messages' => $messages,
            'receiver' => $receiver,
            'form' => $form->createView(),
            'users' => $filteredUsers,
        ]);
    }

    #[Route('/message/{id}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MessageRepository $messageRepository, CarsRepository $carRepository, int $id): Response
    {
        // Récupération de l'utilisateur actuel
        $sender = $this->getUser();

        // Récupération de la voiture en fonction de l'id de la voiture ($id)
        $car = $carRepository->find($id);

        // Récupération de l'utilisateur destinataire en fonction de la voiture
        $receiver = $car->getUser();
        // Création d'un nouveau message
        $message = new Message();

        // Création du formulaire
        $form = $this->createForm(MessageType::class, $message);

        // Traitement du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide alors...
        if ($form->isSubmitted() && $form->isValid()) {

            // Message entre l'expéditeur et le destinataire
            $message->setSender($sender);
            $message->setReceiver($receiver);
            $message->setUpdatedAt(new \DateTimeImmutable());
            $message->setCreatedAt(new \DateTimeImmutable());
            $message->setDeletedAt(new \DateTimeImmutable());
            // Enregistrement du message
            $entityManager->persist($message);
            $entityManager->flush();

            // Redirection vers la même route pour rafraîchir la conversation
            return $this->redirectToRoute('app_message_new', ['id' => $id]);
        }

        // Récupération des messages échangés entre l'expéditeur et le destinataire
        $messages = $messageRepository->findBySenderAndReceiver($sender, $receiver);

        // Affichage de la page de conversation
        return $this->render('message/new.html.twig', [
            'users' => [$sender, $receiver],
            'messages' => $messages,
            'receiver' => $receiver,
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }
}
