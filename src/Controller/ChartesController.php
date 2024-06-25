<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChartesController extends AbstractController
{
    #[Route('/chartes/pdc', name: 'app_pdc')]
    public function pdc(): Response
    {
        return $this->render('chartes/pdc.html.twig');
    }

    #[Route('/chartes/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('chartes/cgu.html.twig');
    }

    #[Route('/chartes/cookies', name: 'app_cookies')]
    public function cookies(): Response
    {
        return $this->render('chartes/cookies.html.twig');
    }

    #[Route('/chartes/mentions-legales', name: 'app_mentions')]
    public function mentions(): Response
    {
        return $this->render('chartes/mentions-legales.html.twig');
    }
}
