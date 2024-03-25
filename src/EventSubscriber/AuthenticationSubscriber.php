<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    private $security;
    private $urlGenerator;
    private $authenticationUtils;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator, AuthenticationUtils $authenticationUtils)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->authenticationUtils = $authenticationUtils;
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
    private function isAuthenticationFailure(): bool
    {
        // Check if there was an authentication error (failed login attempt)
        return $this->authenticationUtils->getLastAuthenticationError() !== null;
    }
    

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
