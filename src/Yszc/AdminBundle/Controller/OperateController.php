<?php
namespace App\Yszc\AdminBundle\Controller;

use App\Yszc\AdminBundle\Constant\MerchantConstant;
use App\Yszc\AdminBundle\Entity\AdminUsers;
use App\Yszc\AdminBundle\Entity\MyRelease;
use App\Yszc\AdminBundle\Entity\YsBanners;
use App\Yszc\AdminBundle\Entity\YsMessage;
use App\Yszc\AdminBundle\Entity\YsPartnerCoupon;
use App\Yszc\AdminBundle\Entity\YsPartnerDaturm;
use App\Yszc\AdminBundle\Entity\YsPartners;
use App\Yszc\AdminBundle\Entity\YsUserMessage;
use App\Yszc\AdminBundle\Services\City\Impl\CityServiceImpl;
use App\Yszc\AdminBundle\Services\Operate\Impl\OperateServiceImpl;
use App\Yszc\AdminBundle\Services\Partner\Impl\PartnerServiceImpl;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * @Route("/operate")
 */
class OperateController extends CommonController
{
	/**
	 * 广告位管理(后台)
	 * @Route("/banner_list.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$conditions = $this->getListAndLimt();
		/** @var OperateServiceImpl $operateService */
		$operateService = $this->createService('Operate.OperateService');
		$bannersList = $operateService->findList($conditions);
		$page = $this->get('page_service');
		$tabid = 'banners_list';
		$page->setPage($bannersList['count'], $request->request->get('p',1),true,$tabid);
		return $this->show(
			'operate/banner_list',
			[
				'tabid' => $tabid,
				'list' => $bannersList['data'],
				'page' => $page->show(),
				'params' => $conditions,
			]
		);
	}

	/**
	 * 添加或编辑广告位
	 * @Route("/banner_create/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function bannerCreateAction(Request $request){
		if('post' == strtolower($request->getRealMethod())){
			$em = $this->getDoctrine()->getManager();
			$b_title = trim($request->get('b_title'));
			$b_type = $request->get('b_type','1');
			$b_status = $request->get('b_status','0');
			$b_order = $request->get('b_order',0);
			$b_url = $request->get('b_url','');
			$b_id = intval($request->get('id',0));

			if(empty($b_url)){
				return  $this->parseData(['code' => 500,'msg'=>'广告图片必传！','closeCurrTab'=>false]);
			}

			if(empty($b_type)){
				return  $this->parseData(['code' => 500,'msg'=>'广告位类型必选！','closeCurrTab'=>false]);
			}

			if(empty($b_title)){
				return  $this->parseData(['code' => 500,'msg'=>'广告位名称必填！','closeCurrTab'=>false]);
			}
			if($b_id){
				$banner = $this->getDoctrine()->getRepository('AdminBundle:YsBanners')->find($b_id);
			}else{
				$banner = new YsBanners();
				$banner->setBUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
			}

			$banner->setBTitle($b_title);
			$banner->setBStatus($b_status);
			$banner->setBType($b_type);
			$banner->setBOrder($b_order);
			$banner->setBImg($b_url);
			$em->persist($banner);
			$em->flush();
			return  $this->parseData(['code' => 200,'openUrl'=>'/operate/banner_list.html','title'=>'广告位列表']);
		}else {
			$b_id = $request->get('id',0);
			$data = ['b_id' => $b_id];
			if($b_id){
				$banner = $this->getDoctrine()->getRepository('AdminBundle:YsBanners')->find($b_id);
				if($banner){
					$data['banner'] = $banner;
				}
			}
//			print_r($data);die;
			return $this->show('operate/banner_form', $data);
		}
	}

	/**
	 * 广告位停用或启用(后台)
	 * @Route("/disorenable.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function bannerDisOrEnable(Request $request){
		$b_id = intval($request->get('id',0));
		$em = $this->getDoctrine()->getManager();
		$banner = $em->getRepository('AdminBundle:YsBanners')->find($b_id);
		if(is_null($banner)){
			return $this->parseData(['code'=>500,'msg'=>'该广告位不存在！','closeCurrTab'=>false]);
		}
		if($banner->getBStatus() == 0){
			$banner->setBStatus(1);
		}elseif($banner->getBStatus() == 1){
			$banner->setBStatus(0);
		}
		$em->persist($banner);
		$em->flush();
		return $this->parseData(['code'=>200,'openUrl'=>'/operate/banner_list.html','msg'=>'修改成功','closeCurrTab'=>false]);
	}
	/**
	 * app发布列表(后台)
	 * @Route("/app_list.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function appListAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$conditions = $this->getListAndLimt();
		/** @var OperateServiceImpl $operateService */
		$operateService = $this->createService('Operate.OperateService');
		$appList = $operateService->findAppList($conditions);
		$page = $this->get('page_service');
		$tabid = 'app_list';
		$page->setPage($appList['count'], $request->request->get('p',1),true,$tabid);
		return $this->show(
			'operate/app_list',
			[
				'tabid' => $tabid,
				'list' => $appList['data'],
				'page' => $page->show(),
				'params' => $conditions,
			]
		);
	}

	/**
	 * app发版添加或修改
	 * @Route("/app_form/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function appFormAction(Request $request){
		if('post' == strtolower($request->getRealMethod())){
			$em = $this->getDoctrine()->getManager('fenqi');
			$title = trim($request->get('title'));
			$type = $request->get('type');
			$app_url = $request->get('app_url');
			$is_force = $request->get('is_force');
			$info = $request->get('info');
			$version_no = $request->get('version_no');
			$filesize = $request->get('filesize');
			$app_type = $request->get('app_type');
			$id = intval($request->get('id',0));
			if(empty($title)){
				return  $this->parseData(['code' => 500,'msg'=>'标题必填！','closeCurrTab'=>false]);
			}
			if(empty($type)){
				return  $this->parseData(['code' => 500,'msg'=>'系统类型必选！','closeCurrTab'=>false]);
			}

			if(empty($app_type)){
				return  $this->parseData(['code' => 500,'msg'=>'app类型必选！','closeCurrTab'=>false]);
			}

			if(empty($app_url)){
				return  $this->parseData(['code' => 500,'msg'=>'app更新地址必填！','closeCurrTab'=>false]);
			}
			if($id){
				$appEntity = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyRelease')->find($id);
			}else{
				$appEntity = new MyRelease();
				$appEntity->setUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
			}

			$appEntity->setTitle($title);
			$appEntity->setAppUrl($app_url);
			$appEntity->setIsForce($is_force);
			$appEntity->setInfo($info);
			$appEntity->setVersionNo($version_no);
			$appEntity->setFilesize($filesize);
			$appEntity->setType($type);
			$appEntity->setAppType($app_type);
			$em->persist($appEntity);
			$em->flush();
			return  $this->parseData(['code' => 200,'openUrl'=>'/operate/app_list.html','title'=>'app发版列表']);
		}else {
			$id = $request->get('id',0);
			$data = ['id' => $id];
			if($id){
				$app = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyRelease')->find($id);
				if($app){
					$data['app'] = $app;
				}
			}
			return $this->show('operate/app_form', $data);
		}
	}

	/**
	 * 消息管理(后台)
	 * @Route("/msg_list.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function msgListAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$conditions = $this->getListAndLimt();
		/** @var OperateServiceImpl $operateService */
		$operateService = $this->createService('Operate.OperateService');
		$msgList = $operateService->findMsgList($conditions);
		$page = $this->get('page_service');
		$tabid = 'banners_list';
		$page->setPage($msgList['count'], $request->request->get('p',1),true,$tabid);
		return $this->show(
			'operate/msg_list',
			[
				'tabid' => $tabid,
				'list' => $msgList['data'],
				'page' => $page->show(),
				'params' => $conditions,
			]
		);
	}

	/**
	 * msg添加或修改
	 * @Route("/msg_form/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function msgFormAction(Request $request){
		if('post' == strtolower($request->getRealMethod())){
			$em = $this->getDoctrine()->getManager();
			$title = trim($request->get('msg_title'));
			$msg_type = $request->get('msg_type');
			$msg_sys_type = $request->get('msg_platform',0);
			$msg_content = $request->get('msg_info','');
			$msg_send_status = $request->get('msg_send_status',1);
			$bounce_type = $request->get('bounce_type',1);
			$id = intval($request->get('id',0));
			if(empty($title)){
				return  $this->parseData(['code' => 500,'msg'=>'标题必填！','closeCurrTab'=>false]);
			}
			if(empty($msg_type)){
				return  $this->parseData(['code' => 500,'msg'=>'类型必选！','closeCurrTab'=>false]);
			}

			if(empty($msg_sys_type)){
				return  $this->parseData(['code' => 500,'msg'=>'推送平台必选！','closeCurrTab'=>false]);
			}

			if(empty($msg_content)){
				return  $this->parseData(['code' => 500,'msg'=>'消息内容不能为空！','closeCurrTab'=>false]);
			}

			if(empty($msg_send_status)){
				return  $this->parseData(['code' => 500,'msg'=>'发布状态必须选！','closeCurrTab'=>false]);
			}
			if(empty($bounce_type)){
				return  $this->parseData(['code' => 500,'msg'=>'弹框类型必须选！','closeCurrTab'=>false]);
			}
			if($id){
				$msgEntity = $this->getDoctrine()->getManager()->getRepository('AdminBundle:YsMessage')->find($id);
			}else{
				$msgEntity = new YsMessage();
				$msgEntity->setMsgAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$msgEntity->setMsgSendType('2');
			}

			$admin_id = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();

			$msgEntity->setMsgTitle($title);
			$msgEntity->setMsgContent($msg_content);
			$msgEntity->setMsgType($msg_type);
			$msgEntity->setMsgSendType(2);
			$msgEntity->setMsgSendStatus($msg_send_status);
			$msgEntity->setMsgSysType($msg_sys_type);
			$msgEntity->setIsBounce($bounce_type);
			$msgEntity->setPublisherId($admin_id);
			$em->persist($msgEntity);
			$em->flush();
			return  $this->parseData(['code' => 200,'openUrl'=>'/operate/msg_list.html','title'=>'发送消息']);
		}else {
			$id = $request->get('id',0);
			$data = ['msg_id' => $id];
			if($id){
				$msg = $this->getDoctrine()->getManager()->getRepository('AdminBundle:YsMessage')->find($id);
				if($msg){
					$data['msg'] = $msg;
				}
			}
			return $this->show('operate/msg_form', $data);
		}
	}

	/**
	 * msg详情
	 * @Route("/msg_detail/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function msgDetailAction(Request $request){
		$id = $request->get('id',0);
		if(empty($id)){
			return $this->_404();
		}
		$data = ['msg_id' => $id];
		if($id){
			$msg = $this->getDoctrine()->getManager()->getRepository('AdminBundle:YsMessage')->find($id);
			if($msg){
				$data['msg'] = $msg;
			}
		}
		return $this->show('operate/msg_detail', $data);

	}

}
