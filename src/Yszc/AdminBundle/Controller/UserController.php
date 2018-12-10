<?php

namespace App\Yszc\AdminBundle\Controller;
use App\Yszc\AdminBundle\AdminBundle;
use App\Yszc\AdminBundle\Entity\MyBorrowApply;
use App\Yszc\AdminBundle\Entity\MyUsers;
use App\Yszc\AdminBundle\Services\Product\Impl\ProductServiceImpl;
use App\Yszc\AdminBundle\Services\Product\ProductService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Yszc\AdminBundle\Common\CommonFunction;
use App\Yszc\AdminBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use App\Yszc\AdminBundle\Entity\MyUserCreditValue;
use App\Yszc\AdminBundle\Entity\MyBlacklist;
use App\Yszc\AdminBundle\Entity\MyChannel;
use App\Yszc\AdminBundle\Entity\MyFeedback;
use Symfony\Component\Validator\Constraints\Time;

/**
 * @Route("/user")
 */
class UserController extends CommonController
{
	/**
	 * 客户列表
	 * @Route("/user_list.html")
	 */
	public function userList(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        $conditions = $this->ajaxRequest();
        $conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }
        $emA = $this->get('doctrine')->getManager('fenqi');
        $sql = "SELECT u.u_id,/*u.u_service_code,*/u.u_code,u.u_name,u.u_reg_time,u.u_status,u.u_type,u.u_source,ui.ui_ident_no,ui.ui_true_name FROM my_users AS u LEFT JOIN my_user_info AS ui ON u.`u_code`=ui.`u_code` ".$limit;
        $ret = $emA->getConnection()->fetchAll($sql);
        $numRes = $emA->getConnection()->fetchAssoc("select count(1) as num from my_users");
        $totalNum = $numRes['num'];
        $page = $this->get('page_service');
        $page->setPage($totalNum, $request->get('p', '1'), true, 'userList');//设置为ajax链接
		return $this->show('user/user_list',['tabid'=>'userList','user_list'=>$ret,'page'=>$page->show()]);
	}


	/**
	 * 查看用户详情
	 * @Route("/show_user/{uCode}.html",defaults={"uCode":0},requirements={"uCode":"U\d{6,}"})
	 */
	public function showUser($uCode){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        $emA = $this->get('doctrine')->getManager('fenqi');
        $sql = "SELECT u.u_id,/*u.u_service_code,*/u.u_code,u.u_name,u.u_reg_time,u.u_status,u.u_type,u.u_source,ui.ui_ident_no,ui.ui_true_name,ui.ui_phone FROM my_users AS u LEFT JOIN my_user_info AS ui ON u.`u_code`=ui.`u_code` WHERE u.u_code='$uCode'";
        $userInfo = $emA->getConnection()->fetchAssoc($sql);
		if(empty($userInfo)){
			return $this->parseData(['msg'=>'该用户信息不存在!','code'=>500,'openUrl'=>'/user/user_list.html']);
		}
		return $this->show('user/user_details',['tabid'=>'userDetails','userInfo'=>$userInfo]);
	}

    /**
     * 修改用户信息
     * @Route("/edit_user/{uCode}.html",defaults={"uCode":0},requirements={"uCode":"U\d{6,}"})
     */
    public function editUserInfo(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        if(strtolower($request->getMethod()) == 'post'){
            $u_code = $request->get('u_code');
            $u_status = $request->get('u_status');

            $em = $this->get('doctrine')->getManager('fenqi');
            $sql = "SELECT u_id,u_code,u_name,u_reg_time,u_status,u_type,u_source FROM my_users WHERE u_code='$u_code'";
            $userInfo = $em->getConnection()->fetchAssoc($sql);
            if(empty($userInfo)){
                return $this->parseData(['msg'=>'该用户信息不存在!','code'=>500,'openUrl'=>'/user/user_list.html']);
            }
            $upSql = "update my_users set u_status=$u_status WHERE u_code='$u_code'";
            $em->getConnection()->exec($upSql);
            return $this->parseData(['code'=>200,'msg'=>'修改成功!','openUrl'=>'/user/user_list.html','title'=>'客户列表']);
        }else{
            $uCode = $request->get('uCode');
            $emA = $this->get('doctrine')->getManager('fenqi');
            $sql = "SELECT u.u_id,/*u.u_service_code,*/u.u_code,u.u_name,u.u_reg_time,u.u_status,u.u_type,u.u_source,ui.ui_ident_no,ui.ui_true_name,ui.ui_phone FROM my_users AS u LEFT JOIN my_user_info AS ui ON u.`u_code`=ui.`u_code` WHERE u.u_code='$uCode'";
            $userInfo = $emA->getConnection()->fetchAssoc($sql);
            if(empty($userInfo)){
                return $this->parseData(['msg'=>'该用户信息不存在!','code'=>500,'openUrl'=>'/user/user_list.html']);
            }
            return $this->show('user/edit_user',['u_code'=>$uCode,'tabid'=>'editUserDetails','userInfo'=>$userInfo]);
        }
    }


	private function getUserService(){
		return $this->get('application_service')->getService('User.UserService');
	}

    private function getAdminService(){
        return $this->get('application_service')->getService('Admin.AdminService');
    }

}
