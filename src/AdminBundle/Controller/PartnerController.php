<?php
namespace App\AdminBundle\Controller;
use App\AdminBundle\Common\CommonFunction;
use App\AdminBundle\Common\MobileDetect;
use App\AdminBundle\Constant\AdminUserConstant;
use App\AdminBundle\Constant\MerchantConstant;
use App\AdminBundle\Entity\AdminUserContact;
use App\AdminBundle\Entity\AdminUserInfo;
use App\AdminBundle\Entity\PartnerCheck;
use App\AdminBundle\Entity\YsPartnerCheck;
use App\AdminBundle\Entity\YsPartnerCheckLog;
use App\AdminBundle\Entity\YsPartnerDaturm;
use App\AdminBundle\Entity\YsPartnerInfo;
use App\AdminBundle\Entity\YsPartners;
use App\AdminBundle\Services\City\Impl\CityServiceImpl;
use App\AdminBundle\Services\Partner\Impl\PartnerServiceImpl;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/partner")
 */
class PartnerController extends CommonController
{
	/**
	 * 商户列表页(后台)
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
	    $partnerList = $partnerService->findList($conditions);

	    $page = $this->get('page_service');
	    $tabid = 'partner_list';
	    $page->setPage($partnerList['count'], $request->request->get('p',1),true,$tabid);
	    return $this->show(
		    'partner/list',
		    [
			    'tabid' => $tabid,
			    'list' => $partnerList['data'],
			    'page' => $page->show(),
			    'params' => $conditions,
			    'partnerCatagory' => MerchantConstant::$merchant_catagory,
			    'checkStatus' => MerchantConstant::$merchant_check_status,
		    ]
	    );
    }

	/**
	 * 商户实名认证（个人）（前台）
	 * @Route("/authenticate.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function authenticationAction(Request $request){
	    if($this->forbidAdminAccess()){
		    return $this->_404();
	    }

	    $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
	    /** @var EntityManager $em */
	    $em = $this->getDoctrine()->getManager();
	    $adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
	    $adminUser = $em->getRepository('AdminBundle:AdminUsers')->find($aid);


	    // 获取开户行信息
//	    $open_bank = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyOpenBank')->findBy(['isAble'=>1]);
	    /** @var PartnerServiceImpl $partnerService */
	    /*$partnerService = $this->createService('Partner.PartnerService');
	    $open_bank = $partnerService->getOpenBankList();*/
	    if($request->getRealMethod() == 'POST'){
		    if(is_null($adminUserInfo)){
			    $adminUserInfo = new AdminUserInfo();
			    $adminUserInfo->setAId($aid);
		    }

	    	$identNo = trim($request->get('ident_no'));
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
			    return $this->parseData(['msg'=>'身份证号必填','code'=>500,'closeCurrTab'=>false]);
		    }
		    $truename = trim($request->get('truename'));
		    if(!empty($truename)) {
			    $auth_params['u_true_name'] = $truename;
			    $verfiy_params['realName'] = $truename;
			    $adminUserInfo->setATrueName($truename);
		    }else{
			    return $this->parseData(['msg'=>'姓名必填','code'=>500,'closeCurrTab'=>false]);
		    }
		    $user_type = $request->get('user_type',1);
		    if(empty($user_type)){
			    return $this->parseData(['msg'=>'商户类型错误','code'=>500,'closeCurrTab'=>false]);
		    }

		    $cardNo = trim($request->get('card_no'));
		    if(!empty($cardNo)){
			    $auth_params['bank_code'] = $cardNo;
			    $verfiy_params['bankcard'] = $cardNo;
			    $adminUserInfo->setACardNo($cardNo);
		    }else{
			    return $this->parseData(['msg'=>'银行卡号必填','code'=>500,'closeCurrTab'=>false]);
		    }

