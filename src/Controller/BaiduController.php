<?php

namespace App\Controller;

use App\Service\RedisService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/baidumap")
 * Class BaiduController
 * @package App\Controller
 * @return \Symfony\Component\HttpFoundation\Response
 */
class BaiduController extends Controller
{
	/**
	 * @Route("/case")
	 */
	public function testAction(){
		$redis = $this->container->get('redis');
		echo 3434;die;
		echo '<pre />';
		print_r($redis);die;
		return $this->render("baidu/case.html.twig",['name' => '百度地图使用案例']);
	}

	public function testService(){
		return '可以被作为service使用';
	}
}