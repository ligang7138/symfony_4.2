<?php

namespace App\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Gregwar\Captcha\CaptchaBuilder;
use App\AdminBundle\Controller\CommonController;

class IndexController extends CommonController
{
    /**
     * @Route("/no_role.html")
     * @Method("GET")
     */
    public function noRoleAction(){
        return $this->show('index/no_role',[]);
    }

	/**
	 * @Route("/welcome.html")
	 * @Method("GET")
	 */
	public function welcome(){
		return $this->show('index/index',[]);
	}

	/**
	 * @Route("/admin/reg/success.html")
	 * @Method("GET")
	 */
	public function regSuccess(){
		return $this->render('AdminBundle:admin:register_success.html.twig',[]);
	}

	/**
	 * 心跳检测
	 * @Route("/admin/heart.html")
	 * @Method("POST")
	 */
	public function heart(){
		die('hello');
	}

    /**
     * @Route("/code.html",name="check_code")
     * @Method("GET")
     */
    public function getCode(){
        $login_code = (string)mt_rand(10000,99999);
        $session = $this->container->get('session');
        $session->set('login_code', $login_code);
        $builder = new CaptchaBuilder($login_code);
        $builder->build(120);

        //获取验证码的内容
        $phrase = $builder->getPhrase();
        header('Content-type: image/jpeg');
        die($builder->output());
    }

}
