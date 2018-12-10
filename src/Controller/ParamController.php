<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/param")
 * Class ParamController
 * @package App\Controller
 * @return \Symfony\Component\HttpFoundation\Response
 */
class ParamController extends Controller
{
	/**
	 * @Route("/test")
	 */
	public function testAction(){
		echo $this->getParameter('pay_name');
		echo $this->getParameter('database_host');
		die;
	}
}