		    $openBankName = trim($request->get('bank_type'));
		    if(!empty($openBankName)){
			    $auth_params['bank_name'] = $openBankName;
			    $adminUserInfo->setAOpenBankName($openBankName);
		    }else{
			    return $this->parseData(['msg'=>'开户行必填','code'=>500,'closeCurrTab'=>false]);
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
			    return $this->parseData(['msg'=>$ret['msg'],'code'=>500,'closeCurrTab'=>false]);
		    }
		    $em->getConnection()->beginTransaction();
		    try{
		        if ($user_type == 2){
		        	$params['bank_type'] = 1;
			        $branch_name = $request->get('branch_name','');
					$adminUser->setAPartnerType(2);
					if(empty($branch_name)){
						return $this->parseData(['msg'=>'支行名必填','code'=>500,'closeCurrTab'=>false]);
					}else{
						$params['bank_child_name'] = $branch_name;
						$adminUserInfo->setABankBranchName($branch_name);
					}
			        $branch_code = $request->get('branch_code','');
			        if(empty($branch_code)){
				        return $this->parseData(['msg'=>'支行行号必填','code'=>500,'closeCurrTab'=>false]);
			        }else{
			        	$params['bank_nums'] = $branch_code;
				        $adminUserInfo->setABankBranchCode($branch_code);
			        }
			        $em->persist($adminUser);
			    }
			    $ret = self::webService('pay', 'ident_auth', $auth_params);

			    if($ret['code'] == 2000){
					$em->persist($adminUserInfo);
					$em->flush();
					$em->commit();
					return $this->parseData(['code'=>200,'openUrl'=>'/partner/authenticate.html','closeCurrTab'=>false]);

				}else{
					return $this->parseData(['msg'=>$ret['msg'],'code'=>500,'closeCurrTab'=>false]);
				}
		    }catch (Exception $e){
			    $em->rollback();
			    return $this->parseData(['msg'=>"保存失败",'code'=>500,'closeCurrTab'=>false]);
		    }
	    }else{
		    return $this->show(
			    'partner/certification',
			    [
			    	'adminUserType' => $adminUser->getAPartnerType(),
				    'adminUser' => $adminUserInfo
			    ]
		    );
	    }
    }

	/**
	 * 根据银行卡号获取信息
	 * @Route("/bank_info.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function getBankInfoByBankId(Request $request){
        $bank_no = $request->get('bank_no','');
        if(empty($bank_no)){
            return $this->parseData(['code' => 500,'msg' => '银行卡号不能为空']);
        }
        $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
        $em = $this->getDoctrine()->getManager();
        $adminList = $em->getRepository('AdminBundle:AdminUserInfo')->findBy(['aCardNo' => $bank_no]);

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
            return $this->parseData(['code' => 500,'msg' => '银行卡已使用']);
        }

        $ret = self::webService('pay', 'bank_card_info', ['bank_code' => $bank_no]);
        if($ret['code'] == 2000){
            return $this->parseData(['msg'=>'获取成功','code'=>200,'data' =>$ret]);
        }else{
            return $this->parseData(['msg'=>$ret['msg'],'code'=>500]);
        }
    }

	/**
	 * 商户详情页（前后台）
	 * @Route("/detail/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @Method("GET")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function detailAction(Request $request){
		$id = $request->get('id');
		if($id <= 0){
			return $this->_404();
		}
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $id]);
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $id]);
		$checkInfo = $em->getRepository('AdminBundle:YsPartnerCheck')->findOneBy(['partnerId' => $id]);

		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
//			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),array_keys($this->getParameter('admin_bundle')['file_type']));
		$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);

		$adminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $partner->getAdminId()]);

		$adminUserContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $partner->getAdminId(),'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
		// 获取开户行信息
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
		$open_bank = $partnerService->getOpenBankList();

		// 受理营业部
		$departments = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyDept')->findBy(['dtParent' => $this->getParameter('admin_bundle')['admissible_business_department']]);

		// 最新审核
		$partnerCheck = $em->getRepository('AdminBundle:YsPartnerCheck')->findOneBy(['partnerId' => $id]);

		// 获取商品分类
		$salesCategory = $em->getRepository('AdminBundle:YsGoodsCate')->findBy(['gcNode' => 0,'gcStatus' => 1]);

		// 获取省信息
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');
		return $this->show(
			'partner/admin_detail',
			[
				'partner'           => $partner,
				'adminUser'         => $adminUser,
				'partnerInfo'       => $partnerInfo,
				'partnerCheck'      => $partnerCheck,
				'checkInfo'         => $checkInfo,
				'partnerDaturm'     => $partnerDaturm,
				'bank_type'         => $open_bank,
				'adminContacts'     => $adminUserContacts,
				'salesCategory'     => $salesCategory,
				'province_lists'    => $province_list,
				'departments'       => $departments,
			]
		);
	}

	/**
	 * 商户编辑页(后台)
	 * @param Request $request
	 * @Route("/edit/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		$em = $this->getDoctrine()->getManager();
		if($request->getRealMethod() == 'POST'){
			$id = $request->get('id',0);
			if($id <= 0){
				return $this->show('error/data_error');
			}
			$em = $this->getDoctrine()->getManager();

			$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $id]);

			$aid = $partner->getAdminId();// 商户用户id

			$result = $this->submitBaseInfo($em,$aid,$request,false);
			if($result['code'] != 200){
				return $this->parseData($result);
			}
			if($request->get('storage') == 1){
				return $this->parseData(['code' => 200,'msg' => '操作成功','openUrl' => '/partner/edit/'.$id.'.html','closeCurrTab'=>false]);
			}else{
				return $this->parseData(['code' => 200,'msg' => '操作成功','openUrl' => '/partner/list.html','closeCurrTab'=>false]);
			}
		}else{
			$id = $request->get('id');
			if($id <= 0){
				return $this->show('error/data_error');
			}
			$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $id]);
			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
			$adminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $partner->getAdminId()]);
			$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $partner->getAdminId(),'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
			// 获取省信息
			$cityService = $this->createService('City.CityService');
			$province_list = $cityService->getCityList('CHINA');

			// 受理营业部
			$departments = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyDept')->findBy(['dtParent' => $this->getParameter('admin_bundle')['admissible_business_department']]);

			// 商户上传的资料
			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');
//			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),array_keys($this->getParameter('admin_bundle')['file_type']));
			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);

			// 获取商品分类
			$salesCategory = $em->getRepository('AdminBundle:YsGoodsCate')->findBy(['gcNode' => 0,'gcStatus' => 1]);
			return $this->show(
				'partner/admin_edit',
				[
					'a_id'             => $partner->getAdminId(),
					'adminUser'        => $adminUser,
					'partner'          => $partner,
					'partnerInfo'      => $partnerInfo,
					'province_lists'   => $province_list,
					'adminContacts'    => $adminContacts,
					'departments'      => $departments,
					'maritalStatus'    => AdminUserConstant::$maritalStatus,
					'educateStatus'    => AdminUserConstant::$educateStatus,
					'salesCategory'    => $salesCategory,
					'partnerDaturm'    => $partnerDaturm,
				]
			);
		}
	}


	/**
	 * 商户审核（后台）
	 * @Route("/checkadmin/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function checkAdminAction(Request $request){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		if($request->getRealMethod() == 'POST'){
			$pid = $request->get('id');
			if($pid <= 0){
				return $this->parseData(['msg'=>'非法请求','code'=>500,'closeCurrTab'=>false]);
			}
			/** @var EntityManager $em */
			$em = $this->getDoctrine()->getManager();

			$partnerCheckDao = $em -> getRepository('AdminBundle:YsPartnerCheck')->findOneBy(['partnerId' => $pid]);
			if(is_null($partnerCheckDao)){
				$partnerCheckDao = new YsPartnerCheck();
				$partnerCheckDao->setCheckAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerCheckDao->setCheckUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerCheckDao->setPartnerId($pid);
			}

			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $pid]);

			$checkStatus        = $request->get('check_status');
			$checkRemark        = trim($request->get('check_remark'));
			if(empty($checkRemark)){
				return $this->parseData(['code' => 500,'msg' => '审核备注必填']);
			}
			$checkFeedback      = trim($request->get('check_feedback'));
			if(empty($checkFeedback)){
				return $this->parseData(['code' => 500,'msg' => '审核反馈必填']);
			}
			$partnerType = trim($request->get('partner_type',0));
			$isCreditBuy = trim($request->get('is_credit_buy',0));

			$checkName = $this->container->get('security.token_storage')->getToken()->getUser()->getAName();

			try{
				$em->getConnection()->beginTransaction();

				$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $pid]);

				// 后台审核记录日志
				$checkInfo = '';
				if($checkStatus == 1){
					$partner->setPartnerStatus(MerchantConstant::MERCHANT_CHECK_FAIL);
					$checkInfo .= '拒绝;';
					$reason = '很遗憾，您提交的申请审核失败。';
					if(!empty($checkFeedback)){
						$reason .= '原因是'.$checkFeedback.'。';
					}
					$this->addMessage($partner->getPartnerId(),'商户审核',$reason);
				}elseif($checkStatus == 2){
					$partner->setPartnerStatus(MerchantConstant::MERCHANT_CHECK_SUCCEED);
					$checkInfo .= '通过;';
					if(empty($partnerType)){
						return $this->parseData(['code' => 500,'msg' => '商家类型必选']);
					}
					if(empty($isCreditBuy)){
						return $this->parseData(['code' => 500,'msg' => '是否支持赊购必选']);
					}
					if($partnerType == 1 && $isCreditBuy == 1){
						return $this->parseData(['code' => 500,'msg' => '普通商家不支持赊购']);
					}
					$this->addMessage($partner->getPartnerId(),'商户审核','恭喜您，已成功通过商户审核，立即去激活店铺吧～');

				}elseif($checkStatus == 3){
					$partner->setPartnerStatus(MerchantConstant::MERCHANT_CHECK_REPULSE);
					$checkInfo .= '打回（补充资料）;';
					$reason = '很抱歉，您提交的申请已被打回';
					if(!empty($checkFeedback)){
						$reason .= ',原因是'.$checkFeedback;
					}
					$reason .= ',请补充完整后再次提交审核。';
					$this->addMessage($partner->getPartnerId(),'商户审核',$reason);
				}
				// 后台审核
				$partnerCheckDao->setCheckFeedback($checkFeedback);
				$partnerCheckDao->setCheckRemark($checkRemark);
				$partnerCheckDao->setCheckStatus($checkStatus);
				$partnerCheckDao->setCheckName($checkName);
				$partnerCheckDao->setCheckUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
				$em->persist($partnerCheckDao);

				$partner->setPartnerUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
				$em->persist($partner);
				// 设置商家类型 （信用商家，普通商家）
				$partner->setPartnerType($partnerType);
				$partner->setIsCreditBuy($isCreditBuy);
				$em->flush();

				$checkInfo .= '审核备注:'.$checkRemark.';审核反馈:'.$checkFeedback;
				$this->behavior_trace($partnerInfo->getPartnerId(), $checkInfo, 1);// 记录操作日志用.
				$em->persist($partnerInfo);
				$em->flush();
				$em->getConnection()->commit();
				return $this->parseData(['msg'=>'审核成功','openUrl'=>'/partner/list.html']);
			}catch (\Exception $ex){
				$em->getConnection()->rollBack();
				return $this->parseData(['msg'=>$ex->getMessage(),'code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			$id = $request->get('id');
			if($id <= 0){
				return $this->show('error/data_error');
			}
			$em = $this->getDoctrine()->getManager();
			$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['partnerId' => $id]);
			if($partner->getPartnerStatus() != MerchantConstant::MERCHANT_CHECK_AUDIT){
				return $this->_404();
			}
			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $id]);
			$checkInfo = $em->getRepository('AdminBundle:YsPartnerCheck')->findOneBy(['partnerId' => $id]);

			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');
