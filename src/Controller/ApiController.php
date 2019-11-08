<?php

namespace App\Controller;

use App\Service\RedisService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 * Class BaiduController
 * @package App\Controller
 * @return \Symfony\Component\HttpFoundation\Response
 */
class ApiController extends Controller
{
	/**
	 * @Route("/login")
	 */
	public function loginAction(){
	    /*echo 2343;die;
		$redis = $this->container->get('redis_service');
//		echo 3434;die;
		echo '<pre />';
		print_r($redis);die;
		return $this->render("baidu/case.html.twig",['name' => '百度地图使用案例']);*/
	}

    /**
     * @Route("/login_check")
     * @return string
     */
	public function loginCheckAction(Request $request){
	    echo $request->get('name');die;
	}
}