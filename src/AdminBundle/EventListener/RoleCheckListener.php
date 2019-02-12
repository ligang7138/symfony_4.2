<?php
/**
 * Created by PhpStorm.
 * User: M4500
 * Date: 2016/12/23
 * Time: 16:54
 */

namespace AdminBundle\EventListener;
use AdminBundle\Exception\MessageException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AdminBundle\Common\CommonFunction;

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

            if (is_object($curUser)) {
                if(!in_array($controller,['AdminBundle\Controller\IndexController::heart','AdminBundle\Controller\AdminController::getMessage'])){
	                $hours = self::$timeOut;
	                $my_login_cookie = json_decode($_COOKIE['my_login'],true);
	                $online_time = $my_login_cookie['time'];
	                if($online_time<time()){
		                session_destroy();
		                //header('Refresh:3,Url=/login');
                        throw new MessageException('会话已过期，3秒后将跳转登录页！','window.location="/login"',3000,Response::HTTP_UNAUTHORIZED);
			            //die('<script>setTimeout(function(){window.location="/login"},3000)</script>会话已过期，3秒后将跳转登录页！');
	                }
	                setcookie('my_login', json_encode(array('code'=>200,'time'=>strtotime(date('Y-m-d H:i:s') . '+'.$hours . ' hours'))), strtotime(date('Y-m-d H:i:s') . "+($hours+0.1) hours"),'/');
                }
                if($curUser->getAType()==1){
                    return true;
                }
            }else{
                preg_match('/^\/_(wdt|profiler|twig)/',$server->get('REQUEST_URI'),$ret);
                if(!in_array($controller,$this->uchecks) && !in_array($ret[1],['wdt','profiler','twig'])){
	                setcookie('my_login', json_encode(array('code'=>200,'time'=>0)), -1,'/');
	                session_destroy();
	                //header('Refresh:3,Url=/login');
	                throw new MessageException('会话已过期，3秒后将跳转登录页！','window.location="/login"',3000,Response::HTTP_UNAUTHORIZED);
		            //die('<script>setTimeout(function(){},3000)</script>会话已过期，3秒后将跳转登录页！');
                }
            }
        }
    }

}