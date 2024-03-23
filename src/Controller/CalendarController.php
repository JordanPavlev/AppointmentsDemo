<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$user) {
            $this->redirect('asd');
        }

        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }
}
