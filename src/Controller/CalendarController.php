<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AppointmentsType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Carbon\Carbon;

use App\Repository\AppointmentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends AbstractController
{
    private $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

    #[Route('load-appointments', name: 'load-appointments', methods: ['POST'])]
    public function loadEvents(AppointmentsRepository $appointmentsRepository): JsonResponse
    {
        /**
         * @var \App\Entity\Appointments[] $appointments
         */
        $appointments = $appointmentsRepository->findAll();

        $events = [];
        $usedColors = []; // Keep track of used colors


        foreach ($appointments as $appointment) {
            $title = $appointment->getClientName();
            $color = $appointment->getCalendarColor();

            // THESE ARE ALL THE AVAILABLE PROPERTIES THAT THE LIBRARY READS AND RENDERS
            // id: (required) Unique identifier for the event.
            // title: (required) Title of the event.
            // start: (required) Start time of the event. Should be in ISO 8601 format or a JavaScript Date object.
            // end: End time of the event. Should be in ISO 8601 format or a JavaScript Date object.
            // allDay: Whether the event is an all-day event. Default is false.
            // url: URL that the event will link to when clicked.
            // editable: Whether the event is editable. Default is false.
            // color: Background color for the event.
            // textColor: Text color for the event title.
            // classNames: Additional CSS classes to apply to the event's element.
            // description: Description or additional information about the event.
            // extendedProps: Additional properties specific to your application. This can be an object containing any additional data you want to associate with the event.

            // Create FullCalendar event object
            $event = [
                'id' => $appointment->getId(),
                'title' => $title,
                'start' => Carbon::parse($appointment->getTimeAt())->toISOString(),
                'color' => $color
            ];

            $events[] = $event;
        }

        return $this->json($events);
    }
}
