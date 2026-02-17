<?php

namespace App\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class AccessDeniedSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Escuchamos cuando ocurre una excepción en la app
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Si el error es porque el usuario no tiene los permisos necesarios (ROLE_ADMIN)
        if ($exception instanceof AccessDeniedException) {

            // Añadimos un mensaje flash para que el usuario sepa qué ha pasado
            $event->getRequest()->getSession()->getFlashBag()->add('danger', 'Acceso restringido. Por favor, inicia sesión con una cuenta autorizada.');

            // Redirigimos al login
            $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
            $event->setResponse($response);
        }
    }
}
