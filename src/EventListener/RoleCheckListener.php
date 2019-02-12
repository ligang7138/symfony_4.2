<?php

namespace App\EventListener;
use App\Exception\MessageException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use AdminBundle\Common\CommonFunction;
use AdminBundle\Services\Wx\Impl\WxServiceImpl;

class RoleCheckListener
{
    private $service = '';
    private $uchecks = [];
    private static $timeOut = 0;
    private $container;

    public function __construct($service = '',$tour_bundle=[],$container) {
        $this->service = $service;
        $this->uchecks =$tour_bundle['uchecks'];
        self::$timeOut = $tour_bundle['timeOut'];
        $this->container = $container;
    }

    //权限检查
    public function roleCheck(GetResponseEvent $event){
        if($event->isMasterRequest()) {
            $headers = $event->getRequest()->headers;
            $server = $event->getRequest()->server;
            $controller = $event->getRequest()->attributes->get('_controller');
            $token = $this->container->get('security.token_storage')->getToken();
            if($token){
                $curUser = $token->getUser();
            }else{
                $curUser = null;
            }

            if (!is_object($curUser)) {
                preg_match('/^\/_(wdt|profiler|twig)/',$server->get('REQUEST_URI'),$ret);
                if(!in_array($controller,$this->uchecks) && !in_array($ret[1],['wdt','profiler','twig'])){
	                throw new MessageException('会话已过期，3秒后将跳转登录页！','window.location="/login"',3000,Response::HTTP_UNAUTHORIZED);
                }
            }
        }
    }

    /** @return WxServiceImpl  */
    private function getWxService(){
        return $this->container->get('application_service')->getService('Wx.WxService');
    }
}