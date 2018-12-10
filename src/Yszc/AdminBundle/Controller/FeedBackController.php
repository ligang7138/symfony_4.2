<?php
namespace App\Yszc\AdminBundle\Controller;

use App\Yszc\AdminBundle\Common\CommonFunction;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/feed")
 */
class FeedBackController extends CommonController
{

	/**
	 * 反馈意见列表页
	 * @Route("/feed_list.html")
	 */
    public function feedList(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        $conditions = $this->ajaxRequest();
        $where = ' where f.fd_sys_source=1 ';
        if($conditions['f_name']){
            $where .= " and ui.ui_true_name='$conditions[f_name]'";
        }
        if($conditions['f_phone']){
            $where .= " and f.fd_user_name='$conditions[f_phone]'";
        }
        if(isset($conditions['f_status']) && $conditions['f_status'] != -1 && $conditions['f_status'] != ''){
            $where .= " and f.fd_status='$conditions[f_status]'";
        }
        $conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }
        $order = ' order by f.fd_status asc,f.fd_time asc ';
        $em = $this->get('doctrine')->getManager('fenqi');
        $sql = "SELECT f.*,ui.`ui_true_name` FROM my_feedback AS f LEFT JOIN `my_users` AS u ON u.`u_name`=f.`fd_user_name` LEFT JOIN my_user_info AS ui ON u.`u_code`=ui.`u_code` ".$where.$order.$limit;
        $feedback = $em->getConnection()->fetchAll($sql);
        $numRes = $em->getConnection()->fetchAssoc("select count(1) as num from my_feedback AS f LEFT JOIN my_users AS u ON u.u_name=f.fd_user_name LEFT JOIN my_user_info AS ui ON u.u_code=ui.u_code ".$where);
        $totalNum = $numRes['num'];
        $page = $this->get('page_service');
        $page->setPage($totalNum, $request->get('p', '1'), true, 'feedList');//设置为ajax链接
        return $this->show('feedback/feedback_list',['param'=>$conditions,'tabid'=>'feedList','feed_list'=>$feedback,'page'=>$page->show()]);
    }

    /**
     * 处理
     * @Route("/showdofeed.html")
     */
    public function showdofeed(Request $request){
        if(strtolower($request->getMethod()) == 'post'){
            $fd_id = trim($request->get('fd_id'));
            $fd_op_content = trim($request->get('fd_op_content'));
            $fd_status = trim($request->get('fd_status'));
            $fd_op_name = $this->getUser()->getAName();
            if(empty($fd_op_content)){
                return $this->parseData(['msg'=>'反馈处理说明不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            $fd_op_content = str_replace("'","",$fd_op_content);
            if($fd_status==''){
                return $this->parseData(['msg'=>'请选择反馈处理状态','code'=>500,'closeCurrTab'=>false]);
            }
            $em = $this->get('doctrine')->getManager('fenqi');
            $sql = "update my_feedback set fd_op_content='$fd_op_content',fd_status='$fd_status',fd_op_name='$fd_op_name' WHERE fd_id=".$fd_id;
            $em->getConnection()->exec($sql);
            return $this->parseData(['msg'=>'操作成功','title'=>'反馈列表','openUrl'=>'/feed/feed_list.html']);
        }else{
            $fd_id = $request->get('fdId');
            $em = $this->get('doctrine')->getManager('fenqi');
            $sql = "SELECT * FROM my_feedback WHERE fd_id=".$fd_id;
            $feed_info = $em->getConnection()->fetchAssoc($sql);
            $fd_img_arr = explode(',',$feed_info['fd_img']);
            $fd_op_name = $this->getUser()->getAName();
            return $this->show("feedback/opinion_feedback",['op_name'=>$fd_op_name,'img_arr'=>$fd_img_arr,'feed_info'=>$feed_info,'f_id'=>$fd_id]);
        }
    }

}
