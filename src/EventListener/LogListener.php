<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class LogListener{
    private $logger;
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        if ($request->headers->has('authorization')){
           $message = 'Method: '.$request->server->get("REQUEST_METHOD").
                    '; Endpoint: '.$request->server->get("REQUEST_URI").
                    '; Request: '.$request->getContent().
                    '; Response: '.$response->getContent();
           $this->logger -> info($message);
        }
    }
}