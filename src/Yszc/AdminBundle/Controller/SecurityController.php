<?php

namespace App\Yszc\AdminBundle\Controller;

use App\Yszc\AdminBundle\Common\MobileDetect;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Yszc\AdminBundle\Controller\CommonController;
use Symfony\Component\Security\Core\Security;

class SecurityController extends CommonController
{
    /**
     *
     * @Route("/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
        $curUser = $this->getUser();
        if ($curUser) {
            return $this->redirectToRoute("admin_admin_index");
        }
        $session = $request->getSession();

        $error = '';
        $code = 200;
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }
        if($error){
            $code = 500;
            $error = $error->getMessage();
        }

        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

	    return $this->render("@Admin/pc/admin/login.html.twig", ['last_username' => $lastUsername,'code'=>$code,'error'=> $this->get('translator')->trans($error)]);
    }

	/**
	 * 发送手机验证码
	 * @Route("/check_code",name="login_check_code")
	 */
	public function getCheckCode(Request $request){
		$phone = trim($request->get('phone'));
		$type = trim($request->get('type'));
		if(strlen(trim($phone))!=11){
			return $this->parseData(['code' => 500,'msg' =>'手机号有误!']);
		}
		$user = $this->getDoctrine()->getRepository('AdminBundle:AdminUsers')->findOneBy(['aName'=>$phone]);

		if($type == 1){
        	// 注册
            if($user){
                return $this->parseData(['code' => 500,'msg' =>'手机号已注册!']);
            }
        }else if($type == 2){
			// 找回密码
	        if(!$user){
		        return $this->parseData(['code' => 500,'msg' =>'手机号未注册!']);
	        }
        }
		$code = mt_rand(100000, 999999);
		$params = ['code' => $code];
		$ret = $this->sendSMS($phone,1,$params);
		if($ret['status'] == 2000){
            if($type == 1) {
                $request->getSession()->set('check_code_'.$phone, $code);
            }elseif($type ==2){
                $request->getSession()->set('check_code_forget_'.$phone, $code);
            }
			return $this->parseData(['msg' => '发送成功!']);
		}else{
			return $this->parseData(['msg' => '发送失败!', 'code' => '500']);
		}
	}

    /**
     *
     * @Route("/dologin",name="login_check")
     */
    public function checkAction(Request $request)
    {
    }

    /**
     * @Route("/logout",name="logout")
     */
    public function logoutAction(Request $request)
    {
    }
}