//			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),array_keys($this->getParameter('admin_bundle')['file_type']));
			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);

			$adminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $partner->getAdminId()]);

			$adminUserContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $partner->getAdminId(),'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
			// 获取开户行信息
			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');
			$open_bank = $partnerService->getOpenBankList();
			// 受理营业部
			$departments = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyDept')->findBy(['dtParent' => $this->getParameter('admin_bundle')['admissible_business_department']]);

			// 办理人
			if(!empty($adminUser->getAiServiceNo()) && empty($partnerInfo->getAcceptOfficer())){
				$result = $this->getDoctrine()->getConnection('fenqi')->fetchAssoc("select a.a_name from my_channel m inner join admin_users a on m.leader_id = a.a_id where m.service_code ='{$adminUser->getAiServiceNo()}'");
				if(!empty($result)){
					$partnerInfo->setAcceptOfficer($result['a_name']);
				}
			}

			// 获取商品分类
			$salesCategory = $em->getRepository('AdminBundle:YsGoodsCate')->findBy(['gcNode' => 0,'gcStatus' => 1]);
			// 获取省信息
			$cityService = $this->createService('City.CityService');
			$province_list = $cityService->getCityList('CHINA');

			return $this->show(
				'partner/admin_check',
				[
					'partner'           => $partner,
					'adminUser'         => $adminUser,
					'partnerInfo'       => $partnerInfo,
					'checkInfo'         => $checkInfo,
					'partnerDaturm'     => $partnerDaturm,
					'province_lists'    => $province_list,
					'bank_type'         => $open_bank,
					'adminContacts'     => $adminUserContacts,
					'salesCategory'     => $salesCategory,
					'departments'       => $departments,
				]
			);
		}
	}

	/**
	 * 商户资料修改（前台）
	 * @Route("/partner_edit.html")
	 * @Method("Get")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function partnerEditAction(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id

		$em = $this->getDoctrine()->getManager();
		$adminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
//		print_r($adminUser);die;
		if(is_null($adminUser) || empty($adminUser->getAIdentNo())){
			return $this->show(
				'partner/partner_unauth'
			);
		}
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		if(!is_null($partner)){
			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
		}
		$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $aid,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
		// 获取省信息
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');

		// 受理营业部
		$departments = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyDept')->findBy(['dtParent' => $this->getParameter('admin_bundle')['admissible_business_department']]);

		// 商户上传的资料
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
//			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),array_keys($this->getParameter('admin_bundle')['file_type']));
		$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);

		// 获取商品分类
		$salesCategory = $em->getRepository('AdminBundle:YsGoodsCate')->findBy(['gcNode' => 0,'gcStatus' => 1]);
		return $this->show(
			'partner/partner_check',
			[
				'a_id'             => $aid,
				'adminUser'        => $adminUser,
				'partner'          => $partner,
				'partnerInfo'      => $partnerInfo,
				'province_lists'   => $province_list,
				'adminContacts'    => $adminContacts,
				'departments'      => $departments,
				'maritalStatus'    => AdminUserConstant::$maritalStatus,
				'educateStatus'    => AdminUserConstant::$educateStatus,
				'salesCategory'    => $salesCategory,
				'partnerDaturm'    => $partnerDaturm,
			]
		);

	}

	/**
	 * 商户审核,填写资料（前台）
	 * @Route("/check.html")
	 * @Method("Get")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function checkAction(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id

		$em = $this->getDoctrine()->getManager();
		$adminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
//		print_r($adminUser);die;
		if(is_null($adminUser) || empty($adminUser->getAIdentNo())){
			return $this->show(
				'partner/partner_unauth'
			);
		}
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		if(!is_null($partner)){
			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);

			// 重新认证时跳到审核页面，提交后改成待审核状态
			$reset = $request->get('reset',0);
			if($reset != 1){
				$partnerCheck = $em->getRepository('AdminBundle:YsPartnerCheck')->findOneBy(['partnerId' => $partner->getPartnerId()]);
				if($partner->getPartnerStatus() == MerchantConstant::MERCHANT_CHECK_SUCCEED){
					return $this->show(
						'partner/partner_check_state',[
							'status'        => $partner->getPartnerStatus(),
							'partnerCheck'  => $partnerCheck,
							'partner'       => $partner,
						]
					);
				}elseif($partner->getPartnerStatus() == MerchantConstant::MERCHANT_CHECK_FAIL || $partner->getPartnerStatus() == MerchantConstant::MERCHANT_CHECK_REPULSE || $partner->getPartnerStatus() == MerchantConstant::MERCHANT_CHECK_AUDIT){
					return $this->show(
						'partner/partner_check_state',[
							'status'        => $partner->getPartnerStatus(),
							'partnerCheck'  => $partnerCheck,
							'partner'       => $partner,
						]
					);
				}
				/*$partner->setPartnerStatus(MerchantConstant::MERCHANT_CHECK_NOT_APPLY);
				$em->persist($partner);
				$em->flush();*/
			}
		}else{
			$partner = new YsPartners();
			$partnerInfo = new YsPartnerInfo();
		}
		$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $aid,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
		// 获取省信息
		$cityService = $this->createService('City.CityService');
		$province_list = $cityService->getCityList('CHINA');

		// 受理营业部
		$departments = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyDept')->findBy(['dtParent' => $this->getParameter('admin_bundle')['admissible_business_department']]);

		// 商户上传的资料
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
//			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),array_keys($this->getParameter('admin_bundle')['file_type']));
		$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);

		// 办理人
		if(!empty($adminUser->getAiServiceNo()) && empty($partnerInfo->getAcceptOfficer())){
			$result = $this->getDoctrine()->getConnection('fenqi')->fetchAssoc("select a.a_name from my_channel m inner join admin_users a on m.leader_id = a.a_id where m.service_code ='{$adminUser->getAiServiceNo()}'");
			if(!empty($result)){
				$partnerInfo->setAcceptOfficer($result['a_name']);
			}
		}
		// 获取商品分类
		$salesCategory = $em->getRepository('AdminBundle:YsGoodsCate')->findBy(['gcNode' => 0,'gcStatus' => 1]);
		return $this->show(
			'partner/partner_check',
			[
				'a_id'             => $aid,
				'adminUser'        => $adminUser,
				'partner'          => $partner,
				'partnerInfo'      => $partnerInfo,
				'province_lists'   => $province_list,
				'adminContacts'    => $adminContacts,
				'departments'      => $departments,
				'maritalStatus'    => AdminUserConstant::$maritalStatus,
				'educateStatus'    => AdminUserConstant::$educateStatus,
				'salesCategory'    => $salesCategory,
				'partnerDaturm'    => $partnerDaturm,
			]
		);

	}
	/**
	 * 商户认证
	 * @param EntityManager $em
	 * @param $aid
	 * @param $request
	 * @return array
	 */
	private function partnerAuthentication($em,$aid,$request){

		$adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
		$adminUser = $em->getRepository('AdminBundle:AdminUsers')->find($aid);
		if(is_null($adminUserInfo)){
			$adminUserInfo = new AdminUserInfo();
			$adminUserInfo->setAId($aid);
		}

		$verfiy_params = [];

		$truename = trim($request->get('truename'));
		if(!empty($truename)) {
			$verfiy_params['realName'] = $truename;
			$adminUserInfo->setATrueName($truename);
		}else{
			return ['msg'=>'姓名必填','code'=>500,'closeCurrTab'=>false];
		}

		$identNo = trim($request->get('ident_no'));

		if(!empty($identNo)){
			$verfiy_params['cardNo'] = $identNo;
			$adminUserInfo->setAIdentNo($identNo);
		}else{
			return ['msg'=>'身份证号必填','code'=>500,'closeCurrTab'=>false];
		}

		$user_type = $request->get('user_type',0);
		if(empty($user_type)){
			return ['msg'=>'商户类型错误','code'=>500,'closeCurrTab'=>false];
		}

		$ret = self::webService('channel', 'verify_bank', $verfiy_params);
		if($ret['status'] != 2000){
			return ['msg'=>$ret['msg'],'code'=>500,'closeCurrTab'=>false];
		}
		$em->getConnection()->beginTransaction();
		try{
			if ($user_type == 2){
				$params['bank_type'] = 1;
				$branch_name = $request->get('branch_name','');
				$adminUser->setAPartnerType(2);
				if(empty($branch_name)){
					return ['msg'=>'支行名必填','code'=>500,'closeCurrTab'=>false];
				}else{
					$params['bank_child_name'] = $branch_name;
					$adminUserInfo->setABankBranchName($branch_name);
				}
				$branch_code = $request->get('branch_code','');
				if(empty($branch_code)){
					return ['msg'=>'支行行号必填','code'=>500,'closeCurrTab'=>false];
				}else{
					$params['bank_nums'] = $branch_code;
					$adminUserInfo->setABankBranchCode($branch_code);
				}
				$em->persist($adminUser);
			}


			$em->persist($adminUserInfo);
			$em->flush();
			$em->commit();
			return ['code'=>200,'openUrl'=>'/partner/authenticate.html','closeCurrTab'=>false];

		}catch (Exception $e){
			$em->rollback();
			return ['msg'=>"保存失败",'code'=>500,'closeCurrTab'=>false];
		}
	}

	/**
	 * 商户审核资料填写
	 * @param EntityManager $em
	 * @param $aid
	 * @param $request
	 * @param bool $admin
	 * @return array
	 */
	private function submitBaseInfo($em,$aid,$request,$admin = true){

		$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $aid,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);

		$adminInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

		if(is_null($partner)){
			$partner = new YsPartners();
			$partner->setPartnerAddTime(new \DateTime(date('Y-m-d H:i:s')));
			$partner->setPartnerUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
			$partner->setAdminId($aid);

			$partnerInfo = new YsPartnerInfo();
			$partnerInfo->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
			$partnerInfo->setUpdateTime(new \DateTime(date('Y-m-d H:i:s')));

		}else{
			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
			if(is_null($partnerInfo)){
				$partnerInfo = new YsPartnerInfo();
				$partnerInfo->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerInfo->setUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
			}
		}
		// 等于1时 暂存
		$storage = $request->get('storage',0);

		/*$liveArea = trim($request->get('live_area',''));
		if(empty($liveArea) && $storage == 0){
			return $this->parseData(['code' => 500,'msg' => '居住地址必填','closeCurrTab'=>true]);
		}*/
		$province = $request->get('provinces'); // 省
		$city = $request->get('city'); // 市
		$area = $request->get('area'); // 县
		if((empty($province) || empty($city) || empty($area)) && $storage == 0) {
			return ['code' => 500,'msg' => '商家省市县必填'];
		}
		$maritalStatus = $request->get('marital_status','');
		/*if(empty($maritalStatus) && $storage == 0){
			return ['code' => 500,'msg' => '婚姻状况必选','closeCurrTab'=>true];
		}*/
		$liveAddress = trim($request->get('live_address',''));
		if(empty($liveAddress) && $storage == 0){
			return ['code' => 500,'msg' => '详细地址必填','closeCurrTab'=>true];
		}
		$homePhone = $request->get('home_phone','');
		/*if(empty($homePhone) && $storage == 0){
			return ['code' => 500,'msg' => '住宅电话必填','closeCurrTab'=>true];
		}*/
		$email = $request->get('email','');
		$degree = $request->get('degree','');
		/*if(empty($degree) && $storage == 0){
			return ['code' => 500,'msg' => '教育程度必选','closeCurrTab'=>true];
		}*/
		$togetherLivePerson = trim($request->get('together_live_person',''));
		/*if(empty($togetherLivePerson) && $storage == 0){
			return ['code' => 500,'msg' => '共同居住人必填','closeCurrTab'=>true];
		}*/
		$partnerIntention = $request->get('partner_intention',''); // 销售品类,意向
