<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;





class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $message = (new Email())
                ->from($contactFormData['email'])
                ->to($contactFormData['email'])
                ->subject($contactFormData['subject'])
                ->text($contactFormData['message'])
                ->html('<p>' . $contactFormData['message'] . '</p>');

            $mailer->send($message);
            $this->addFlash('success', 'Ton message a bien été envoyé !');

            return $this->redirectToRoute('app_contact');


            
        } else {
            $this->addFlash('error', 'Ton message n\'a pas pu être envoyé !');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
