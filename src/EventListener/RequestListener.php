<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $response)
    {
       $this->logger->info(     "Route : ".$response->getRequest()->attributes->get("_route")." Param : ".json_encode($response->getRequest()->attributes->get("_route_params"))." Content : ".$response->getRequest()->getContent());
    }
}
