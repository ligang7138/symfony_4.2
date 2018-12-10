<?php
namespace App\Yszc\AdminBundle\Controller;

use App\Yszc\AdminBundle\Constant\MerchantConstant;
use App\Yszc\AdminBundle\Common\MobileDetect;
use App\Yszc\AdminBundle\Entity\AdminUsers;
use App\Yszc\AdminBundle\Entity\YsPartnerCoupon;
use App\Yszc\AdminBundle\Entity\YsPartnerDaturm;
use App\Yszc\AdminBundle\Entity\YsPartnerInfo;
use App\Yszc\AdminBundle\Entity\YsPartners;
use App\Yszc\AdminBundle\Services\City\Impl\CityServiceImpl;
use App\Yszc\AdminBundle\Services\Partner\Impl\PartnerServiceImpl;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * @Route("/shop")
 */
class ShopController extends CommonController
{
	/**
	 * 店铺列表页(后台)
	 * @Route("/list.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$conditions = $this->getListAndLimt();
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
		$partnerList = $partnerService->findShopList($conditions);

		$page = $this->get('page_service');
		$tabid = 'partner_list';
		$page->setPage($partnerList['count'], $request->request->get('p',1),true,$tabid);
		return $this->show(
			'shop/list',
			[
				'tabid' => $tabid,
				'list' => $partnerList['data'],
				'page' => $page->show(),
				'params' => $conditions,
				'partnerCatagory' => MerchantConstant::$merchant_catagory,
				'checkStatus' => MerchantConstant::$merchant_check_status,
				'is_normal' => MerchantConstant::$merchant_status,
			]
		);
	}
	/**
	 * 创建店铺(前台)
	 * @Route("/create.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\DBAL\ConnectionException
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
    public function createAction(Request $request){
	    if($this->forbidAdminAccess()){
		    return $this->_404();
	    }
	    /** @var AdminUsers $sessionUser */
	    $sessionUser = $this->container->get('security.token_storage')->getToken()->getUser();
	    $aid = $sessionUser->getAId();// 商户用户id
		$registPhone = $sessionUser->getAName();
	    /** @var EntityManager $em */
	    $em = $this->getDoctrine()->getManager();
	    $adminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);

	    $partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
	    if($request->getRealMethod() == 'POST'){

		    $isAgree = $request->get('is_agree');

		    if($isAgree != 'on'){

			    return $this->parseData(['code' => 500,'msg' => '请同意《开店服务协议》']);
		    }

			try{
				$em->getConnection()->beginTransaction();
				$partner->setIsAgree('2');
				$partner->setIsNormal(MerchantConstant::MERCHANT_STATUS_DISABLED);
				$em->persist($partner);
				$em->flush();
				$em->getConnection()->commit();
				return $this->parseData(['code' => 200,'msg' => '创建成功']);
			}catch (PDOException $e){
				return $this->parseData(['code' => 500,'msg' => '创建失败']);
				$em->getConnection()->rollBack();
			}

	    }else{

		    // 获取开户行信息
		    $open_bank = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyOpenBank')->findBy(['isAble'=>1]);

		    return $this->show(
			    'shop/shop_create',[
				    'adminUser' => $adminUser,
				    'bank_type' => $open_bank,
				    'partner' => $partner,
				    'registPhone' => $registPhone,
			    ]
		    );
	    }

    }

	/**
	 * 店铺基本信息设置(前台)
	 * @Route("/setup.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function setupAction(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		if(empty($partner)){
            $partner = new YsPartners();
            $partner->setAdminId($aid);
        }

        $partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);


		/** @var CityServiceImpl $cityService */
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');

		if($request->getRealMethod() == 'POST'){
			try{
				$em->getConnection()->beginTransaction();

				$result = $this->setUpSubmitPc($em,$partner,$partnerInfo,$aid,$request);

				if($result['code'] != 200){
					return $this->parseData($result);
				}
				$this->addMessage($partner->getPartnerId(),'店铺激活','店铺激活于'.date('Y-m-d H:i:s').'完成');
				$em->getConnection()->commit();

				return $this->parseData(['msg'=>'恭喜您，创建店铺成功','code' => 200,'openUrl'=>'/shop/setup.html']);
			}catch (\Exception $ex){
				$em->getConnection()->rollBack();
				return $this->parseData(['msg'=>$ex->getMessage(),'code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			// 商户上传的资料
			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');
			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['b','c']);

			$adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);

			$result = $this->checkIsActivate($partner);

			$partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);

			if($result['code'] != 200){
				return $this->show(
					'shop/shop_state',[
						'result' => $result
					]);
			}else{
				return $this->show(
					'shop/admin_shop_edit',[
						'province_lists'    => $province_list,
						'partnerDaturm'     => $partnerDaturm,
						'adminUser'         => $adminUserInfo,
						'partner'           => $partner,
						'partnerInfo'       => $partnerInfo,
						'partnerCoupons'    => $partnerCoupons
					]
				);
			}
		}

	}

	/**
	 * 店铺基本信息设置(wap)
	 * @Route("/wap_setup.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function setupWapAction(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

        if(empty($partner)){
            $partner = new YsPartners();
            $partner->setAdminId($aid);
        }
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);

		/** @var CityServiceImpl $cityService */
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');

		if($request->getRealMethod() == 'POST'){
            $em->getConnection()->beginTransaction();
			try{
				$partnerTiedCardResult = $this->partnerTiedCard($em,$partner->getAdminId(),$request);
				if($partnerTiedCardResult['code'] != 200){
					return $this->parseData($partnerTiedCardResult);
				}

				$result = $this->setUpSubmitWap($em,$partner,$partnerInfo,$aid,$request);
                $this->addMessage($partner->getPartnerId(),'店铺激活','店铺激活于'.date('Y-m-d H:i:s').'完成');
				if($result['code'] != 200){
					return $this->parseData($result);
				}else{
                    $ret = self::webService('pay', 'ident_auth', $partnerTiedCardResult['auth_params']);
                    if($ret['code'] != 2000){
                        throw new \Exception($ret['msg']);
                    }
                }

				$em->getConnection()->commit();

				return $this->parseData(['msg'=>'恭喜您，创建店铺成功','code' => 200,'openUrl'=>'/shop/setup.html']);
			}catch (\Exception $ex){
				$em->getConnection()->rollBack();
				return $this->parseData(['msg'=>$ex->getMessage(),'code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			// 商户上传的资料
			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');
			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['b','c']);

			$adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);

			$result = $this->checkIsActivate($partner);

			$partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);

			if($result['code'] != 200){
				return $this->show(
					'shop/shop_state',[
					'result' => $result
				]);
			}else{
				return $this->show(
					'shop/admin_shop_edit_wap',[
						'province_lists'    => $province_list,
						'partnerDaturm'     => $partnerDaturm,
						'adminUser'         => $adminUserInfo,
						'partner'           => $partner,
						'partnerInfo'       => $partnerInfo,
						'partnerCoupons'    => $partnerCoupons
					]
				);
			}
		}

	}

    /**
     * 店铺管理首页信息
     * @Route("/partner_info.html")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function partnerInfo(Request $request){
        if($this->forbidAdminAccess()){
            return $this->_404();
        }
        //$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$curUser = $this->container->get('security.token_storage')->getToken()->getUser();
		$aid = $curUser->getAId();// 商户用户id
	    $cityService = $this->createService('City.CityService');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

        if(!empty($partner)){
            $partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
            $partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);
            $partnerCheckInfo = $em -> getRepository('AdminBundle:YsPartnerCheck')->findOneBy(['partnerId' => $partner->getPartnerId()]);
            $partnerService = $this->createService('Partner.PartnerService');
            $partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['b','c']);
        }else{
            $partnerInfo = [];
        }

        // 同步商户服务编号
        $userInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);

        $data = [];
        if(!empty($partner)){
            $data['partner_detail_address'] = $cityService->getCityName($partnerInfo->getProvince()).$cityService->getCityName($partnerInfo->getCity()).$cityService->getCityName($partnerInfo->getCounty()).$partnerInfo->getPartnerDetailAddress();
            $data['partner_phone'] = $partner->getPartnerPhone();
            $data['partner_name'] = $partner->getPartnerName();

            $data['partner_status'] = $partner->getPartnerStatus();
            $data['partner_type'] = $partner->getPartnerType();
            $data['is_support_distribut'] = $partner->getIsSupportDistribut();
            $data['send_out_money'] = $partner->getSendOutMoney();
            $data['begin_distribut_time'] = $partner->getBeginDistributTime();
            $data['end_distribut_time'] = $partner->getEndDistributTime();
            $data['distribut_distance'] = $partner->getDistributDistance();

            if(isset($partnerDaturm['b']) && count($partnerDaturm['b'])>0){
                $data['partner_icon'] = $partnerDaturm['b'][0]['pd_url'];
            }else{
                $data['partner_icon'] = '';
            }

            if(isset($partnerDaturm['c']) && count($partnerDaturm['c'])>0){
                $data['partner_imgs'] = $partnerDaturm['c'];
            }else{
                $data['partner_imgs'] = [];
            }

            if($partnerCheckInfo){
                $data['check_pass_time'] = $partnerCheckInfo->getCheckUpdateTime()->format('Y-m-d H:i:s');
                $data['check_feedback'] = $partnerCheckInfo->getCheckFeedback();
            }
            $current_time = time();
            foreach ($partnerCoupons as $item){
                if($item->getPcStartTime()->getTimeStamp() <= $current_time && $item->getPcEndTime()->getTimeStamp() >= $current_time && $item->getPcType() == 0){
                    $data['partner_coupons'][] = '满'.$item->getPcBuyUp().'减'.$item->getPcBuyUpSubtraction();
                }
            }
            $data['partner_coupons'] = implode(' ',$data['partner_coupons']);
	        $data['partner_type'] = $partner->getPartnerType();
        }

        $inviteDo = $em->getRepository('AdminBundle:AdminSaleInvite')->findOneBy(['aId' => $aid,'aiType' => 0]);
	    $data['invite_code'] = '';
	    $data['invite_name'] = '';
        if(!empty($inviteDo)){
	        $data['invite_code'] = $inviteDo->getParentInviteCode();
//	        $data['invite_name'] = $inviteDo->getParentInviteCode().' '.$inviteDo->getAiAddName();
	        $adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $inviteDo->getAiParent()]);

	        if(!empty($adminUserInfo)){
		        $data['invite_name'] = $adminUserInfo->getATrueName();
	        }

        }
        if(!empty($userInfo)){
            $data['truename'] = $userInfo->getATrueName();
            $data['identno'] = $userInfo->getAIdentNo();
            $data['cardno'] = $userInfo->getACardNo();
            $data['open_bank_name'] = $userInfo->getAOpenBankName();
        }


		$data['aAddTime'] = $curUser->getAAddTime()->format('Y-m-d H:i:s');
		$data['aName'] = $curUser->getAName();

	    /*$data['service_code'] = $userInfo->getAiServiceNo();
	    if($data['service_code']){
            $channel = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyChannel')->findOneBy(['serviceCode' => $data['service_code'],'status' => 1]);
            $data['service_name'] = $channel->getTruename().' '.$channel->getMobile();
        }*/
        return $this->parseData([
            'data'           => $data,
        ]);

    }

    /**
	 * 商户绑卡
	 * @param $em EntityManager
	 * @param YsPartners $partner
	 * @param $partnerInfo
	 * @param $aid
	 * @param $request
	 * @return array
	 */
	private function partnerTiedCard($em,$aid,$request){

		$adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
		$adminUser = $em->getRepository('AdminBundle:AdminUsers')->find($aid);



		if(empty($adminUserInfo->getAIdentNo())){
            return ['msg'=>'请先进行实名认证','code'=>500,'closeCurrTab'=>false];
		}

		$identNo = $adminUserInfo->getAIdentNo();

		$auth_params = [
			'admin_id' => $aid,
			'bank_type' => 0
		];
		$verfiy_params = [];
		if(!empty($identNo)){
			$auth_params['ident_no'] = $identNo;
			$verfiy_params['cardNo'] = $identNo;
			$adminUserInfo->setAIdentNo($identNo);
		}else{
			return ['msg'=>'身份证号必填','code'=>500,'closeCurrTab'=>false];
		}
		$truename = $adminUserInfo->getATrueName();
		if(!empty($truename)) {
			$auth_params['u_true_name'] = $truename;
			$verfiy_params['realName'] = $truename;
			$adminUserInfo->setATrueName($truename);
		}else{
			return ['msg'=>'姓名必填','code'=>500,'closeCurrTab'=>false];
		}

		$user_type = $request->get('user_type',1);

		$cardNo = trim($request->get('card_no'));
		if(!empty($cardNo)){

            $adminList = $em->getRepository('AdminBundle:AdminUserInfo')->findBy(['aCardNo' => $cardNo]);

            if($adminList){
                $is_use = true;
            }else{
                $is_use = false;
            }

            foreach ($adminList as $item){
                if($item->getAId() == $aid){
                    $is_use = false;
                }
            }

            if($is_use){
                return ['msg'=>'银行卡号已使用','code'=>500,'closeCurrTab'=>false];
            }

			$auth_params['bank_code'] = $cardNo;
			$verfiy_params['bankcard'] = $cardNo;
			$adminUserInfo->setACardNo($cardNo);
		}else{
			return ['msg'=>'银行卡号必填','code'=>500,'closeCurrTab'=>false];
		}

		$openBankName = trim($request->get('bank_type'));
		if(!empty($openBankName)){
			$auth_params['bank_name'] = $openBankName;
			$adminUserInfo->setAOpenBankName($openBankName);
		}else{
			return ['msg'=>'开户行必填','code'=>500,'closeCurrTab'=>false];
		}

		$phone = trim($request->get('phone'));
		if(!empty($phone)){
			$auth_params['u_phone'] = $phone;
			$verfiy_params['Mobile'] = $phone;
			$adminUserInfo->setAPhone($phone);
		}else{
			$auth_params['u_phone'] = $adminUser->getAName();
		}


		$ret = self::webService('channel', 'verify_bank', $verfiy_params);
		if($ret['status'] != 2000){
			return ['msg'=>$ret['msg'],'code'=>500,'closeCurrTab'=>false];
		}else{
            return ['code'=>200,'auth_params'=>$auth_params];
        }
//		try{
//			if ($user_type == 2){
//				$params['bank_type'] = 1;
//				$branch_name = $request->get('branch_name','');
//				$adminUser->setAPartnerType(2);
//				if(empty($branch_name)){
//					return $this->parseData(['msg'=>'支行名必填','code'=>500,'closeCurrTab'=>false]);
//				}else{
//					$params['bank_child_name'] = $branch_name;
//					$adminUserInfo->setABankBranchName($branch_name);
//				}
//				$branch_code = $request->get('branch_code','');
//				if(empty($branch_code)){
//					return $this->parseData(['msg'=>'支行行号必填','code'=>500,'closeCurrTab'=>false]);
//				}else{
//					$params['bank_nums'] = $branch_code;
//					$adminUserInfo->setABankBranchCode($branch_code);
//				}
//				$em->persist($adminUser);
//			}
//			$ret = self::webService('pay', 'ident_auth', $auth_params);
//
//			if($ret['code'] == 2000){
//				$em->persist($adminUserInfo);
//				$em->flush();
//				return ['code'=>200,'openUrl'=>'/partner/authenticate.html','closeCurrTab'=>false];
//
//			}else{
//				return ['msg'=>$ret['msg'],'code'=>500,'closeCurrTab'=>false];
//			}
//		}catch (Exception $e){
//			return ['msg'=>"保存失败",'code'=>500,'closeCurrTab'=>false];
//		}
	}
	/**
	 * @param $em EntityManager
	 * @param YsPartners $partner
	 * @param $partnerInfo
	 * @param $aid
	 * @param $request
	 * @return array
	 */
	private function setUpSubmitPc($em,YsPartners $partner,$partnerInfo,$aid,$request){
		$partnerName = trim($request->get('partner_name'));
		if(empty($partnerName)){
			return ['code' => 500,'msg' => '店铺名字不能为空'];
		}else{
			$re = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerName' => $partnerName]);
			// 排除自己店铺名称的去重
			if(!is_null($re) && ($re !== $partner)){
				return ['code' => 500,'msg' => '店铺名已存在'];
			}
		}
		$partnerHeadImg = $request->get('headImg');

		$oldPartnerImgs = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'c']);

		$partnerImgs = $request->get('partnerImg');
		if(count($partnerImgs) > 3){
			return ['code' => 500,'msg' => '店铺图片必须上传至多三张'];
		}
		foreach ($oldPartnerImgs as $old){
			if(in_array($old->getPdUrl(),$partnerImgs)){
				unset($partnerImgs[array_search($old->getPdUrl(),$partnerImgs)]);
			}
		}
		$province = $request->get('provinces'); // 省
		$city = $request->get('city'); // 市
		$area = $request->get('area'); // 县
		if(empty($province) || empty($city) || empty($area)){
			return ['code' => 500,'msg' => '商家省市县必填'];
		}
		$detailAddress = trim($request->get('detail_address'));
		if(empty($detailAddress)){
			return ['code' => 500,'msg' => '商家详细地址必填'];
		}
		$partnerPhone = $request->get('partner_phone');

		if(empty($partnerPhone)){
			return ['code' => 500,'msg' => '商家电话必填'];
		}elseif(!preg_match("/^\d*$/",$partnerPhone)){
			return ['code' => 500,'msg' => '请输入正确的商家电话'];
		}
		$partnerIntro = $request->get('partner_intro');
		$partnerNotice = $request->get('partner_notice');

		$partnerLng = $request->get('partner_lng');
		if(empty($partnerLng)){
			return ['code' => 500,'msg' => '商家地址经度不能为空'];
		}
		$partnerLat = $request->get('partner_lat');
		if(empty($partnerLat)){
			return ['code' => 500,'msg' => '商家地址纬度不能为空'];
		}

		if(!empty($partnerHeadImg)){
			$oldPartnerHeadImg = $em->getRepository('AdminBundle:YsPartnerDaturm')->findOneBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'b']);
			if(is_null($oldPartnerHeadImg)){
				$partnerHeadImgDao = new YsPartnerDaturm();
				$partnerHeadImgDao->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerHeadImgDao->setPartnerId($partner->getPartnerId());
				$partnerHeadImgDao->setAdminId($aid);
				$partnerHeadImgDao->setPdType('b');
				$partnerHeadImgDao->setPdUrl($partnerHeadImg);
				$em->persist($partnerHeadImgDao);
				$em->flush();
			}else{
				$oldPartnerHeadImg->setPdUrl($partnerHeadImg);
				$em->persist($oldPartnerHeadImg);
				$em->flush();
			}
		}

		// 插入店铺图片
		foreach ($partnerImgs as $k => $v){
			$partnerImg = new YsPartnerDaturm();
			$partnerImg->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
			$partnerImg->setPartnerId($partner->getPartnerId());
			$partnerImg->setAdminId($aid);
			$partnerImg->setPdType('c');
			$partnerImg->setPdUrl($v);
			$em->persist($partnerImg);
			$em->flush();
		}

		$partnerInfo->setProvince($province);
		$partnerInfo->setCity($city);
		$partnerInfo->setCounty($area);
		$partnerInfo->setPartnerDetailAddress($detailAddress);

		$partner->setPartnerName($partnerName);
		$partner->setPartnerPhone($partnerPhone);
		$partner->setPartnerNotice($partnerNotice);
		$partner->setPartnerIntro($partnerIntro);
		$partner->setPartnerLng($partnerLng);
		$partner->setPartnerLat($partnerLat);
		$partner->setIsNormal(MerchantConstant::MERCHANT_STATUS_ENABLED);

		$this->addMessage($partner->getPartnerId(),'店铺激活','恭喜您，店铺已成功激活，立即去添加商品吧～');
		$em->persist($partner);
		$em->persist($partnerInfo);
		$em->flush();
		$request->getSession()->set('partner_id',$partner->getPartnerId());// 设置完店铺信息给session存入id值【2018-8-17  cxl】
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');

		$partnerService -> geoAdd($city,$partnerLng,$partnerLat, $partner->getPartnerId());
		return ['code' => 200,'msg' => '店铺基本信息修改成功'];
	}

	/**
	 * @param $em EntityManager
	 * @param YsPartners $partner
	 * @param $partnerInfo
	 * @param $aid
	 * @param $request
	 * @return array
	 */
	private function setUpSubmitWap($em,YsPartners $partner,$partnerInfo,$aid,$request){
		$partnerName = trim($request->get('partner_name'));
		if(empty($partnerName)){
			return ['code' => 500,'msg' => '店铺名字不能为空'];
		}else{
			$re = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerName' => $partnerName]);
			// 排除自己店铺名称的去重
			if(!is_null($re) && ($re !== $partner)){
				return ['code' => 500,'msg' => '店铺名已存在'];
			}
		}
		$partnerHeadImg = $request->get('headImg');

		if(empty($partnerHeadImg)){
			return ['code' => 500,'msg' => '店铺头像必须上传至少一张'];
		}
        if($request->request->has('is_agree')){
            $isAgree = $request->get('is_agree');

            if($isAgree != 'on'){
                return ['code' => 500,'msg' => '请同意《开店服务协议》'];
            }
        }



        $oldPartnerImgs = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'c']);


		$partnerImgs = $request->get('partnerImg');
		if(count($partnerImgs) > 3){
			return ['code' => 500,'msg' => '店铺图片必须上传至多三张'];
		}
		$delImages = [];
		foreach ($oldPartnerImgs as $old){
			if(in_array($old->getPdUrl(),$partnerImgs)){
				unset($partnerImgs[array_search($old->getPdUrl(),$partnerImgs)]);
			}else{
                $delImages[] = $old;
            }
		}

		foreach ($delImages as $item){
            $em->remove($item);
            $em->flush();
        }

		$province = $request->get('provinces'); // 省
		$city = $request->get('city'); // 市
		$area = $request->get('area'); // 县
		if(empty($province) || empty($city) || empty($area)){
			return ['code' => 500,'msg' => '商家省市县必填'];
		}
		$detailAddress = trim($request->get('detail_address'));
		if(empty($detailAddress)){
			return ['code' => 500,'msg' => '商家详细地址必填'];
		}
		$partnerPhone = $request->get('partner_phone');

		if(empty($partnerPhone)){
			return ['code' => 500,'msg' => '商家电话必填'];
		}elseif(!preg_match("/^\d*$/",$partnerPhone)){
			return ['code' => 500,'msg' => '请输入正确的商家电话'];
		}
		$partnerIntro = $request->get('partner_intro'); //商家颉
		$partnerNotice = $request->get('partner_notice');

		$partnerLng = $request->get('partner_lng');
		if(empty($partnerLng)){
			return ['code' => 500,'msg' => '商家地址经度不能为空'];
		}
		$partnerLat = $request->get('partner_lat');
		if(empty($partnerLat)){
			return ['code' => 500,'msg' => '商家地址纬度不能为空'];
		}

        $partner->setPartnerName($partnerName);
        $partner->setPartnerPhone($partnerPhone);
        $partner->setPartnerNotice($partnerNotice);
        $partner->setPartnerIntro($partnerIntro);
        $partner->setPartnerLng($partnerLng);
        $partner->setPartnerLat($partnerLat);
        $partner->setIsAgree(2);
        $partner->setIsNormal(MerchantConstant::MERCHANT_STATUS_ENABLED);
        $partner->setPartnerUpdateTime(new \DateTime(date('Y-m-d H:i:s')));

        $em->persist($partner);
        $em->flush();
        $partnerInfo->setPartnerId($partner->getPartnerId());

		if(!empty($partnerHeadImg)){
			$oldPartnerHeadImg = $em->getRepository('AdminBundle:YsPartnerDaturm')->findOneBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'b']);
			if(is_null($oldPartnerHeadImg)){
				$partnerHeadImgDao = new YsPartnerDaturm();
				$partnerHeadImgDao->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerHeadImgDao->setPartnerId($partner->getPartnerId());
				$partnerHeadImgDao->setAdminId($aid);
				$partnerHeadImgDao->setPdType('b');
				$partnerHeadImgDao->setPdUrl($partnerHeadImg);
				$em->persist($partnerHeadImgDao);
				$em->flush();
			}else{
				$oldPartnerHeadImg->setPdUrl($partnerHeadImg);
				$em->persist($oldPartnerHeadImg);
				$em->flush();
			}
		}

		// 插入店铺图片
		foreach ($partnerImgs as $k => $v){
			$partnerImg = new YsPartnerDaturm();
			$partnerImg->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
			$partnerImg->setPartnerId($partner->getPartnerId());
			$partnerImg->setAdminId($aid);
			$partnerImg->setPdType('c');
			$partnerImg->setPdUrl($v);
			$em->persist($partnerImg);
			$em->flush();
		}

		$partnerInfo->setProvince($province);
		$partnerInfo->setCity($city);
		$partnerInfo->setCounty($area);
		$partnerInfo->setPartnerDetailAddress($detailAddress);



		$this->addMessage($partner->getPartnerId(),'店铺激活','恭喜您，店铺已成功激活，立即去添加商品吧～');
		$em->persist($partner);
		$em->persist($partnerInfo);
		$em->flush();
		$request->getSession()->set('partner_id',$partner->getPartnerId());// 设置完店铺信息给session存入id值【2018-8-17  cxl】
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');

		$partnerService -> geoAdd($city,$partnerLng,$partnerLat, $partner->getPartnerId());
		return ['code' => 200,'msg' => '店铺基本信息修改成功'];
	}

	/**
	 * 商家配送(前台)
	 * @Route("/distribut.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function distribut(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

		if($request->getRealMethod() == 'POST'){
			$this->distributSubmint($em,$partner,$request);
			return $this->parseData(['msg'=>'配送信息设置成功','code'=>200,'openUrl' => '/shop/distribut.html','closeCurrTab'=>false]);
		}else{
			$result = $this->checkIsActivate($partner);

			if($result['code'] != 200){
				return $this->show(
					'shop/shop_state',[
					'result' => $result
				]);
			}else {
				return $this->show(
					'shop/shop_distribut', [
						'partner' => $partner
					]
				);
			}
		}
	}

	/**
	 * 配送管理提交数据公共方法
	 * @param $em
	 * @param $partner
	 * @param $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	private function distributSubmint($em,$partner,$request){
		$isSupportDistribut = $request->get('is_support_distribut',2);// 是否支持配送

		if(empty($isSupportDistribut)){
			$isSupportDistribut = 2;
//			return ['msg'=>'配送服务必须选','code'=>500,'closeCurrTab'=>false];
		}
		// 支持配送
		if($isSupportDistribut == 1){
			$distance = $request->get('distance'); // 配送范围
			if(empty($distance)){
				return ['msg'=>'配送范围必填','code'=>500,'closeCurrTab'=>false];
			}

			$freeFreight = $request->get('free_freight'); // 是否免运费
			if(empty($freeFreight)){
				return ['msg'=>'配送费必须选','code'=>500,'closeCurrTab'=>false];
			}
			// 设置运费的情况
			if($freeFreight == 1){
				$lowestFreightDistance = $request->get('lowestFreightDistance');// 最低运费距离范围
				$lowestFreightMoney = $request->get('lowestFreightMoney');// 最低运费统一价
				$additionFreightDistance = $request->get('additionFreightDistance');// 附加运费距离范围
				$additionFreightMoney = intval(trim($request->get('additionFreightMoney')));// 附加运费距离价格 ，每增加多少公里增加多少钱
				if(!empty($additionFreightMoney) && $additionFreightMoney > 10){
					return ['msg'=>'增加运费不能超过10元','code'=>500,'closeCurrTab'=>false];
				}
			}
			$sendOutMoney = $request->get('sendOutMoney');// 起送价
			if(empty($sendOutMoney)){
				return ['msg'=>'订单起送金额必填','code'=>500,'closeCurrTab'=>false];
			}
			$beginDistributTime = $request->get('beginDistributTime');// 配送开始时间
			if(empty($beginDistributTime)){
				return ['msg'=>'配送开始时间必填','code'=>500,'closeCurrTab'=>false];
			}
			$endDistributTime = $request->get('endDistributTime');// 配送结束时间
			if(empty($endDistributTime)){
				return ['msg'=>'配送结束时间必填','code'=>500,'closeCurrTab'=>false];
			}

			$beginDistributTimeArr = explode(':',$beginDistributTime);
			$endDistributTimeArr = explode(':',$endDistributTime);
			$beginSeconds = $beginDistributTimeArr[0]*3600 + $beginDistributTimeArr[1] + $beginDistributTimeArr[2];
			$endSeconds = $endDistributTimeArr[0]*3600 + $endDistributTimeArr[1] + $endDistributTimeArr[2];
			if($beginSeconds > $endSeconds){
				return ['msg'=>'配送开始时间不能大于配送结束时间','code'=>500,'closeCurrTab'=>false];
			}

			// 不支持配送的时候，之前的配送信息不清空
			$partner->setIsSupportDistribut($isSupportDistribut);
			$partner->setDistributDistance($distance);
			$partner->setFreeFreight($freeFreight);
			$partner->setLowestFreightDistance($lowestFreightDistance);
			$partner->setLowestFreightMoney($lowestFreightMoney);
			$partner->setAdditionFreightDistance($additionFreightDistance);
			$partner->setAdditionFreightMoney($additionFreightMoney);
			$partner->setSendOutMoney($sendOutMoney);
			$partner->setBeginDistributTime($beginDistributTime);
			$partner->setEndDistributTime($endDistributTime);
		}


		$em->persist($partner);
		$em->flush();
		return ['code'=>200,'msg' => '店铺配送信息修改成功'];
	}

	/**
	 * 商家优惠(前台)
	 * @Route("/coupon.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function coupon(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

		$partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);

		if($request->getRealMethod() == 'POST'){
			$this->couponSubmit($em,$partnerCoupons,$partner,$request);
			return $this->parseData(['msg'=>'优惠信息设置成功','code'=>200,'openUrl' => '/shop/distribut.html','closeCurrTab'=>false]);
		}else{
			$result = $this->checkIsActivate($partner);

			if($result['code'] != 200){
				return $this->show(
					'shop/shop_state',[
					'result' => $result
				]);
			}else {
				return $this->show(
					'shop/shop_coupon', [
						'partnerCoupons' => $partnerCoupons
					]
				);
			}
		}
	}

	/**
	 * 优惠活动提交数据公共方法
	 * @param $em
	 * @param $partnerCoupons
	 * @param $partner
	 * @param $request
	 */
	private function couponSubmit($em,$partnerCoupons,$partner,$request){
		$startTime = $request->get('start_time');
		$endTime = $request->get('end_time');
		$buyUp = $request->get('buy_up',0);
		$buyUpSubtraction = $request->get('buy_up_subtraction',0);
		$ids = $request->get('ids');
		$buyUpArr = array_diff_assoc($buyUp,array_unique($buyUp));

		if(count($buyUpArr) > 0){
			return ['code' => 500,'msg' => '购满金额不能重复'];
		}
		// 修改已有的优惠活动
		$newPartnerCoupons = [];

		foreach ($partnerCoupons as $partnerCoupon){
			if(!in_array($partnerCoupon->getPcId(),$ids)){
				$em->remove($partnerCoupon);
				$em->flush();
			}else{
				$newPartnerCoupons[$partnerCoupon->getPcId()] = $partnerCoupon;
			}
		}
		foreach ($startTime as $k => $v){
			if(!empty($ids[$k])){
				$partnerCoupon = $newPartnerCoupons[$ids[$k]];
			}else{
				if(strtotime($startTime[$k]) < time() || strtotime($startTime[$k]) > strtotime($endTime[$k])) return ['code' => 500,'msg' => '请选择正确的时间范围'];
				if(intval($buyUp[$k]) <= 0){
					return ['code' => 500,'msg' => '购满金额必填'];
				}
				if(intval($buyUpSubtraction[$k]) <= 0){
					return ['code' => 500,'msg' => '购满优惠金额必填'];
				}
				$partnerCoupon = new YsPartnerCoupon();
				$partnerCoupon->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerCoupon->setPartnerId($partner->getPartnerId());
			}
			$partnerCoupon->setPcStartTime(new \DateTime($startTime[$k]));
			$partnerCoupon->setPcEndTime(new \DateTime($endTime[$k]));
			$partnerCoupon->setPcBuyUp($buyUp[$k]);
			$partnerCoupon->setPcBuyUpSubtraction($buyUpSubtraction[$k]);
			$em->persist($partnerCoupon);
			$em->flush();
		}
		return ['code'=>200,'msg' => '店铺优惠信息修改成功'];
	}

	/**
	 * 店铺基本信息，配送管理，优惠活动 编辑(后台)
	 * @param Request $request
	 * @Route("/shop-edit/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function shopEditAction(Request $request){
		$pid = $request->get('id');
		if($pid <= 0){
			return $this->_404();
		}
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $pid]);

		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);

		// 商户上传的资料
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
		$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['b','c']);

		/** @var CityServiceImpl $cityService */
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');

		$partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);

		if($request->getRealMethod() == 'POST'){
			try{
				$em->getConnection()->beginTransaction();

				$resultSet = $this->setUpSubmitPc($em,$partner,$partnerInfo,$partner->getAdminId(),$request);
				if($resultSet['code'] != 200){
					return $this->parseData($resultSet);
				}

				$resultDis = $this->distributSubmint($em,$partner,$request);

				if($resultDis['code'] != 200){
					return $this->parseData($resultDis);
				}

				$resultCou = $this->couponSubmit($em,$partnerCoupons,$partner,$request);
				if($resultCou['code'] != 200){
					return $this->parseData($resultCou);
				}

				$em->getConnection()->commit();
				return $this->parseData(['msg'=>'店铺设置成功','code'=>200,'openUrl' => '/shop/list.html','closeCurrTab'=>false]);
			}catch(PDOException $e){
				return $this->parseData(['msg'=>'店铺设置失败','code'=>500,'openUrl' => '/shop/list.html','closeCurrTab'=>false]);
				$em->getConnection()->rollBack();
			}
		}else{
			if($this->forbidPartnerAccess()){
				return $this->_404();
			}
			return $this->show(
				'shop/admin_shop_edit',[
				'province_lists'    => $province_list,
				'partnerDaturm'     => $partnerDaturm,
				'partner'           => $partner,
				'partnerInfo'       => $partnerInfo,
				'partnerCoupons' => $partnerCoupons
			]);
		}
	}

	/**
	 * 店铺详情页（后台）
	 * @Route("/detail/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function detailAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$pid = $request->get('id');
		if($pid <= 0){
			return $this->show('error/data_error');
		}
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $pid]);

		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
		$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['b','c']);

		/** @var CityServiceImpl $cityService */
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');

		$partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);
		return $this->show(
			'shop/admin_detail',[
			'province_lists'    => $province_list,
			'partnerDaturm'     => $partnerDaturm,
			'partner'           => $partner,
			'partnerInfo'       => $partnerInfo,
			'partnerCoupons'    => $partnerCoupons
		]);
	}
	/**
	 * 搜索设置（后台）
	 * @param Request $request
	 * @Route("/search-set/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function searchSetAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$id = $request->get('id');
		if($id <= 0){
			return $this->show('error/data_error');
		}
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $id]);
		if($request->getRealMethod() == 'POST'){
			if(empty($partner->getPartnerServiceCode())){
//				echo '该商户还没有服务编号，不能修改！';die;
				return $this->parseData(['msg'=>'该商户还没有服务编号，不能修改！','code'=>500,'openUrl' => '/shop/list.html','closeCurrTab'=>false]);
			}
			$isLonely = $request->get('is_lonely');
			$partner->setIsLonely($isLonely);
			$em->persist($partner);
			$em->flush();
			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');
			$partnerService -> addLonelySet(intval($partner->getPartnerId()),intval($partner->getPartnerServiceCode()),intval($isLonely));
			return $this->parseData(['msg'=>'店铺搜索设置成功','code'=>200,'openUrl' => '/shop/list.html','closeCurrTab'=>false]);
		}else{
			return $this->show(
			'shop/set_search',[
				'partner' => $partner
			]);
		}
	}

	/**
	 * 店铺状态设置（后台）
	 * @param Request $request
	 * @Route("/partner-set/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function partnerSetAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$id = $request->get('id');
		if($id <= 0){
			return $this->show('error/data_error');
		}
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $id]);

		if($request->getRealMethod() == 'POST'){
			if(empty($partner->getPartnerName())){
				return $this->parseData(['msg'=>'您还没有完成店铺设置！','code'=>500,'closeCurrTab'=>false]);
			}
			$isNormal = $request->get('is_normal');
			$partner->setIsNormal($isNormal);
			$em->persist($partner);
			$em->flush();
			return $this->parseData(['msg'=>'店铺设置成功','code'=>200,'openUrl' => '/shop/list.html','closeCurrTab'=>false]);
		}else{
			return $this->show(
				'shop/edit_shop_state',[
					'partner' => $partner
				]);
		}
	}
	/**
	 * 检查店铺是否激活
	 * @param $partner YsPartners
	 */
	public function checkIsActivate($partner){
		if($partner->getIsAgree() == 1){
			return ['code' => 202,'msg' => '店铺没有激活'];
		}elseif($partner->getPartnerStatus() != MerchantConstant::MERCHANT_CHECK_SUCCEED){
			return ['code' => 201,'msg' => '商户审核中，请耐心等待……'];
		}else{
			return ['code' => 200];
		}
	}
	/**
	 * 修改获取店铺基本信息
	 * @Route("/base-info.html")
	 * @param Request $request
	 */
	public function shopBaseInfo(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
        if(empty($partner)){
            return $this->parseData(['msg'=>'请先创建店铺!']);
        }
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);

		// 商户上传的资料
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');

		$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['b','c']);

		if($request->getRealMethod() == 'POST'){
			$result = $this->setUpSubmitWap($em,$partner,$partnerInfo,$aid,$request);
            return $this->parseData($result);
		}else{
            $cityService = $this->createService('City.CityService');
            $partner = self::object2array($partner);
            $partner['partner_address'] = $cityService->getCityName($partnerInfo->getProvince()).$cityService->getCityName($partnerInfo->getCity()).$cityService->getCityName($partnerInfo->getCounty());
            $partner['partnerDetailAddress'] = $partnerInfo->getPartnerDetailAddress();
            $partner['province'] = $partnerInfo->getProvince();
            $partner['city'] = $partnerInfo->getCity();
            $partner['city_name'] = $cityService->getCityName($partnerInfo->getCity());
            $partner['county'] = $partnerInfo->getCounty();
            $result = [
				'partner' => $partner,
				'partner_daturm' => self::object2array($partnerDaturm),
			];
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$result]);
		}

	}

	/**
	 * 修改获取店铺配送信息
	 * @Route("/distribut-info.html")
	 * @param Request $request
	 */
	public function shopDistribut(Request $request)
	{
		if ($this->forbidAdminAccess()) {
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		if($request->getRealMethod() == 'POST'){
			$result = $this->distributSubmint($em,$partner,$request);
			if($result['code'] != 200){
				return $this->parseData($result);
			}
			return $this->parseData(['msg'=>'店铺配送信息修改成功','code'=>200]);
		}else{
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>self::object2array($partner)]);
		}
	}

	/**
	 * 修改获取店铺配送信息
	 * @Route("/coupon-info.html")
	 * @param Request $request
	 */
	public function shopCoupon(Request $request)
	{
		if ($this->forbidAdminAccess()) {
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

		$partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);

		if($request->getRealMethod() == 'POST'){
			$result = $this->couponSubmit($em,$partnerCoupons,$partner,$request);
			if($result['code'] != 200){
				return $this->parseData($result);
			}
			return $this->parseData(['msg'=>'店铺优惠信息修改成功','code'=>200]);
		}else{
			$result = [
				'coupons' => self::object2array($partnerCoupons),
				'partner' => self::object2array($partner),
			];
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$result]);
		}
	}

    /**
     * 修改店铺活动
     * @Route("/coupon-edit.html")
     * @param Request $request
     */
    public function couponEdit(Request $request)
    {
        if ($this->forbidAdminAccess()) {
            return $this->_404();
        }
        $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

        $partnerCoupons = $em->getRepository('AdminBundle:YsPartnerCoupon')->findBy(['partnerId' => $partner->getPartnerId()]);

        if($request->getRealMethod() == 'POST'){
            $startTime = $request->get('start_time');
            $endTime = $request->get('end_time');
            $buyUp = $request->get('buy_up',0);
            $buyUpSubtraction = $request->get('buy_up_subtraction',0);
            $ids = $request->get('ids');

            // 修改已有的优惠活动
            $newPartnerCoupons = [];
            $is_diff = false;
            foreach ($partnerCoupons as $partnerCoupon){
                if($partnerCoupon->getPcBuyUp() == $buyUp){
                    $is_diff = true;
                }
                $newPartnerCoupons[$partnerCoupon->getPcId()] = $partnerCoupon;
            }

            if(!empty($ids)){
                $partnerCoupon = $newPartnerCoupons[$ids];
            }else{
                if($is_diff){
                    return $this->parseData(['msg'=>'购满金额不能重复','code'=>500]);
                }
                if(strtotime($startTime) < time() || strtotime($startTime) > strtotime($endTime)){
                    return $this->parseData(['code' => 500,'msg' => '请选择正确的时间范围']);
                }
                if(floatval($buyUp) <= 0){
                    return $this->parseData(['code' => 500,'msg' => '购满金额必填']);
                }
                if(floatval($buyUpSubtraction) <= 0){
                    return $this->parseData(['code' => 500,'msg' => '购满优惠金额必填']);
                }

                $partnerCoupon = new YsPartnerCoupon();
                $partnerCoupon->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
                $partnerCoupon->setPartnerId($partner->getPartnerId());
            }
            $partnerCoupon->setPcStartTime(new \DateTime($startTime));
            $partnerCoupon->setPcEndTime(new \DateTime($endTime));
            $partnerCoupon->setPcBuyUp($buyUp);
            $partnerCoupon->setPcBuyUpSubtraction($buyUpSubtraction);
            $em->persist($partnerCoupon);
            $em->flush();
            return $this->parseData(['msg'=>'店铺优惠信息修改成功','code'=>200]);
        }else{
            $result = [
                'coupons' => self::object2array($partnerCoupons),
                'partner' => self::object2array($partner),
            ];
            return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$result]);
        }
    }

    /**
     * 删除店铺活动
     * @Route("/coupon-del.html")
     * @param Request $request
     */
    public function couponDel(Request $request)
    {
        if ($this->forbidAdminAccess()) {
            return $this->_404();
        }

        if($request->getRealMethod() == 'POST'){
            $id = $request->get('id');

            $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

            $partnerCoupon = $em->getRepository('AdminBundle:YsPartnerCoupon')->findOneBy(['partnerId' => $partner->getPartnerId(),'pcId' =>$id]);

            if($partnerCoupon){
                $em->remove($partnerCoupon);
                $em->flush();
                return $this->parseData(['msg'=>'店铺优惠信息删除成功','code'=>200]);
            }else{
                return $this->parseData(['msg'=>'未找到该活动信息','code'=>500]);
            }
        }else{
            $result = [
                'coupons' => self::object2array($partnerCoupons),
                'partner' => self::object2array($partner),
            ];
            return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$result]);
        }
    }
}
