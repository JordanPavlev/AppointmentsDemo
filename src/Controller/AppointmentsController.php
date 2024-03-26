<?php

namespace App\Controller;

use App\Entity\Appointments;
use App\Form\AppointmentsType;
use App\Repository\AppointmentsRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;


#[Route('/appointments')]
class AppointmentsController extends AbstractController
{
    #[Route('/appointments-all', name: 'app_appointments_index', methods: ['GET'])]
    public function index(AppointmentsRepository $appointmentsRepository): Response
    {
        return $this->render('appointments/index.html.twig', [
            'controller_name' => 'Appointments',
            'appointments' => $appointmentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_appointments_new', methods: ['GET', 'POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $timestamp = $request->query->get('timestamp');


        $appointment = new Appointments();
        
        // $logger->info($timestamp);
        if ($timestamp != null) {
            $parsedTimestmap = Carbon::parse($timestamp);
            $appointment->setTimeAt($parsedTimestmap);
        }

        $form = $this->createFormBuilder($appointment)
            ->add('time_at', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email',
                ],
            ])
            ->add('client_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client name',
                ],
            ])
            ->add('client_email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email',
                ],
            ])
            ->add('client_phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client phone number',
                ],
            ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointments/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointments_show', methods: ['GET'])]
    public function show(Appointments $appointment): Response
    {
        return $this->render('appointments/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointments $appointment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppointmentsType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appointments/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointments_delete', methods: ['POST'])]
    public function delete(Request $request, Appointments $appointment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
    }
}
