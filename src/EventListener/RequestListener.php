<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestListener
{
    /**
     * @var FilterControllerEvent
     */
    private $event;

    public function onKernelController(FilterControllerEvent $event)
    {
        $this->event = $event;

        if (in_array($this->event->getRequest()->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT])) {
            $this->decodeJsonRequest();
        }
    }

    private function decodeJsonRequest()
    {
        $request = $this->event->getRequest();

        $data = json_decode($request->getContent(), true);
        if ((json_last_error() !== JSON_ERROR_NONE)) {
            throw new BadRequestHttpException('Bad json request');
        }

        $request->request->replace($data);
    }
}
