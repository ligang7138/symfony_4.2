<?php
/**
 * Created by GroupTour.
 * User: ydmx_lei
 * Time: 2016/12/22 18:23
 */

namespace App\Yszc\AdminBundle\Security;
use App\Yszc\AdminBundle\Common\MobileDetect;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

use App\Yszc\AdminBundle\Entity\AdminUsers;
use App\Yszc\AdminBundle\Security\WebserviceUser;

class AuthenticationHandler implements UserProviderInterface
{
    /**
     * @var  \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    private $service;
    private static $config;
    public function __construct($request,$service,$tour_bundle) {
        $this->request = $request;
        $this->service = $service;
        self::$config = $tour_bundle;
    }

    public function loadUserByUsername($username)
    {
        $request = $this->request->getCurrentRequest();
        $session = $request->getSession();

        if(empty($request->get('_username'))){
            throw new BadCredentialsException('请输入正确的用户名！');
        }
        if(empty($request->get('_password'))){
            throw new BadCredentialsException('密码不能为空！');
        }

        if($request->request->has('check_code')){
            if($request->get('check_code') != $session->get('login_code')){
                throw new BadCredentialsException('验证码错误！');
            }
        }
        $admin = $this->service->getRepository('AdminBundle:AdminUsers');
        /** @var AdminUsers $user */
        $user = $admin->findOneBy(['aName'=>$username]);
        if(empty($user)){
            throw new BadCredentialsException('用户名不存在！');
        }

        // 商户登录时（手机端），验证商户类型
	    if(0==$user->getAStatus()){
		    throw new BadCredentialsException('用户未激活！');
	    }

	    if(0 != $user->getAType()){
		    throw new BadCredentialsException('用户类型错误！');
	    }

	    $partner = $this->service->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId'=>$user->getAId()]);
	    $partner_id = null;
	    if($partner){
		    $partner_id = $partner->getPartnerId();
	    }
	    $request->getSession()->set('partner_id',$partner_id);
        $session->set('login_code', NULL);
        $timeOut = '+'.self::$config['timeOut'];
        setcookie('my_login', json_encode(array('code'=>200,'time'=>strtotime(date('Y-m-d H:i:s') . "+$timeOut hours"))), strtotime(date('Y-m-d H:i:s') . "+($timeOut+0.1) hours"),'/');
        if($user->getAType() == 0){
	        $this->pullMsg($user);
        }
        return $user;
    }

	/**
	 * 登录系统时将未读消息写入ys_user_message表
	 * @param AdminUsers $user
	 */
	protected function pullMsg(AdminUsers $user){

		$conn = $this->service->getConnection();
		$aid = $user->getAId();
		$last_pull_data = $conn->fetchAssoc("select max(create_time) as last_time from ys_user_message where u_code = {$aid} order by create_time desc");
		if(isset($last_pull_data['last_time']) && !empty($last_pull_data['last_time'])){
			$last_time = $last_pull_data['last_time'];

		}else{
			$last_time = $user->getAAddTime()->format('Y-m-d H:i:s');
		}

		$msgIdArr = $conn->fetchAll("select msg_id from ys_user_message where u_code = {$aid}");
		$msgIdStr = implode(array_column($msgIdArr,'msg_id'),',');
		if(empty($msgIdStr)){
			$sql = "select * from ys_message where (admin_id = {$aid} or (msg_sys_type in (1,3) and msg_send_type = 2 and msg_send_status = 2)) and msg_add_time >= '{$last_time}' ";

		}else{
			$sql = "select * from ys_message where (admin_id = {$aid} or (msg_sys_type in (1,3) and msg_send_type = 2 and msg_send_status = 2)) and msg_add_time >= '{$last_time}' and msg_id not in($msgIdStr)";
		}
		$data = $conn->fetchAll($sql);
		if(!empty($data)){
			$insertSql = 'insert into ys_user_message(u_code,msg_id,read_status,create_time) values ';
			foreach ($data as $v){
				$insertSql .= "({$aid},{$v['msg_id']},0,'".date('Y-m-d H:i:s').'\'),';
			}
			$conn->exec(trim($insertSql,','));
		}

	}

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return AdminUsers::class === $class;
        //return $class === 'AdminBundle\Entity\AdminUsers';
    }
}