//		if(empty($partnerIntention) && $storage == 0){
//			return ['code' => 500,'msg' => '销售品类必填','closeCurrTab'=>true];
//		}
		$partnerIntention = implode($partnerIntention,',');
		$acceptDepartment = $request->get('accept_department','');// 受理营业部
		$saleManager = trim($request->get('sale_manager',''));// 营业部经理
		$acceptOfficer = trim($request->get('accept_officer','')); // 办理人员
		if(empty($acceptOfficer) && $storage == 0){
			return ['code' => 500,'msg' => '办理人员必填','closeCurrTab'=>true];
		}
		// 经营信息
		$businessAddress = $request->get('business_address');// 经营地址
		$businessDetailAddress = $request->get('business_detail_address');// 经营详细地址
		$pastAverageCreditSales = $request->get('past_average_credit_sales');// 过往平均赊销占比
		$annualSales = $request->get('annual_sales');// 年销售额
		$grossInterestRate = $request->get('gross_interest_rate');// 毛利率
		$netInterestRate = $request->get('net_interest_rate');// 净利率
		$otherManageInfo = $request->get('other_manage_info');// 其他经营信息
		$businessInfo = json_encode([
			'business_address'          => $businessAddress,
			'business_detail_address'   => $businessDetailAddress,
			'past_average_credit_sales' => $pastAverageCreditSales,
			'annual_sales'              => $annualSales,
			'gross_interest_rate'       => $grossInterestRate,
			'net_interest_rate'         => $netInterestRate,
			'other_manage_info'         => $otherManageInfo,
		]);

		// 资产信息
		$assetInfo = json_encode([
			'receivables'               => $request->get('receivables',0), // 应收款
			'stock'                     => $request->get('stock',0), // 存货
			'vehicle_information'       => $request->get('vehicle_information'), // 车辆信息
			'house_information'         => $request->get('house_information'), // 住宅信息
			'store_information'         => $request->get('store_information'), // 门店信息
			'other_asset_information'   => $request->get('other_asset_information'), // 其他资产信息
		]);

		// 负债信息
		$debtInfo = json_encode([
			'bank_loan'         => $request->get('bank_loan'), // 银行贷款
			'company_loan'      => $request->get('company_loan'), // 小贷公司借款
			'private_loan'      => $request->get('private_loan'), // 私人借款
			'other_debt_loan'   => $request->get('other_debt_loan'), // 其他负债信息
		]);

		// 商户上传证件
		$oldCertificates = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'a']);
		$certificates = $request->get('idUpload');

		if(count($certificates) < 1 && empty($certificates) && $storage == 0){
			return ['code' => 500,'msg' => '证件图片至少上传一张','closeCurrTab'=>true];
		}

		foreach ($oldCertificates as $old){
			if(in_array($old->getPdUrl(),$certificates)){
				unset($certificates[array_search($old->getPdUrl(),$certificates)]);
			}
		}

		// 商户其他资料
		$otherDatum = $request->get('otherUpload');


		$em->getConnection()->beginTransaction();

		try{

			// 插入新的联系人
			$concactName = $request->get('contact_name');
			$concactPhone = $request->get('contact_phone');
			$concactCard = $request->get('contact_card');
			$concactAddress = $request->get('contact_address');
			$concactProfession = $request->get('contact_profession');
			$concactSex = $request->get('contact_sex');
			$concactAge = $request->get('contact_age');
			$concactId = $request->get('contact_id');
			// 修改已有的联系人
			$newContacts = [];

			foreach ($adminContacts as $adminContact){
				if(!in_array($adminContact->getId(),$concactId)){
					$adminContact->setIsDel(AdminUserConstant::CONCACT_DEL_YES);
					$em->persist($adminContact);
					$em->flush();
				}else{
					$newContacts[$adminContact->getId()] = $adminContact;
				}
			}

			foreach ($concactName as $k => $v){

				if(!empty($concactId[$k])){
					$concactAdmin = $newContacts[$concactId[$k]];
				}else{
					$concactAdmin = new AdminUserContact();
					$concactAdmin->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
					$concactAdmin->setAId($aid);
				}

				if(!empty($concactCard[$k]) && !ctype_space($concactCard[$k])){
					if(!CommonFunction::isIdCard($concactCard[$k])){
						return ['msg'=>'联系人身份证号格式错误','code'=>500,'closeCurrTab'=>false];
					}else{
						$concactAdmin->setAge(CommonFunction::getAgeByIdCard($concactCard[$k]));
						$concactAdmin->setSex(CommonFunction::getSexByIdCard($concactCard[$k]));
					}
				}else{
					$concactAdmin->setAge(intval($concactAge[$k]));
					$concactAdmin->setSex(trim($concactSex[$k]));
				}

				$concactAdmin->setContactName($concactName[$k]);
				$concactAdmin->setPhone($concactPhone[$k]);
				$concactAdmin->setIdentNo(!ctype_space($concactCard[$k]) ? $concactCard[$k] : '');
				$concactAdmin->setLiveAddress($concactAddress[$k]);
				$concactAdmin->setProfession($concactProfession[$k]);
				$em->persist($concactAdmin);
				$em->flush();
			}

			// 商户信息
//				$adminInfo->setALiveAddress($liveArea);
			$adminInfo->setAMaritalStatus($maritalStatus);
			$adminInfo->setTogetherLivePerson($togetherLivePerson);
			$adminInfo->setADetailAddress($liveAddress);
			$adminInfo->setAHomePhone($homePhone);
			$adminInfo->setAiEmail($email);
			$adminInfo->setADegree($degree);
			$adminInfo->setAProvince($province);
			$adminInfo->setACity($city);
			$adminInfo->setACounty($area);

			$em->persist($adminInfo);
			$em->flush();

			// 商家信息
			$partnerInfo->setAcceptDepartment($acceptDepartment);
			$partnerInfo->setAcceptOfficer($acceptOfficer);
			$partnerInfo->setSaleManager($saleManager);
			$partnerInfo->setManageInfo($businessInfo);
			$partnerInfo->setDebtInfo($debtInfo);
			$partnerInfo->setAssetInfo($assetInfo);
			$partnerInfo->setSaleManager($saleManager);

			$partner->setPartnerIntention($partnerIntention);
			if($storage == 0){
				// 后台用户编辑不修改审核状态
				if($admin){
					$partner->setPartnerStatus(MerchantConstant::MERCHANT_CHECK_AUDIT);
				}
			}
			$em->persist($partner);
			$em->flush();

			$partnerInfo->setPartnerId($partner->getPartnerId());
			$em->persist($partnerInfo);
			$em->flush();
//echo $partner->getPartnerId();
			// 插入商户证件
			foreach ($certificates as $k => $v){
				$certificate = new YsPartnerDaturm();
				$certificate->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$certificate->setPartnerId($partner->getPartnerId());
				$certificate->setAdminId($aid);
				$certificate->setPdType('a');
				$certificate->setPdUrl($v);
				$em->persist($certificate);
				$em->flush();
			}

			// 插入商户其他资料
			foreach ($otherDatum as $k => $v){
				$certificate = new YsPartnerDaturm();
				$certificate->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$certificate->setPartnerId($partner->getPartnerId());
				$certificate->setAdminId($aid);
				$certificate->setPdType('g');
				$certificate->setPdUrl($v);
				$em->persist($certificate);
				$em->flush();
			}
			// 商户提交材料，发站内消息
			if($admin){
				$this->addMessage($partner->getPartnerId(),'商户审核','尊敬的商户，您的资料申请已提交，请等待审核结果！');
			}
			$em->getConnection()->commit();
			return ['msg'=>'操作成功','code'=>200,'closeCurrTab'=>false];
		}catch (PDOException $exception){
			$em->getConnection()->rollback();
			return ['msg'=>'操作失败','code'=>500,'closeCurrTab'=>false];
		}
	}

	/**
	 * 商户审核资料填写(含认证)
	 * @param EntityManager $em
	 * @param $aid
	 * @param $request
	 * @param bool $admin
	 * @return array
	 */
	private function submitBaseInfoAndAuth($em,$aid,$request,$admin = true){

		$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $aid,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);

		$adminInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

		$adminUser = $em->getRepository('AdminBundle:AdminUsers')->find($aid);

		if(is_null($adminInfo)){
			$adminInfo = new AdminUserInfo();
			$adminInfo->setAId($aid);
		}

		$verfiy_params = [];

		$truename = trim($request->get('truename'));
		if(!empty($truename)) {
			$verfiy_params['realName'] = $truename;
			$adminInfo->setATrueName($truename);
		}else{
			return ['msg'=>'姓名必填','code'=>500,'closeCurrTab'=>false];
		}

		$identNo = trim($request->get('ident_no'));

		if($adminInfo->getAIdentNo()){
			if($adminInfo->getAIdentNo() != $identNo){
				// 验证身份证是否存在
				$checkAdminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aIdentNo' => $identNo]);
				if(!empty($checkAdminUser)){
					return ['msg'=>'身份证已存在','code'=>500,'closeCurrTab'=>false];
				}
			}
		}else{
			// 验证身份证是否存在
			$checkAdminUser = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aIdentNo' => $identNo]);
			if(!empty($checkAdminUser)){
				return ['msg'=>'身份证已存在','code'=>500,'closeCurrTab'=>false];
			}
		}

		if(!empty($identNo)){
			$verfiy_params['cardNo'] = $identNo;
			$adminInfo->setAIdentNo($identNo);
		}else{
			return ['msg'=>'身份证号必填','code'=>500,'closeCurrTab'=>false];
		}

		$user_type = $request->get('user_type',0);
		if(empty($user_type)){
			return ['msg'=>'商户类型错误','code'=>500,'closeCurrTab'=>false];
		}

		$ret = self::webService('channel', 'verify_bank', $verfiy_params);
		if($ret['status'] != 2000){
			return ['msg'=>$ret['msg'],'code'=>500,'closeCurrTab'=>false];
		}

		if(is_null($partner)){
			$partner = new YsPartners();
			$partner->setPartnerAddTime(new \DateTime(date('Y-m-d H:i:s')));
			$partner->setPartnerUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
			$partner->setAdminId($aid);

			$partnerInfo = new YsPartnerInfo();
			$partnerInfo->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
			$partnerInfo->setUpdateTime(new \DateTime(date('Y-m-d H:i:s')));

		}else{
			$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
			if(is_null($partnerInfo)){
				$partnerInfo = new YsPartnerInfo();
				$partnerInfo->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
				$partnerInfo->setUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
			}
		}
		// 等于1时 暂存
		$storage = $request->get('storage',0);

		/*$liveArea = trim($request->get('live_area',''));
		if(empty($liveArea) && $storage == 0){
			return $this->parseData(['code' => 500,'msg' => '居住地址必填','closeCurrTab'=>true]);
		}*/
		$province = $request->get('provinces'); // 省
		$city = $request->get('city'); // 市
		$area = $request->get('area'); // 县
		if((empty($province) || empty($city) || empty($area)) && $storage == 0) {
			return ['code' => 500,'msg' => '商家省市县必填'];
		}
		$maritalStatus = $request->get('marital_status','');
		/*if(empty($maritalStatus) && $storage == 0){
			return ['code' => 500,'msg' => '婚姻状况必选','closeCurrTab'=>true];
		}*/
		$liveAddress = trim($request->get('live_address',''));
		if(empty($liveAddress) && $storage == 0){
			return ['code' => 500,'msg' => '详细地址必填','closeCurrTab'=>true];
		}

		$tradeName = trim($request->get('trade_name',''));
		if(empty($tradeName) && $storage == 0){
			return ['code' => 500,'msg' => '行业名称必选','closeCurrTab'=>true];
		}

		$tradeRate = trim($request->get('trade_rate',''));
		if(empty($tradeRate) && $storage == 0){
			return ['code' => 500,'msg' => '行业佣金费率必填','closeCurrTab'=>true];
		}

		$homePhone = $request->get('home_phone','');
		/*if(empty($homePhone) && $storage == 0){
			return ['code' => 500,'msg' => '住宅电话必填','closeCurrTab'=>true];
		}*/
		$email = $request->get('email','');
		$degree = $request->get('degree','');
		/*if(empty($degree) && $storage == 0){
			return ['code' => 500,'msg' => '教育程度必选','closeCurrTab'=>true];
		}*/
		$togetherLivePerson = trim($request->get('together_live_person',''));
		/*if(empty($togetherLivePerson) && $storage == 0){
			return ['code' => 500,'msg' => '共同居住人必填','closeCurrTab'=>true];
		}*/
		$partnerIntention = $request->get('partner_intention',''); // 销售品类,意向
