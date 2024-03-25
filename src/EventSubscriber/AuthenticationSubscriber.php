<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\AuthenticationFailureEvent;
class AuthenticationSubscriber implements EventSubscriberInterface
{
    private $security;
    private $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Check if the user is not authenticated and is accessing / or /product
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY') && 
            ($request->getPathInfo() !== '/login')) {
            $url = $this->urlGenerator->generate('app_login');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }

    }
    

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
