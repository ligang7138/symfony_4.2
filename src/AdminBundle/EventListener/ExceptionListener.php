<?php
namespace App\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Bridge\Monolog\Logger;

class ExceptionListener{

    private $logger = null;

    public function onKernelException(GetResponseForExceptionEvent $event){
        $exception = $event->getException();
        $status = $exception->getCode();
        if($status === 0){
            $status = 501;
        }
        $response = new JsonResponse(array(
            'status' => $status,
            'msg' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
        ),$status);
        $event->setResponse($response);
    }

    public function setLogger(Logger $logger){
        if(empty($this->logger)){
           $this->logger = $logger;
        }
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        //只记录非GET请求
        if($request->getMethod() != 'GET'){
            $this->logger->debug($request->getRequestUri(),['attributes'=>$request->attributes->all(),'request'=>$request->request->all(),'query'=>$request->query->all()]);
        }
        if (!$event->isMasterRequest()) {
            return;
        }
    }
}

