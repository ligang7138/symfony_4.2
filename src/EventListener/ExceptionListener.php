<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Monolog\Logger;
use App\Exception\MessageException;
use App\Exception\NotifyException;

class ExceptionListener{

    private $logger = null;

    public function onKernelException(GetResponseForExceptionEvent $event){
        $exception = $event->getException();
        $request = $event->getRequest();

        $status = $exception->getCode();
        if($status === 0){
            $status = 501;
        }

        $log_data = array(
            'status' => $status,
            'code' => $status,
            'msg' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
        );

        $this->logger->error($request->getRequestUri(),['error_data'=>$log_data,'attributes'=>$request->attributes->all(),'request'=>$request->request->all(),'query'=>$request->query->all()]);

        if($exception instanceof MessageException){
            $response = new JsonResponse($log_data,$status);
        }else if($exception instanceof NotifyException){
            $response = new JsonResponse(['status'=>$status,'code'=>$status,'msg'=>$exception->getMessage()],$status);
        }else{
            $msg = '文件：'.$exception->getFile().'--'.'第'.$exception->getLine().'行';
            $response = new JsonResponse(['status'=>$status,'code'=>$status,'msg'=>$msg],$status);
        }

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

