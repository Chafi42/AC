<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CCMController extends AbstractController
{
    #[Route('/comment-ça-marche', name: 'app_ccm', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('ccm/ccm.tml.twig',);
    }
}
