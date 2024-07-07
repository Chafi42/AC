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
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        // Récupération de l'utilisateur actuel
        $user = $userRepository->$this->getUser();
        // Récupération de tous les messages
        $message = $messageRepository->findAll();
        return $this->render('message/index.html.twig', [
            'messages' => $message,
            'users' => $user,
        ]);
    }

    #[Route('/message/{id}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MessageRepository $messageRepository, CarsRepository $carRepository, int $id): Response
    {
        // Récupération de l'utilisateur actuel
        $sender = $this->getUser();
        if (!$sender) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
    
        // Récupération de la voiture en fonction de l'id de la voiture ($id)
        $car = $carRepository->find($id);
        
        // Si la voiture n'existe pas, lancer une exception
        if (!$car) {
            throw $this->createNotFoundException('La voiture n\'existe pas.');
        }
    
        // Récupération de l'utilisateur destinataire en fonction de la voiture
        $receiver = $car->getUser();
    
        if (!$receiver) {
            throw $this->createNotFoundException('Le destinataire n\'existe pas.');
        }
    
        // Création d'un nouveau message
        $message = new Message();
    
        // Création du formulaire
        $form = $this->createForm(MessageType::class, $message);
    
        // Traitement du formulaire
        $form->handleRequest($request);
    
        // Si le formulaire est soumis et valide alors...
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'ID du destinataire à partir de la requête POST 
            $receiverId = $request->request->get('receiver_id');
            $receiver = $userRepository->find($receiverId);
            
            // Message entre l'expéditeur et le destinataire
            $message->setSender($sender);
            $message->setReceiver($receiver);
    
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





    // #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    // public function show(int $id, EntityManagerInterface $entityManager): Response
    // {

    //     $messages = $entityManager->getRepository(Message::class)->find($id);

    //     $users = $this->getUser();
    //     $users = $entityManager->getRepository(User::class)->find($id);

    //     $pictures = $this->getUser();
    //     $pictures = $entityManager->getRepository(User::class)->find($id);
    //     return $this->render('message/show.html.twig', [
    //         'messages' => $messages,
    //         'users' => $users,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_message_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    // public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->getPayload()->get('_token'))) {
    //         $entityManager->remove($message);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_message', [], Response::HTTP_SEE_OTHER);
    // }
}