//		if(empty($partnerIntention) && $storage == 0){
//			return ['code' => 500,'msg' => '销售品类必填','closeCurrTab'=>true];
//		}
		$partnerIntention = implode($partnerIntention,',');
		$acceptDepartment = $request->get('accept_department','');// 受理营业部
		$saleManager = trim($request->get('sale_manager',''));// 营业部经理
//		$acceptOfficer = trim($request->get('accept_officer','')); // 手机端由"办理人员"改成"服务编码必填  最后改成邀请码，注册时必填，认证时只显示"
		/*if(empty($acceptOfficer) && $storage == 0){
			return ['code' => 500,'msg' => '服务编码必填','closeCurrTab'=>true];
		}else{
			$channel = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyChannel')->findOneBy(['serviceCode' => $acceptOfficer,'status' => 1]);
			if(empty($channel)){
				return ['msg'=>'该服务编码无效','code'=>500,'closeCurrTab'=>false];
			}
		}*/

		// 经营信息
		$businessAddress = $request->get('business_address');// 经营地址
		$businessDetailAddress = $request->get('business_detail_address');// 经营详细地址
		$pastAverageCreditSales = $request->get('past_average_credit_sales');// 过往平均赊销占比
		$annualSales = $request->get('annual_sales');// 年销售额
		$grossInterestRate = $request->get('gross_interest_rate');// 毛利率
		$netInterestRate = $request->get('net_interest_rate');// 净利率
		$otherManageInfo = $request->get('other_manage_info');// 其他经营信息
		$businessInfo = json_encode([
			'business_address'          => $businessAddress,
			'business_detail_address'   => $businessDetailAddress,
			'past_average_credit_sales' => $pastAverageCreditSales,
			'annual_sales'              => $annualSales,
			'gross_interest_rate'       => $grossInterestRate,
			'net_interest_rate'         => $netInterestRate,
			'other_manage_info'         => $otherManageInfo,
		]);

		// 资产信息
		$assetInfo = json_encode([
			'receivables'               => $request->get('receivables',0), // 应收款
			'stock'                     => $request->get('stock',0), // 存货
			'vehicle_information'       => $request->get('vehicle_information'), // 车辆信息
			'house_information'         => $request->get('house_information'), // 住宅信息
			'store_information'         => $request->get('store_information'), // 门店信息
			'other_asset_information'   => $request->get('other_asset_information'), // 其他资产信息
		]);

		// 负债信息
		$debtInfo = json_encode([
			'bank_loan'         => $request->get('bank_loan'), // 银行贷款
			'company_loan'      => $request->get('company_loan'), // 小贷公司借款
			'private_loan'      => $request->get('private_loan'), // 私人借款
			'other_debt_loan'   => $request->get('other_debt_loan'), // 其他负债信息
		]);

		// 商户上传证件
		$oldCertificates = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'a']);
		$certificates = $request->get('idUpload');

		if(count($certificates) < 1 && empty($certificates) && $storage == 0){
			return ['code' => 500,'msg' => '证件图片至少上传一张','closeCurrTab'=>true];
		}

		foreach ($oldCertificates as $old){
			if(in_array($old->getPdUrl(),$certificates)){
				unset($certificates[array_search($old->getPdUrl(),$certificates)]);
			}
		}

		// 商户其他资料
		$otherDatum = $request->get('otherUpload');

		$oldOtherDatums = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'g']);


		foreach ($oldOtherDatums as $old){
			if(in_array($old->getPdUrl(),$otherDatum)){
				unset($otherDatum[array_search($old->getPdUrl(),$otherDatum)]);
			}
		}

		$em->getConnection()->beginTransaction();

		try{

			if ($user_type == 2){
				$params['bank_type'] = 1;
				$branch_name = $request->get('branch_name','');
				$adminUser->setAPartnerType(2);
				if(empty($branch_name)){
					return ['msg'=>'支行名必填','code'=>500,'closeCurrTab'=>false];
				}else{
					$params['bank_child_name'] = $branch_name;
					$adminInfo->setABankBranchName($branch_name);
				}
				$branch_code = $request->get('branch_code','');
				if(empty($branch_code)){
					return ['msg'=>'支行行号必填','code'=>500,'closeCurrTab'=>false];
				}else{
					$params['bank_nums'] = $branch_code;
					$adminInfo->setABankBranchCode($branch_code);
				}
				$em->persist($adminUser);
			}


			$em->persist($adminInfo);
			$em->flush();

			// 插入新的联系人
			$concactName = $request->get('contact_name');
			$concactPhone = $request->get('contact_phone');
			$concactCard = $request->get('contact_card');
			$concactAddress = $request->get('contact_address');
			$concactProfession = $request->get('contact_profession');
			$concactSex = $request->get('contact_sex');
			$concactAge = $request->get('contact_age');
			$concactId = $request->get('contact_id');
			// 修改已有的联系人
			$newContacts = [];

			foreach ($adminContacts as $adminContact){
				if(!in_array($adminContact->getId(),$concactId)){
					$adminContact->setIsDel(AdminUserConstant::CONCACT_DEL_YES);
					$em->persist($adminContact);
					$em->flush();
				}else{
					$newContacts[$adminContact->getId()] = $adminContact;
				}
			}

			foreach ($concactName as $k => $v){

				if(!empty($concactId[$k])){
					$concactAdmin = $newContacts[$concactId[$k]];
				}else{
					$concactAdmin = new AdminUserContact();
					$concactAdmin->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
					$concactAdmin->setAId($aid);
				}

				if(!empty($concactCard[$k]) && !ctype_space($concactCard[$k])){
					if(!CommonFunction::isIdCard($concactCard[$k])){
						return ['msg'=>'联系人身份证号格式错误','code'=>500,'closeCurrTab'=>false];
					}else{
						$concactAdmin->setAge(CommonFunction::getAgeByIdCard($concactCard[$k]));
						$concactAdmin->setSex(CommonFunction::getSexByIdCard($concactCard[$k]));
					}
				}else{
					$concactAdmin->setAge(intval($concactAge[$k]));
					$concactAdmin->setSex(trim($concactSex[$k]));
				}

				$concactAdmin->setContactName($concactName[$k]);
				$concactAdmin->setPhone($concactPhone[$k]);
				$concactAdmin->setIdentNo(!ctype_space($concactCard[$k]) ? $concactCard[$k] : '');
				$concactAdmin->setLiveAddress($concactAddress[$k]);
				$concactAdmin->setProfession($concactProfession[$k]);
				$em->persist($concactAdmin);
				$em->flush();
			}

			// 商户信息
//				$adminInfo->setALiveAddress($liveArea);
			$adminInfo->setAMaritalStatus($maritalStatus);
			$adminInfo->setTogetherLivePerson($togetherLivePerson);
			$adminInfo->setADetailAddress($liveAddress);
			$adminInfo->setAHomePhone($homePhone);
			$adminInfo->setAiEmail($email);
			$adminInfo->setADegree($degree);
			$adminInfo->setAProvince($province);
			$adminInfo->setACity($city);
			$adminInfo->setACounty($area);

			$em->persist($adminInfo);
			$em->flush();

			// 商家信息
			$partnerInfo->setAcceptDepartment($acceptDepartment);
//			$partnerInfo->setAcceptOfficer($acceptOfficer);
			$partnerInfo->setSaleManager($saleManager);
			$partnerInfo->setManageInfo($businessInfo);
			$partnerInfo->setDebtInfo($debtInfo);
			$partnerInfo->setAssetInfo($assetInfo);
			$partnerInfo->setSaleManager($saleManager);

			$partner->setPartnerIntention($partnerIntention);
			$partner->setTradeBrokerageRate($tradeRate);
			$partner->setTradeName($tradeName);
			if($storage == 0){
				// 后台用户编辑不修改审核状态
				if($admin){
					$partner->setPartnerStatus(MerchantConstant::MERCHANT_CHECK_AUDIT);
				}
			}
			$em->persist($partner);
			$em->flush();

			$partnerInfo->setPartnerId($partner->getPartnerId());
			$em->persist($partnerInfo);
			$em->flush();
//echo $partner->getPartnerId();
			// 插入商户证件
			foreach ($certificates as $k => $v){
				$certificate = new YsPartnerDaturm();
				$certificate->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$certificate->setPartnerId($partner->getPartnerId());
				$certificate->setAdminId($aid);
				$certificate->setPdType('a');
				$certificate->setPdUrl($v);
				$em->persist($certificate);
				$em->flush();
			}

			// 插入商户其他资料
			foreach ($otherDatum as $k => $v){
				$certificate = new YsPartnerDaturm();
				$certificate->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
				$certificate->setPartnerId($partner->getPartnerId());
				$certificate->setAdminId($aid);
				$certificate->setPdType('g');
				$certificate->setPdUrl($v);
				$em->persist($certificate);
				$em->flush();
			}
			// 商户提交材料，发站内消息
			if($admin){
				$this->addMessage($partner->getPartnerId(),'商户审核','尊敬的商户，您的资料申请已提交，请等待审核结果！');
			}
			$em->getConnection()->commit();
			return ['msg'=>'操作成功','code'=>200,'closeCurrTab'=>false];
		}catch (PDOException $exception){
			$em->getConnection()->rollback();
			return ['msg'=>'操作失败','code'=>500,'closeCurrTab'=>false];
		}
	}
	/**
	 * 商户资料提交（前台）
	 * @Route("/baseinfo.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function submitBaseInfoAction(Request $request){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		if($request->getRealMethod() == 'POST'){
			$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id

			$em = $this->getDoctrine()->getManager();

			if(MobileDetect::isWap()){
				// 商户信息填写验证 (含商户认证)
				$result = $this->submitBaseInfoAndAuth($em,$aid,$request);
				if($result['code'] != 200){
					return $this->parseData($result);
				}
			}else{
				// 商户信息填写验证
				$result = $this->submitBaseInfo($em,$aid,$request);
				if($result['code'] != 200){
					return $this->parseData($result);
				}
			}

			return $this->parseData($result);
		}else{
		    $data = [];
            $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
            $em = $this->getDoctrine()->getManager();
            $adminInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);

			// 服务编号（邀请码） 姓名
			$data['serviceOrinviteCode'] = '';
			$data['inviteCode'] = '';
			$inviteDo = $em->getRepository('AdminBundle:AdminSaleInvite')->findOneBy(['aId' => $aid]);
			if(!empty($inviteDo) && !empty($inviteDo->getParentInviteCode())){
				$data['serviceOrinviteCode'] = $inviteDo->getParentInviteCode();
				$data['inviteCode'] = $inviteDo->getAiCode();
				$adminUserInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $inviteDo->getAiParent()]);

				if(!empty($adminUserInfo)){
					$data['serviceOrinviteCode'] .= ' '.$adminUserInfo->getATrueName();

				}
			}

            if($adminInfo){
                $partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
	            $partnerDaturm = [];
	            $data['trade_name'] = '';
	            $data['trade_brokerage_rate'] = '';
	            $data['partner_status'] = '';
	            $data['partner_name'] = '';
                if(!empty($partner)){
	                $data['partner_status'] = $partner->getPartnerStatus();
	                $data['partner_name'] = $partner->getPartnerName();
	                $partnerService = $this->createService('Partner.PartnerService');
	                $partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);
	                $data['trade_name'] = $partner->getTradeName();
	                $data['trade_brokerage_rate'] = $partner->getTradeBrokerageRate();
                }
                $data['a_true_name'] = $adminInfo->getATrueName();
                $data['a_ident_no'] = $adminInfo->getAIdentNo();
                $data['a_city'] = $adminInfo->getACity();
                $data['a_province'] = $adminInfo->getAProvince();
                $data['a_county'] = $adminInfo->getACounty();
                $data['a_detail_address'] = $adminInfo->getADetailAddress();
                $data['a_county'] = $adminInfo->getACounty();
                $data['ai_service_no'] = $adminInfo->getAiServiceNo();


                $data['partner_daturms'] = $partnerDaturm;
                /** @var CityServiceImpl $cityService */
                $cityService = $this->createService('City.CityService');
                $address = '';
                $province = $cityService->getCityName($data['a_province']);
                if(!empty($province)) $address .= $province;
                $city = $cityService->getCityName($data['a_city']);
                if(!empty($city)) $address .= ' '.$city;
                $county = $cityService->getCityName($data['a_county']);
                if(!empty($county)) $address .= ' '.$county;
                $data['address'] = $address;
	            return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$data]);
            }else{
                return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$data]);
            }

        }
	}

	/**
	 * 商户类型设置（后台）
	 * @Route("/partner-type-set/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function partnerTypeSetAction(Request $request){
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
			$partnerType = $request->get('partner_type');
			// 商户类型改为普通时，不支持赊购
			if($partnerType == MerchantConstant::MERCHANT_ORDINARY){
				$partner->setIsCreditBuy(MerchantConstant::MERCHANT_CREDIT_BUY_NO);
			}
			$partner->setPartnerType($partnerType);
			$em->persist($partner);
			$em->flush();
			return $this->parseData(['msg'=>'商户类型设置成功','code'=>200,'openUrl' => '/partner/list.html','closeCurrTab'=>false]);
		}else{
			return $this->show(
				'partner/edit_partner_type',[
				'partner' => $partner
			]);
		}
	}

	/**
	 * 商户是否支持赊购设置（后台）
	 * @Route("/partner-credit-set/{id}.html",defaults={"id":0},requirements={"id":"\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function partnerCreditSetAction(Request $request){
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

			$parnterCredit = $request->get('partner_credit');
			if(empty($parnterCredit)){
				return $this->parseData(['code' => 500,'msg' => '您还未选择！']);
			}
			$partner->setIsCreditBuy($parnterCredit);
			$em->persist($partner);
			$em->flush();
			return $this->parseData(['msg'=>'设置成功','code'=>200,'openUrl' => '/partner/list.html','closeCurrTab'=>false]);
		}else{
			return $this->show(
				'partner/edit_credit',[
				'partner' => $partner
			]);
		}
	}

	/**
	 * 修改获取商户联系人（前台）
	 * @Route("/partner-link-man.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function partnerLinkMan(Request $request){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $aid,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
		if($request->getRealMethod() == 'POST'){
			// 插入新的联系人
			$concactName = $request->get('contact_name');
			$concactPhone = $request->get('contact_phone');
			$concactCard = $request->get('contact_card');
			$concactAddress = $request->get('contact_address');
			$concactProfession = $request->get('contact_profession');
			$concactSex = $request->get('contact_sex');
			$concactAge = $request->get('contact_age');
			$concactId = $request->get('contact_id');

			$concactAdmin = $em->getRepository('AdminBundle:AdminUserContact')->findOneBy(['id' => $concactId,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);

			try {

				if (is_null($concactAdmin)) {
					$concactAdmin = new AdminUserContact();
					$concactAdmin->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
					$concactAdmin->setAId($aid);
				}

				if (!empty($concactCard) && !ctype_space($concactCard)) {
					if (!CommonFunction::isIdCard($concactCard)) {
                        return $this->parseData(['msg' => '联系人身份证号格式错误', 'code' => 500, 'closeCurrTab' => false]);
					} else {
						$concactAdmin->setAge(CommonFunction::getAgeByIdCard($concactCard));
						$concactAdmin->setSex(CommonFunction::getSexByIdCard($concactCard));
					}
				} else {
					$concactAdmin->setAge(intval($concactAge));
					$concactAdmin->setSex(trim($concactSex));
				}

				$concactAdmin->setContactName($concactName);
				$concactAdmin->setPhone($concactPhone);
				$concactAdmin->setIdentNo(!ctype_space($concactCard) ? $concactCard : '');
				$concactAdmin->setLiveAddress($concactAddress);
				$concactAdmin->setProfession($concactProfession);
				$em->persist($concactAdmin);
				$em->flush();
				return $this->parseData(['msg'=>'修改成功','code'=>200,'closeCurrTab'=>false]);
			}catch (PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}

		}else{
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>self::object2array($adminContacts)]);
		}
	}

    /**
     * 删除商户联系人（前台）
     * @Route("/del-link-man.html")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delLinkMan(Request $request){
        $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
        $em = $this->getDoctrine()->getManager();

        if($request->getRealMethod() == 'POST'){
            // 插入新的联系人
            $id = $request->get('id');

            $concactAdmin = $em->getRepository('AdminBundle:AdminUserContact')->findOneBy(['id' => $id,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);

            try {

                if (is_null($concactAdmin)) {
                    return $this->parseData(['msg' => '无该联系人', 'code' => 500, 'closeCurrTab' => false]);
                }

                $em->remove($concactAdmin);
                $em->flush();
                return $this->parseData(['msg'=>'删除成功','code'=>200,'closeCurrTab'=>false]);
            }catch (PDOException $exception){
                return $this->parseData(['msg'=>'删除失败','code'=>500,'closeCurrTab'=>false]);
            }

        }else{
            return $this->parseData(['msg'=>'请求方式错误','code'=>500]);
        }
    }


    /**
     * 增加商户联系人
     * @Route("/add-partner-link-man.html")
     * @Method("Post")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addPartnerLinkMan(Request $request){
        $aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
	    /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        //$adminContacts = $em->getRepository('AdminBundle:AdminUserContact')->findBy(['aId' => $aid,'isDel' => AdminUserConstant::CONCACT_DEL_NO]);
        // 插入新的联系人
        $concactName = $request->get('contact_name');
        $concactPhone = $request->get('contact_phone');
        $concactCard = $request->get('contact_card');
        $concactAddress = $request->get('contact_address');
        $concactProfession = $request->get('contact_profession');
        $concactSex = $request->get('contact_sex');
        $concactAge = $request->get('contact_age');
        $concactId = $request->get('contact_id');
	    $em->getConnection()->beginTransaction();
        try {
            foreach ($concactName as $k => $v) {

                $concactAdmin = new AdminUserContact();
                $concactAdmin->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
                $concactAdmin->setAId($aid);


                if (!empty($concactCard[$k]) && !ctype_space($concactCard[$k])) {
                    if (!CommonFunction::isIdCard($concactCard[$k])) {
                        return $this->parseData(['msg' => '联系人'.$concactName[$k].' 身份证号格式错误', 'code' => 500, 'closeCurrTab' => false]);
                    } else {
                        $concactAdmin->setAge(CommonFunction::getAgeByIdCard($concactCard[$k]));
                        $concactAdmin->setSex(CommonFunction::getSexByIdCard($concactCard[$k]));
                    }
                } else {
                    $concactAdmin->setAge(intval($concactAge[$k]));
                    $concactAdmin->setSex(trim($concactSex[$k]));
                }

                $concactAdmin->setContactName($concactName[$k]);
                $concactAdmin->setPhone($concactPhone[$k]);
                $concactAdmin->setIdentNo(!ctype_space($concactCard[$k]) ? $concactCard[$k] : '');
                $concactAdmin->setLiveAddress($concactAddress[$k]);
                $concactAdmin->setProfession($concactProfession[$k]);
                $em->persist($concactAdmin);
                $em->flush();
            }
	        $em->getConnection()->commit();
            return $this->parseData(['msg'=>'添加成功','code'=>200,'closeCurrTab'=>false]);
        }catch (PDOException $exception){
            return $this->parseData(['msg'=>'添加失败','code'=>500,'closeCurrTab'=>false]);
        }
    }

	/**
	 * 修改获取商户经营信息
	 * @Route("/partner-manage-info.html")
	 * @param Request $request
	 */
	public function partnerManage(Request $request){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
		if($request->getRealMethod() == 'POST'){
			// 经营信息
			$businessAddress = $request->get('business_address');// 经营地址
			$businessDetailAddress = $request->get('business_detail_address');// 经营详细地址
			$pastAverageCreditSales = $request->get('past_average_credit_sales');// 过往平均赊销占比
			$annualSales = $request->get('annual_sales');// 年销售额
			$grossInterestRate = $request->get('gross_interest_rate');// 毛利率
			$netInterestRate = $request->get('net_interest_rate');// 净利率
			$otherManageInfo = $request->get('other_manage_info');// 其他经营信息
			$businessInfo = json_encode([
				'business_address'          => $businessAddress,
				'business_detail_address'   => $businessDetailAddress,
				'past_average_credit_sales' => $pastAverageCreditSales,
				'annual_sales'              => $annualSales,
				'gross_interest_rate'       => $grossInterestRate,
				'net_interest_rate'         => $netInterestRate,
				'other_manage_info'         => $otherManageInfo,
			]);
			try{
				$partnerInfo->setManageInfo($businessInfo);
				$em->persist($partnerInfo);
				$em->flush();
				return $this->parseData(['msg'=>'修改成功','code'=>200,'closeCurrTab'=>false]);
			}catch (PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			$str = $partnerInfo->getManageInfo();
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>json_decode($str,true),'tradeName' => $partner->getTradeName()]);
		}
	}

	/**
	 * 修改获取商户资产信息
	 * @Route("/partner-asset-info.html")
	 * @param Request $request
	 */
	public function partnerAssetInfo(Request $request){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
		if($request->getRealMethod() == 'POST'){
			// 资产信息
			$assetInfo = json_encode([
				'receivables'               => $request->get('receivables',0), // 应收款
				'stock'                     => $request->get('stock',0), // 存货
				'vehicle_information'       => $request->get('vehicle_information'), // 车辆信息
				'house_information'         => $request->get('house_information'), // 住宅信息
				'store_information'         => $request->get('store_information'), // 门店信息
				'other_asset_information'   => $request->get('other_asset_information'), // 其他资产信息
			]);
			try{
				$partnerInfo->setAssetInfo($assetInfo);
				$em->persist($partnerInfo);
				$em->flush();
				return $this->parseData(['msg'=>'修改成功','code'=>200,'closeCurrTab'=>false]);
			}catch (PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			$str = $partnerInfo->getAssetInfo();
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>json_decode($str,true)]);
		}
	}

	/**
	 * 修改获取受理人信息
	 * @Route("/partner-accept-info.html")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function partnerAcceptInfo(Request $request){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
		if($request->getRealMethod() == 'POST'){
			$acceptDepartment = $request->get('accept_department','');// 受理营业部
			$saleManager = trim($request->get('sale_manager',''));// 营业部经理
			$acceptOfficer = trim($request->get('accept_officer','')); // 办理人员 -> 邀请码
			/*if(empty($acceptOfficer)){
				return $this->parseData(['code' => 500,'msg' => '办理人员必填','closeCurrTab'=>true]);
			}*/

			try{
				$partnerInfo->setAcceptDepartment($acceptDepartment);
				$partnerInfo->setAcceptOfficer($acceptOfficer);
				$partnerInfo->setSaleManager($saleManager);
				$em->persist($partnerInfo);
				$em->flush();
				return $this->parseData(['msg'=>'修改成功','code'=>200,'closeCurrTab'=>false]);
			}catch (PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			// 受理营业部
			$departments = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyDept')->findBy(['dtParent' => $this->getParameter('admin_bundle')['admissible_business_department']]);
			$departments_options = [];
			foreach($departments as $item){
                $departments_options[] = [
                    'text' => $item->getDtName(),
                    'key' => $item->getDtId()
                ];
            }

			$result = [
				'accept_department' => $partnerInfo->getAcceptDepartment(),
				'sale_manager' => $partnerInfo->getSaleManager(),
				'accept_officer' => $partnerInfo->getAcceptOfficer(),
				'departments' => $departments_options
			];
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$result]);
		}
	}

	/**
	 * 修改获取商户负债信息
	 * @Route("/partner-debt-info.html")
	 * @param Request $request
	 */
	public function partnerDebtInfo(Request $request){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);
		$partnerInfo = $em->getRepository('AdminBundle:YsPartnerInfo')->findOneBy(['partnerId' => $partner->getPartnerId()]);
		if($request->getRealMethod() == 'POST'){
			// 负债信息
			$debtInfo = json_encode([
				'bank_loan'         => $request->get('bank_loan'), // 银行贷款
				'company_loan'      => $request->get('company_loan'), // 小贷公司借款
				'private_loan'      => $request->get('private_loan'), // 私人借款
				'other_debt_loan'   => $request->get('other_debt_loan'), // 其他负债信息
			]);
			try{
				$partnerInfo->setDebtInfo($debtInfo);
				$em->persist($partnerInfo);
				$em->flush();
				return $this->parseData(['msg'=>'修改成功','code'=>200,'closeCurrTab'=>false]);
			}catch (PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			$str = $partnerInfo->getDebtInfo();
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>json_decode($str,true)]);
		}
	}
	/**
	 * 修改获取商户基本信息
	 * @Route("/partner-base-info.html")
	 * @param Request $request
	 */
	public function partnerBaseInfo(Request $request){
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();
		$adminInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $aid]);
		if($request->getRealMethod() == 'POST'){
			// 商户信息

			$province = $request->get('provinces'); // 省
			$city = $request->get('city'); // 市
			$area = $request->get('area'); // 县
			if((empty($province) || empty($city) || empty($area))) {
                return $this->parseData(['code' => 500,'msg' => '商家省市县必填']);
			}
			$maritalStatus = $request->get('marital_status','');
			/*if(empty($maritalStatus) && $storage == 0){
				return ['code' => 500,'msg' => '婚姻状况必选','closeCurrTab'=>true];
			}*/
			$liveAddress = trim($request->get('live_address',''));
			if(empty($liveAddress)){
                return $this->parseData(['code' => 500,'msg' => '详细地址必填','closeCurrTab'=>true]);
			}
			$homePhone = $request->get('home_phone','');
			/*if(empty($homePhone) && $storage == 0){
				return ['code' => 500,'msg' => '住宅电话必填','closeCurrTab'=>true];
			}*/
			$email = $request->get('email','');
			$degree = $request->get('degree','');
			/*if(empty($degree) && $storage == 0){
				return ['code' => 500,'msg' => '教育程度必选','closeCurrTab'=>true];
			}*/
			$togetherLivePerson = trim($request->get('together_live_person',''));
//				$adminInfo->setALiveAddress($liveArea);

			try{
				$adminInfo->setAMaritalStatus($maritalStatus);
				$adminInfo->setTogetherLivePerson($togetherLivePerson);
				$adminInfo->setADetailAddress($liveAddress);
				$adminInfo->setAHomePhone($homePhone);
				$adminInfo->setAiEmail($email);
				$adminInfo->setADegree($degree);
				$adminInfo->setAProvince($province);
				$adminInfo->setACity($city);
				$adminInfo->setACounty($area);
				$em->persist($adminInfo);
				$em->flush();
				return $this->parseData(['msg'=>'修改成功','code'=>200,'closeCurrTab'=>false]);
			}catch (PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			$adminInfo = self::object2array($adminInfo);
			$adminInfo['degree'] = $this->getParameter('admin_bundle')['edu'][$adminInfo['aDegree']];
			$adminInfo['maritalStatus'] = $this->getParameter('admin_bundle')['marryStatus'][$adminInfo['aMaritalStatus']];
			/** @var CityServiceImpl $cityService */
			$cityService = $this->createService('City.CityService');
			$address = '';
			$province = $cityService->getCityName($adminInfo['aProvince']);
			if(!empty($province)) $address .= $province;
			$city = $cityService->getCityName($adminInfo['aCity']);
			if(!empty($city)) $address .= ' '.$city;
			$county = $cityService->getCityName($adminInfo['aCounty']);
			if(!empty($county)) $address .= ' '.$county;
			$adminInfo['address'] = $address;
			$adminInfo['edu'] = $this->getParameter('admin_bundle')['edu'];
			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>$adminInfo]);
		}
	}
	/**
	 * 修改获取商户资料信息
	 * @Route("/partner-daturm-info.html")
	 * @param Request $request
	 */
	public function partnerDaturmInfo(Request $request)
	{
		$aid = $this->container->get('security.token_storage')->getToken()->getUser()->getAId();// 商户用户id
		$em = $this->getDoctrine()->getManager();

		$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $aid]);

		if($request->getRealMethod() == 'POST'){
			try{
				// 商户上传证件
//				$oldCertificates = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'a']);
//				$certificates = $request->get('idUpload');
                $otherDatums = $request->get('otherUpload');

//                if(is_array($certificates) && count($certificates) > 3){
//                    return $this->parseData(['code' => 500,'msg' => '证件图片不能超过3张','closeCurrTab'=>true]);
//                }

                if(is_array($otherDatums) && count($otherDatums) > 3){
                    return $this->parseData(['code' => 500,'msg' => '其他图片不能超过3张','closeCurrTab'=>true]);
                }


//				if(count($certificates) < 1 && empty($certificates)){
//					return $this->parseData(['code' => 500,'msg' => '证件图片至少上传一张','closeCurrTab'=>true]);
//				}
//
//				$delImages = [];
//
//				foreach ($oldCertificates as $old){
//					if(in_array($old->getPdUrl(),$certificates)){
//						unset($certificates[array_search($old->getPdUrl(),$certificates)]);
//					}else{
//                        $delImages[] = $old;
//                    }
//				}
//				// 插入商户证件
//				foreach ($certificates as $k => $v){
//					$certificate = new YsPartnerDaturm();
//					$certificate->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
//					$certificate->setPartnerId($partner->getPartnerId());
//					$certificate->setAdminId($aid);
//					$certificate->setPdType('a');
//					$certificate->setPdUrl($v);
//					$em->persist($certificate);
//					$em->flush();
//				}
//
//				foreach ($delImages as $item){
//				    $em->remove($item);
//                    $em->flush();
//                }

                $delImages = [];

				// 商户其他资料
				$oldOtherDatums = $em->getRepository('AdminBundle:YsPartnerDaturm')->findBy(['partnerId' => $partner->getPartnerId(),'pdType' => 'g']);

				foreach ($oldOtherDatums as $old){
					if(in_array($old->getPdUrl(),$otherDatums)){
						unset($otherDatums[array_search($old->getPdUrl(),$otherDatums)]);
					}else{
                        $delImages[] = $old;
                    }
				}
				// 插入商户其他资料
				foreach ($otherDatums as $k => $v){
					$certificate = new YsPartnerDaturm();
					$certificate->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
					$certificate->setPartnerId($partner->getPartnerId());
					$certificate->setAdminId($aid);
					$certificate->setPdType('g');
					$certificate->setPdUrl($v);
					$em->persist($certificate);
					$em->flush();
				}
                foreach ($delImages as $item){
                    $em->remove($item);
                    $em->flush();
                }
				return $this->parseData(['msg'=>'修改成功','code'=>200]);
			}catch(PDOException $exception){
				return $this->parseData(['msg'=>'修改失败','code'=>500,'closeCurrTab'=>false]);
			}
		}else{
			// 商户上传的资料
			/** @var PartnerServiceImpl $partnerService */
			$partnerService = $this->createService('Partner.PartnerService');

			$partnerDaturm = $partnerService->getDaturms($partner->getPartnerId(),['a','g']);

			return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>self::object2array($partnerDaturm)]);
		}

	}

	/**
	 * @Route("/trade-info.html")
	 */
	public function getTradeInfo(){
		/** @var PartnerServiceImpl $partnerService */
		$partnerService = $this->createService('Partner.PartnerService');
		$tradeInfo = $partnerService->getTradeInfo();
		return $this->parseData(['msg'=>'获取成功','code'=>200,'data'=>self::object2array($tradeInfo)]);
	}
}
