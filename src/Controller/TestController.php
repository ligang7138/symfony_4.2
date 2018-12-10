<?php

namespace App\Controller;

use App\Services\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 * Class TestController
 * @package App\Controller
 */
class TestController extends Controller
{
	/**
	 * @Route("/test")
	 */
	public function test(){
		$em = $this->getDoctrine()->getManager();

		$userRepository = $em->getRepository('App:User');

//		$params = $this->getParameter('pay_name');

//		print_r($params);die;

		print_r($userRepository->find(61270));die;
		return $this->render('a.html.twig',['name' => 'symfony']);
	}

	/**
	 * 一次使用资源导入多项服务的情况
	 * @Route("/service")
	 * @param MessageGenerator $messageGenerator
	 */
	public function testService(MessageGenerator $messageGenerator){
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