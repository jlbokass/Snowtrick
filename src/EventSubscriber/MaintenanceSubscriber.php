<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment as Twig;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function methodCallOnKernelResponse(FilterResponseEvent $filterResponseEvent)
    {
        $maintenance = false;

        if ($maintenance) {
            $content = $this->twig->render('maintenance/maintenance.html.twig');
            $response = new Response($content);

            return $filterResponseEvent->setResponse($response);
        }

        return $filterResponseEvent->getResponse()->getContent();
    }

    public static function getSubscribedEvents()
    {
        return [
          KernelEvents::RESPONSE => 'methodCallOnKernelResponse',
        ];
    }
}
