<?php

namespace App\Yszc\AdminBundle\Controller;
use App\Yszc\AdminBundle\Common\MobileDetect;
use App\Yszc\AdminBundle\Entity\AdminSaleInvite;
use App\Yszc\AdminBundle\Entity\YsUserMessage;
use App\Yszc\AdminBundle\Services\Admin\Impl\AdminServiceImpl;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Yszc\AdminBundle\Common\CommonFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Yszc\AdminBundle\Entity\AdminUsers;
use App\Yszc\AdminBundle\Entity\AdminUserInfo;
use App\Yszc\AdminBundle\Entity\AdminRoles;
use App\Yszc\AdminBundle\Entity\AdminMenus;
use App\Yszc\AdminBundle\Entity\AdminModles;
use App\Yszc\AdminBundle\Entity\EventActions;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
/**
 * @Route("/admin")
 */
class AdminController extends CommonController
{
    private $menu_array = [];
    private $child_menu_array = [];
    const PKC_PADDING = 2;
    /**
     * 首页
     * @Route("/index.html")
     * @Method("GET")
     */
    public function indexAction(){
        $curUser = $this->getUser();
        if(!is_object($curUser)){
            return new RedirectResponse('/login');
        }
        $em = $this->getDoctrine()->getManager();
		// 同步商户服务编号
	    $userInfo = $em->getRepository('AdminBundle:AdminUserInfo')->findOneBy(['aId' => $curUser->getAId()]);

	    if (!is_null($userInfo) && !empty($userInfo->getAPhone())){
		    $partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $curUser->getAId()]);
		    if(!empty($partner)){
			    $channel = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyChannel')->findOneBy(['mobile' => $userInfo->getAPhone(),'status' => 1]);
			    if(!empty($channel)){
				    $partner->setPartnerServiceCode($channel->getServiceCode());
				    $em -> persist($partner);
				    $userInfo->setAiServiceNo($channel->getServiceCode());
				    $em -> persist($userInfo);
				    $em->flush();
			    }
		    }
	    }

        $data = [
            'cur_user'=>$curUser,
            'userInfo' => $userInfo
        ];

        if (!is_null($userInfo) ){
            $data['is_ident'] = !empty($userInfo->getAIdentNo()); //是否认证
        }else{
            $data['is_ident'] = false;
        }

        $admin_bundle = $this->getParameter('admin_bundle');
        $data['fileHost'] = $admin_bundle['fileHost']; //文件存储域名
        $data['uploadFileUrl'] = $admin_bundle['uploadFileUrl']; //表彰上传
        $data['uploadFileBase64'] = $admin_bundle['uploadFileBase64']; //base64上传
	    if($curUser->getAType == 0){
		    $bounce_msg = $em->getConnection()->fetchAll("SELECT m.msg_id,m.admin_id,m.msg_title,m.msg_content,m.is_bounce,m.msg_sys_type,m.msg_add_time,um.u_code FROM qy_message m inner join qy_user_message um on m.msg_id = um.msg_id where um.u_code = ".$curUser->getAId()."  and  um.read_status = 0 and m.is_bounce = 2 order by m.msg_id desc ");
		    $data['bounce_msg'] = $bounce_msg;
	    }
	    return $this->show('/main',$data);
    }

	/**
	 * 弹框的消息更新状态
	 * @Route("/msg_bounce.html")
	 * @param Request $request
	 * @throws \Doctrine\DBAL\DBALException
	 */
    public function msgUpdateBounceStatus(Request $request){
		$msg_id_str = trim($request->get('msg_id_str',''),',');
		$aid = $this->getUser()->getAId();
		/** @var Connection $ec */
		$ec = $this->getDoctrine()->getConnection();
		$sql = "update qy_user_message set read_status = 1 where u_code = {$aid} and msg_id in ($msg_id_str)";
		$result = $ec->exec($sql);
		if($result){
			die(json_encode(['status' => 200,'msg' => '修改成功']));
		}else{
			die(json_encode(['status' => 500,'msg' => '修改失败']));
		}
    }
    /**
     * 后台用户列表
     * @Route("/user_list.html")
     */
    public function userListAction(Request $request){
        $conditions = $this->getListAndLimt();
        $user_list = $this->getAdminService()->getAllUsers($conditions);
        $page = $this->get('page_service');
        $page->setPage($user_list['count'], $request->request->get('p',1),true,'UserList');//设置为ajax链接
        return $this->show('admin/user_list',['tabid'=>'UserList','user_list'=>$user_list['data'],'page'=>$page->show(),'params'=>$conditions]);
    }
    
    /**
     * 删除管理员
     * @Route("/del/{aId}_user.html",defaults={"aId":0},requirements={"aId":"\d+"})
     */
    public function delUserAction($aId){
        try{
            $em = $this->getDoctrine()->getManager();
            $users = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->findBy(['aParentId'=>$aId]);
            if(empty($users)){
                $user = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->find($aId);
                $em->remove($user);
                $em->flush();
                $close = true;
            }else{
                $code = 500;
                $close = false;
                $msg = '操作失败，该管理员下有子级管理员';
            }
        }catch(\Exception $e){
            $code= '500';
            $close = false;
            $msg = $e->getMessage();
        }
        return  $this->parseData(['code'=>$code,'msg'=>$msg,'openUrl'=>'/admin/user_list.html','closeCurrTab'=>$close,'title'=>'后台用户列表']);
        
    }
    
    /**
     * 添加或编辑管理员
     * @Route("/user/{aId}.html",defaults={"aId":0},requirements={"aId":"add|\d+"})
     */
    public function userFormAction(Request $request){
        if('post' == strtolower($request->getMethod())){
            $em = $this->getDoctrine()->getManager();
            $user = NULL;
            $a_name = trim($request->get('a_name'));
            $a_pwd = $request->get('a_pwd');
            $a_status = $request->get('a_status');
            $a_type = $request->get('a_type');
            if(empty($a_name)){
                return  $this->parseData(['msg'=>'用户名不能为空！','closeCurrTab'=>false]);
            }
            if(intval($request->get('a_id'))){
                $user = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->find(intval($request->get('a_id')));
            }
            
            if(empty($user)){
                $user = new AdminUsers();
            }
            
            $user->setAName ($a_name);
            if(intval($request->get('a_id'))){
                if(!empty($a_pwd)){
                    $user->setAPwd(password_hash($a_pwd, PASSWORD_DEFAULT, ['cost' =>6]));
                }
            }else{
                if(empty($a_pwd)){
                    return  $this->parseData(['msg'=>'密码不能为空！','closeCurrTab'=>false]);
                }
                $user->setAPwd(password_hash($a_pwd, PASSWORD_DEFAULT, ['cost' =>6]));
                $user->setAAddTime(new \DateTime(date('Y-m-d H:i:s')));
            }
            
            $user->setAType($a_type);
	        $user->setAStatus($a_status);
            $em->persist($user);
            $em->flush();
            return  $this->parseData(['openUrl'=>'/admin/user_list.html','title'=>'后台用户列表']);
        }else {
            $aId = $request->get('aId');
            $user = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->find($aId);
            return $this->show('admin/user_form', ['user' => $user,'a_id'=>$aId]);
        }
    }

    /**
     * 检查服务号是否存在
     * @Route("/reg/check_service_code.html")
     * @Method("POST")
     */
    public function checkServiceCode(Request $request){
        $service_code = trim($request->get('service_code',''));

        if(!empty($service_code)){
            $channel = $this->getDoctrine()->getManager('fenqi')->getRepository('AdminBundle:MyChannel')->findOneBy(['serviceCode' => $service_code,'status' => 1]);
            if(is_null($channel)){
                return  $this->parseData(['code'=>500,'msg'=>'该服务编码不存在！']);
            }else{
                return  $this->parseData(['service_name'=>$channel->getTruename().' '.$channel->getMobile() ]);
            }
        }else{
            return  $this->parseData(['code'=>500,'msg'=>'该服务编码不存在！']);
        }
    }

    /**
     * 检查服务号是否存在
     * @Route("/check/check_version.html")
     * @Method("POST")
     */
    public function checkVersion(Request $request){
        $version_no = trim($request->get('version_no',''));
        $divice_type = trim($request->get('divice_type','1'));


        if(!empty($version_no)){
            $release_info = $this->getAdminService()->lastVersion($divice_type);

            if($release_info){
                $last_version_no = $release_info['version_no'];
                $version_no = $version_no;
                if(version_compare($version_no,$last_version_no) === -1){
                    $data = [
                        'app_url' => $release_info['app_url'],
                        'is_force'=> $release_info['is_force']?true:false,
                        'info'=> $release_info['info'],
                        'version_no'=> $release_info['version_no'],
                        'filesize'=> $release_info['filesize']];
                    return  $this->parseData(['data'=>$data]);
                }else{
                    return  $this->parseData(['code'=>500,'msg'=>'没有可用版本！']);
                }
            }else{
                return  $this->parseData(['code'=>500,'msg'=>'没有可用版本！']);
            }

        }else{
            return  $this->parseData(['code'=>500,'msg'=>'版本号不能为空！']);
        }
    }

    /**
     * 商户注册
     * @Route("/reg/partner.html")
     * @Method("POST")
     */
    public function regPartner(Request $request){
        if('post' == strtolower($request->getMethod())){
            $a_name = trim($request->get('a_name'));
            $pwd = $request->get('pwd');
            $a_pwd = $request->get('a_pwd');
            $check_code = trim($request->get('check_code'));
            $service_code = trim($request->get('service_code',''));
            $service_code = strtoupper($service_code);

            if(empty($service_code)){
	            return  $this->parseData(['code'=>500,'msg'=>'邀请码必填！']);
            }else{
	            // 邀请码是否存在
	            $invite_info = $this->getDoctrine()->getRepository('AdminBundle:AdminSaleInvite')->findOneBy(['aiCode'=>$service_code]);
	            if(is_null($invite_info)){
		            return  $this->parseData(['code'=>500,'msg'=>'该邀请码不存在！']);
	            }
	            $ai_type = $invite_info->getAiType();
                $adminUserInfo = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->findOneBy(['aId'=>$invite_info->getAId()]);
                if(empty($adminUserInfo) || $adminUserInfo->getAStatus() != 1){
                    return $this->parseData(['msg'=>'该邀请码有误，请重新输入','code'=>500]);
                }

                if($ai_type == 0 ||  $ai_type == 6){
		            return $this->parseData(['msg'=>'该邀请码有误，请重新输入','code'=>500]);
	            }
            }

            if(empty($a_name)){
                return  $this->parseData(['code'=>500,'msg'=>'手机号有误！']);
            }
            $phone_preg = '/^1(([3|5|7|8][\d]{9})|(47|45)[\d]{8})$/';
            if(!preg_match($phone_preg, $a_name)){
                return $this->parseData(['code'=>500,'msg'=>'请输入正确的手机号','closeCurrTab'=>false]);
            }
            if(empty($pwd)){
                return  $this->parseData(['code'=>500,'msg'=>'密码不能为空！']);
            }
            if(strlen($pwd) < 6 || strlen($pwd) > 16){
                return $this->parseData(['msg'=>'密码长度在6-16位之间','code'=>500]);
            }

            if(trim($a_pwd) != $pwd){
                return $this->parseData(['msg'=>'两次密码不一致','code'=>500]);
            }
            $code = $request->getSession()->get('check_code_'.$a_name);
            if(empty($code) || $check_code != $code){
                return  $this->parseData(['code'=>500,'msg'=>'手机验证码有误！']);
            }
            $user = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->findOneBy(['aName'=>$a_name]);

            if($user){
                return  $this->parseData(['code'=>500,'msg'=>'商户已存在！']);
            }

            $em = $this->getDoctrine()->getManager();

            try{
                $em->getConnection()->beginTransaction();
                $user = new AdminUsers();
                $user->setAName ($a_name);
                $user->setAPwd(password_hash($a_pwd, PASSWORD_DEFAULT, ['cost' =>6]));
                $user->setAAddTime(new \DateTime(date('Y-m-d H:i:s')));
                $user->setAType(0);
                $user->setAStatus(1);
                $em->persist($user);
                $em->flush();

                $adminUserParent = $em->getRepository('AdminBundle:AdminUsers')->find($invite_info->getAId());
                $saleInviteDo = new AdminSaleInvite();
                $saleInviteDo->setAId($user->getAId());
                $saleInviteDo->setAiParent($invite_info->getAId());
                $saleInviteDo->setAiAddName($adminUserParent->getAName());
                $saleInviteDo->setAiType(0);
                $saleInviteDo->setParentInviteCode($invite_info->getAiCode());
                $saleInviteDo->setAiAddTime(new \DateTime(date('Y-m-d H:i:s')));

	            $em->persist($saleInviteDo);
	            $em->flush();
                $em->getConnection()->commit();
                return $this->parseData(['code' => 200,'msg' => '创建成功','ret' => $user->getAName()]);
            }catch (PDOException $e){
                return $this->parseData(['code' => 500,'msg' => '创建失败']);
                $em->getConnection()->rollBack();
            }

            session_destroy();
            return  $this->parseData(['msg'=>'注册成功','ret'=>$a_name]);
        }else {
            return  $this->parseData(['code'=>500,'msg'=>'注册失败']);
        }
    }
    
    /**
     * 查看或编辑用户详情
     * @Route("/user_info/{aId}.html",defaults={"aId":0},requirements={"aId":"\d+"})
     */
    public function userInfoAction(Request $request){
        if('post' == strtolower($request->getMethod())){
            $em = $this->getDoctrine()->getManager();
            $user = NULL;
            $a_id = intval($request->get('a_id'));
            $a_true_name = trim($request->get('a_true_name'));
            $a_ident_no = trim($request->get('a_ident_no'));
            $a_phone = trim($request->get('a_phone'));
            $ai_email = trim($request->get('ai_email'));
            $province_id = trim($request->get('province_id'));
            $city_id = trim($request->get('city_id'));
            $area_id = trim($request->get('area_id'));
            $dt_id = trim($request->get('dt_id'));
            $proInfo = $request->get('proInfo');
            if(empty($a_true_name)){
                return  $this->parseData(['msg'=>'真实姓名不能为空！','closeCurrTab'=>false]);
            }
            if(empty($ai_email)){
                return  $this->parseData(['msg'=>'邮箱不能为空！','closeCurrTab'=>false]);
            }
            /*if(empty($a_ident_no)){
                return  $this->parseData(['msg'=>'身份证号不能为空！','closeCurrTab'=>false]);
            }
            $ident_preg = '/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/';
            if(!preg_match($ident_preg, $a_ident_no)){
                return $this->parseData(['msg'=>'请输入正确的身份证号','closeCurrTab'=>false]);
            }*/
            if(empty($a_phone)){
                return  $this->parseData(['msg'=>'电话号码不能为空！','closeCurrTab'=>false]);
            }
            $phone_preg = '/^1(([3|5|7|8][\d]{9})|(47|45)[\d]{8})$/';
            if(!preg_match($phone_preg, $a_phone)){
                return $this->parseData(['msg'=>'请输入正确的手机号','closeCurrTab'=>false]);
            }
            if(empty($province_id) || empty($city_id) || empty($area_id)){
                return  $this->parseData(['msg'=>'营业部地址不能为空！','closeCurrTab'=>false]);
            }
            if(empty($dt_id)){
                return  $this->parseData(['msg'=>'营业部名称不能为空！','closeCurrTab'=>false]);
            }
            if($a_id){
                $user_info = $this->getDoctrine()->getRepository('AdminBundle:AdminUserInfo')->findOneBy(["aId"=>$a_id]);
            }
            if(empty($user_info)){
                $user_info = new AdminUserInfo();
            }
            $ai_service_no = $user_info->getAiServiceNo();
            if(empty($ai_service_no)){
                //手机号后6位+a_id+2位随机数
                $service_no = substr($a_phone,5).$a_id.mt_rand(10,99);
                $user_info->setAiServiceNo($service_no);
            }
            // 添加/修改 数据库
            $user_info->setAId ($a_id);
            $user_info->setATrueName ($a_true_name);
            $user_info->setAIdentNo($a_ident_no);
            $user_info->setAPhone($a_phone);
            $user_info->setAiEmail($ai_email);
            $user_info->setACityId($province_id.'-'.$city_id.'-'.$area_id);
            $user_info->setDtId($dt_id);
            $user_info->setAiType(implode(",",$proInfo));
            $em->persist($user_info);
            $em->flush();
            return  $this->parseData(['openUrl'=>'/admin/user_list.html','title'=>'后台用户列表']);
        }else {
            $aId = $request->get('aId');
            $user_info = $this->getDoctrine()->getRepository('AdminBundle:AdminUserInfo')->findOneBy(["aId"=>$aId]);
            if($user_info){
                $aiType = $user_info->getAiType();//1,9,24
            }
            $city = [];
            if($user_info){
                $a_city_id = explode('-',$user_info->getACityId());
                $city['province_id'] = $a_city_id[0];
                $city['city_id'] = $a_city_id[1];
                $city['area_id'] = $a_city_id[2];
            }
            // 获取省信息
            $cityService = $this->createService('City.CityService');
	        $province_list = $cityService->getCityList('CHINA');
	        // 获取所有项目
            $proCateInfo = $this->getAdminService()->getProCateInfo();
            $aiTypeArr = explode(',',$aiType);
            foreach ($proCateInfo as $key=>$val){
                if(in_array($val['pr_id'],$aiTypeArr)){
                    $proCateInfo[$key]['current'] = 1;
                }
            }
            //获取部门信息
            $deptInfo = $this->getAdminService()->getDeptList();
            return $this->show('admin/user_info', ['deptInfo'=>$deptInfo,'proCateInfo'=>$proCateInfo,'user_info' => $user_info,'a_id'=>$aId,'province_list'=>$province_list,'city'=>$city]);
        }
    }
    
    /**
     * 管理员修改密码
     * @Route("/user/set_pwd.html")
     */
    public function setPwdAction(Request $request){
        if('post' == strtolower($request->getMethod())){
            $em = $this->getDoctrine()->getManager();
            $a_pwd = trim($request->get('au_pwd'));
            $new_pwd = trim($request->get('new_pwd'));
            $reNew_pwd = trim($request->get('reNew_pwd'));
            $user = $this->getUser();
            $original_pwd = $user->getAPwd();
            if(!password_verify($a_pwd,$original_pwd)){
                return  $this->parseData(['code'=>500,'msg'=>'原始密码不正确！','closeCurrTab'=>false]);
            }
	        if(strlen($new_pwd) < 6 || strlen($new_pwd) > 16){
                return  $this->parseData(['code'=>500,'msg'=>'密码长度在6-16位之间！','closeCurrTab'=>false]);
            }
            if(empty($reNew_pwd)){
                return  $this->parseData(['code'=>500,'msg'=>'确认密码不能为空！','closeCurrTab'=>false]);
            }
            if($new_pwd != $reNew_pwd){
                return  $this->parseData(['code'=>500,'msg'=>'新密码两次输入不一致！','closeCurrTab'=>false]);
            }
            $user->setAPwd(password_hash($new_pwd, PASSWORD_DEFAULT, ['cost' =>6]));
            $em->persist($user);
            $em->flush();
            return  $this->parseData(['openUrl'=>'/admin/index.html','closeCurrTab'=>false]);
        }else{
            $user = $this->getUser();
            return $this->show('admin/set_pwd_form', ['user' => $user]);
        }
    }

    /**
     * 管理员忘记密码
     * @Route("/user/forget_pwd.html")
     */
    public function forgetPwd(Request $request){
        $a_name = trim($request->get('a_name'));
        $a_pwd = trim($request->get('a_pwd'));
        $re_pwd = trim($request->get('re_pwd'));
        $check_code = trim($request->get('check_code'));
        if(empty($a_name)){
            return $this->parseData(['code'=>500,'msg'=>'手机号码不能为空','code'=>500]);
        }
        $phone_preg = '/^1(([3|5|7|8][\d]{9})|(47|45)[\d]{8})$/';
        if(!preg_match($phone_preg, $a_name)){
            return $this->parseData(['code'=>500,'msg'=>'请输入正确的手机号','closeCurrTab'=>false]);
        }
        if(empty($a_pwd)){
            return $this->parseData(['code'=>500,'msg'=>'新密码不能为空','code'=>500]);
        }
        if(strlen($a_pwd) < 6 || strlen($a_pwd) > 16){
            return $this->parseData(['code'=>500,'msg'=>'密码长度在6-16位之间','code'=>500]);
        }
        if(empty($re_pwd)){
            return $this->parseData(['code'=>500,'msg'=>'确认密码不能为空','code'=>500]);
        }
        if($a_pwd != $re_pwd){
            return $this->parseData(['code'=>500,'msg'=>'新密码和确认密码不一致','code'=>500]);
        }
        if(empty($check_code)){
            return $this->parseData(['code'=>500,'msg'=>'验证码不能为空','code'=>500]);
        }
        $code = $request->getSession()->get('check_code_forget_'.$a_name);
        if(empty($code) || $check_code != $code){
            return  $this->parseData(['code'=>500,'msg'=>'手机验证码有误！']);
        }
        $admin_users = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->findOneBy(['aName'=>$a_name]);
        if(empty($admin_users)){
            return $this->parseData(['code'=>500,'msg'=>'该用户不存在，请查证后再操作！','code'=>500]);
        }
	    // 商户登录时（手机端），验证商户类型
	    if(MobileDetect::isWap()){
			if($admin_users->getAType() != 0){
				return $this->parseData(['code'=>500,'msg'=>'用户类型错误！','code'=>500]);
			}
	    }
        $em = $this->getDoctrine()->getManager();
        $admin_users->setAPwd(password_hash($a_pwd, PASSWORD_DEFAULT, ['cost' =>6]));
        $em->persist($admin_users);
        $em->flush();
        return $this->parseData(['msg'=>'操作成功','code'=>200,'openUrl'=>'/login']);
    }

	/**
	 * 消息列表
	 * @Route("/message.html")
	 */
	public function messageList(Request $request){
		$conditions = $this->getListAndLimt();
		$conditions['admin_id'] = $this->getUser()->getAId();
		$user_list = $this->getAdminService()->getMessageList($conditions);
		$page = $this->get('page_service');
		$page->setPage($user_list['count'], $request->request->get('p',1),true,'MessageList');//设置为ajax链接
		return $this->show('admin/message_list',['tabid'=>'MessageList','message_list'=>$user_list['data'],'page'=>$page->show(),'pageInfo'=>$page->getPageInfo(),'params'=>$conditions]);
	}

	/**
	 * 消息详情
	 * @Route("/message/{msgId}.html",defaults={"msgId":0},requirements={"msgId":"\d+"})
	 */
	public function messageDetail(Request $request){
		$msgId = $request->get('msgId');
		$message = $this->getDoctrine()->getRepository('AdminBundle:YsMessage')->find($msgId);
		$this->writeReadMsg($msgId);
		die($message->getMsgContent());
	}

	/**
	 * 阅读消息
	 * @Route("/read_message/{msgId}.html",defaults={"msgId":0},requirements={"msgId":"\d+"})
	 */
	public function readMessage(Request $request){
		$msgId = $request->get('msgId');
		$this->writeReadMsg($msgId);
		return  $this->parseData(['openUrl'=>'/admin/message.html','closeCurrTab'=>true]);
	}

	private function writeReadMsg($msg_id){
		$aid = $this->getUser()->getAId();
		$em = $this->getDoctrine()->getManager();
		$useMessage = $em->getRepository('AdminBundle:YsUserMessage')->findOneBy(['uCode' => $aid,'msgId' => $msg_id]);
		if($useMessage){
			$useMessage->setReadStatus(1);
			$em->persist($useMessage);
		}

		$em->flush();
	}
    /**
     * 获取路由列表
    */
    private function getRouterList(){
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
           'command' => 'debug:router',
            '--format'=>'json',
            '--show-controllers'
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);
        $ret = (array)$output;
        $router_list ='';
        foreach($ret as $key=>$result){
            $router_list = json_decode($result);
            break;
        }
        $router_array = [];
        foreach($router_list as $k=>$router){
                $router_array[$k] = $router->path;
        }
        return $router_array;
    }
    
    private function urlParse($router){
        $router_list = array_keys($this->getRouterList());
        if(in_array($router,$router_list) && '#'!=$router){
            return $this->generateUrl($router);
        }else{
            return trim($router);
        }
    }

    /**
     * 相关协议
     * type 1注册  2商品  3商户
     * @Route("/agreement/{type}.html",defaults={"type":0},requirements={"type":"\d+"})
     */
    public function agreementInfo($type){
        if($type == 1){
            return $this->render('AdminBundle:agreement:registration_service_agreement.html.twig',[]);
        }elseif ($type == 2){
            return $this->render('AdminBundle:agreement:goods_release_spec.html.twig',[]);
        }elseif ($type == 3){
            return $this->render('AdminBundle:agreement:store_service_agreement.html.twig',[]);
        }
    }

    /** @return AdminServiceImpl  */
    private function getAdminService(){
        return $this->get('application_service')->getService('Admin.AdminService');
    }

	/**
	 * 存储app数据
	 * @Route("/store_app_data.html")
	 * @param Request $request
	 */
    public function storeAppDatas(Request $request){
        $post_data = file_get_contents('php://input');

    	$data = json_decode($post_data,true);
    	if($data){
            $data['u_code'] = $this->getUser()->getAId();
            $this->getAdminService()->savePhoneMessage($data);
            return $this->parseData(['msg'=>'保存成功','code'=>200]);
        }else{
            return $this->parseData(['msg'=>'上传格式错误！','code'=>500]);
        }

    }
}
