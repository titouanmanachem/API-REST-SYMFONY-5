<?php


namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response =  new JsonResponse(['status' => 400,'error'=> $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        $event->setResponse($response);
    }
}
