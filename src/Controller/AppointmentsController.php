<?php

namespace App\Controller;

use App\Entity\Appointments;
use App\Repository\AppointmentsRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

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

        // put default value in the appointment time field (current datetime)
        // $appointment->setTimeAt(new \DateTime('now', new \DateTimeZone('Europe/Sofia')));

        // $logger->info($timestamp);
        if ($timestamp != null) {
            $parsedTimestmap = Carbon::parse($timestamp);
            $appointment->setTimeAt($parsedTimestmap);
        }

        $form = $this->createFormBuilder($appointment, [
            'attr' => ['id' => 'new-form'],
        ])
            ->add('time_at', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email',
                ],
                'required' => true, // Making the field required
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Time cannot be blank']),
                ],
            ])
            ->add('client_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client name',
                ],
                'required' => true, // Making the field required
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Name cannot be blank']),
                ],
            ])
            ->add('client_email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('client_phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client phone number',
                ],
                'required' => true, // Making the field required
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{10}$/',
                        'message' => 'Please enter a valid phone number (10 digits).',
                    ]),
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

    #[Route('/{id}/show', name: 'app_appointments_show', methods: ['GET'])]
    public function show(Appointments $appointment): Response
    {
        return $this->render('appointments/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointments $appointment, EntityManagerInterface $entityManager): Response
    {
        $timestamp = $request->query->get('timestamp');

        // $logger->info($timestamp);
        if ($timestamp != null) {
            $parsedTimestmap = Carbon::parse($timestamp);
            $appointment->setTimeAt($parsedTimestmap);
        }

        $form = $this->createFormBuilder($appointment, [
            'attr' => ['id' => 'edit-form'],
        ])
            ->add('time_at', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('client_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client name',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('client_email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('client_phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client phone number',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{10}$/',
                        'message' => 'Please enter a valid phone number (10 digits).',
                    ]),
                ],
            ])

            ->getForm();
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

    #[Route('/check-conflict', name: 'app_appointments_check', methods: ['GET'])]
    public function checkAppointmentConflict(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Get the submitted appointment time
        $appointmentTime = Carbon::parse($request->query->get('time_at'));

        $conflict = $this->checkForAppointmentConflict($appointmentTime, $entityManager);

        // Return JSON response indicating whether there's a conflict
        return new JsonResponse(['conflict' => $conflict]);
    }

    #[Route('/{id}/delete', name: 'app_appointments_delete', methods: ['POST'])]
    public function delete(Request $request, Appointments $appointment, EntityManagerInterface $entityManager): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
        $entityManager->remove($appointment);
        $entityManager->flush();
        // }

        return $this->redirectToRoute('app_appointments_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkForAppointmentConflict(\DateTime $appointmentTime, EntityManagerInterface $entityManager)
    {
        // Assuming you have appointments stored in a database
        $repository = $entityManager->getRepository(Appointments::class);

        // Calculate the start and end times for the interval
        $startTime = Carbon::parse($appointmentTime)->copy();
        $endTime = Carbon::parse($appointmentTime)->copy();
        $startTime->subMinutes(15);
        $endTime->addMinutes(15);

        // Query appointments within the specified time interval
        $conflictingAppointments = $repository->createQueryBuilder('a')
            ->andWhere(':endTime >= a.time_at')
            ->andWhere(':startTime <= a.time_at')
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime)
            ->getQuery()
            ->getResult();
        // If there are conflicting appointments, return true
        if (!empty($conflictingAppointments)) {
            return true;
        }

        // No conflicting appointments found, return false
        return false;
    }
}
