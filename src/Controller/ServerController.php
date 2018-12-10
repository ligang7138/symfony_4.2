<?php

namespace App\Controller;

use App\Services\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 使用service的实例
 * @Route("/server")
 * Class TestController
 * @package App\Controller
 */
class ServerController extends Controller
{
	/**
	 * @Route("/case1")
	 */
	public function case1(){
		$messageGeneratorService = $this->container->get('message_generator');
		print_r($messageGeneratorService);die;
	}

	/**
	 * 一次使用资源导入多项服务的情况, services.yml中 exclude 里去掉Service
	 * @Route("/case2")
	 * @param MessageGenerator $messageGenerator
	 */
	public function case2(MessageGenerator $messageGenerator){
		$message = $messageGenerator->getHappyMessage();
		echo $message;die;
		return $this->addFlash('success',$message);
	}

	/**
	 * 单独在services.yaml加服务的情况，使用$this->container->get(MessageGenerator::class)获取服务
	 * @Route("/service1")
	 */
	public function testService1(){
//		$messageService = $this->container->get(MessageGenerator::class);
		$messageService = $this->container->get('message_generator');
		$message = $messageService->getHappyMessage();
		echo $message;die;
	}

	/**
	 * 测试把controller当作服务使用
	 * @Route("/contr")
	 * @param BaiduController $baiduController
	 */
	public function testContr(BaiduController $baiduController){
		$baiduController->testService();
	}
}