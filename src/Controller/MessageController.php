<?php

namespace App\Controller;

use App\Entity\Message;
// use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
// use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message', methods: ['GET'])]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        $userRepository = $this->getUser();

        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
            'users' => $userRepository,
        ]);
    }

    #[Route('/message/{id}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MessageRepository $messageRepository, int $id): Response 
    {
        // Récupération de l'utilisateur actuel
        $sender = $this->getUser();
    
        // Récupération de l'utilisateur destinataire par ID
        $receiver = $userRepository->find($id);
    
        // Vérification si le destinataire existe
        if (!$receiver) {
            throw $this->createNotFoundException('Le destinataire n\'existe pas.');
        }
    
        // Création d'un nouveau message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($sender);
            $message->setReceiver($receiver);
            $entityManager->persist($message);
            $entityManager->flush();
    
            // Redirection vers la même route pour rafraîchir la conversation
            return $this->redirectToRoute('app_message_new', ['id' => $receiver->getId()]);
        }
    
        // Récupération des messages échangés entre l'expéditeur et le destinataire
        $messages = $messageRepository->findBySenderAndReceiver($sender, $receiver);
    
        return $this->render('message/new.html.twig', [
            'users' => [$sender, $receiver],
            'messages' => $messages,
            'receiver' => $receiver,
            'form' => $form->createView(),
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
    //         'pictures' => $pictures,
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
