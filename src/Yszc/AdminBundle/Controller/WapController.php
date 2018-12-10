<?php

namespace App\Yszc\AdminBundle\Controller;
use App\Yszc\AdminBundle\Services\City\Impl\CityServiceImpl;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use App\Yszc\AdminBundle\Common\CommonFunction;
use App\Yszc\AdminBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/wap")
 */
class WapController extends CommonController
{
	private static $data = [
		'code' => 200,
		'msg' => '',
		'data' => []
	];

	/**
	 * 市列表（wap应用使用）
	 * @Route("/geography_position.html")
	 */
	public function wapCityList(Request $request){
		$province               = $request->get("pid",'CHINA');
		/** @var CityServiceImpl $cityService */
		$cityService          = $this->createService('City.CityService');
		$city                 = $cityService->getCityList($province);
		return $this->handleData($city);
	}

	/**
	 * 获取当前商户
	 * @return \AdminBundle\Entity\YsPartners|null|object
	 */
	private function getCurrentPartner(){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		return $partner;
	}


	private function handleData($data){
		if(empty($data)){
			self::$data['code'] = 200;
			self::$data['msg'] = '没有数据';
		}else{
			self::$data['msg'] = '获取成功';
			self::$data['data'] = $data;
		}
        return $this->parseData(self::$data);
	}

}
