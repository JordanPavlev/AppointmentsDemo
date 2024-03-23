<?php

namespace App\EventSubscriber;

// ...
use App\Repository\AppointmentsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing;
use CalendarBundle\CalendarEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AppointmentsRepository $appointmentsRepository,
        private UrlGeneratorInterface $router
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }
}