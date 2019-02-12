<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-6-14
 * Time: 下午5:03
 */

namespace Bundles\PayBundle\Controller;

use Bundles\PayBundle\Services\PayService;
use Bundles\Util\Lock;
use PhpRbac\Rbac;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PayController
 * @Route("/pay")
 * @package App\PayBundle\Controller
 */
class PayController extends Controller
{

	/**
	 * @Route("/index")
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function indexAction(PayService $pay){
		$a = Lock::getLockInstance('mysql');
		$b = Lock::getLockInstance('file');
		var_dump($a);
		var_dump($b);die;

		/*echo '<pre />';
		print_r(new Rbac());die;*/

		$rbac = new Rbac();

		var_dump($rbac->check(4,1));
		// Create a Permission
//		$perm_id = $rbac->Permissions->add('delete_post', 'Can delete forum posts');

// Create a Role
//		$role_id = $rbac->Roles->add('forum_moderator', 'User can moderate forums');
		die;
		// 自动注入PayService对象
//		print_r($pay->pay());die;
		$em = $this->getDoctrine()->getManager();

		$userRepository = $em->getRepository('PayBundle:User');

//		$params = $this->getParameter('pay_name');

//		print_r($params);die;

		print_r($userRepository->getUser(61269));die;
		$number = random_int(0, 100);
		return $this->render("@Pay/pay/index.html.twig",['name' => $number]);

	}

    /**
     * @Route("/test")
     */
	public function test(){
        return $this->render("@Pay/pay/index.html.twig",['name' => 33]);
    }
}