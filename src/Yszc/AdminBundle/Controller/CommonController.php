<?php
namespace App\Yszc\AdminBundle\Controller;
use App\Yszc\AdminBundle\Entity\YsLogs;
use App\Yszc\AdminBundle\Entity\YsMessage;
use App\Yszc\AdminBundle\Entity\YsOrder;
use App\Yszc\AdminBundle\Entity\YsPartnerCheckLog;
use App\Yszc\AdminBundle\Entity\YsTransaction;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Yszc\AdminBundle\Common\CommonFunction;
use App\Yszc\AdminBundle\Common\MobileDetect;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class CommonController extends AbstractController
{
	private $pool = [];
	protected static $_servicesInstance = [];
	/**
	 * 渲染视图
	 * @param string $view 模板
	 * @param array $data 数据
	 * @return Response
	 */
	protected function show($view = 'index/index', $data = []){
		$curUser = $this->getUser();
		$dir = 0;
        $theme_type = $this->getThemeType();

		if ($curUser) {
			$data['user_type'] = $curUser->getAType();
			if($curUser->getAType() == 0){
				/** @var EntityManager $em */
				$em = $this->getDoctrine()->getManager();
				$partner = $em->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId' => $curUser->getAid()]);
				$message_list = $em->getConnection()->fetchAll("SELECT m.msg_id,m.admin_id,m.msg_title,m.msg_content,m.is_bounce,m.msg_add_time FROM qy_message m inner join qy_user_message um on m.msg_id = um.msg_id where msg_send_status = 2 and um.u_code = ".$curUser->getAId()."  and um.read_status = 0 order by m.msg_id desc ");
				$data['messageList'] = $message_list;
				$data['partner'] = $partner;
			}
			$dir = intval($curUser->getAType());
		}
        $request = $this->get('request_stack')->getCurrentRequest();

		if($request->headers->contains('accept','application/json')){
            $response = new JsonResponse();
            if(!isset($data['code'])){
                $data['code'] = 200;
            }
            $response->setData(self::loopObject2array($data));
        }else{
            $data['admin_bundle'] = $this->getParameter('admin_bundle');
            $data['request_type'] = $_SERVER['HTTP_X_PJAX'] ? true : false;
            $data['request_id'] = $_SERVER['HTTP_X_PJAX_CONTAINER'];

	        $header = $this->container->get('twig')->render("@AdminBundle:{$theme_type}/theme/$dir:header.html.twig", $data);
	        $body = $this->container->get('twig')->render("@AdminBundle:{$theme_type}/" . str_replace('/', ':', $view) . '.html.twig', $data);
	        $footer = $this->container->get('twig')->render("AdminBundle:{$theme_type}/theme/$dir:footer.html.twig", $data);

            $response = new Response();
            $content = $data['request_type'] ? $body : $header.$body.$footer;
            $response->setContent($content);
        }

		return $response;
	}

	public static function loopObject2array($data){
		foreach ($data as $k => $v){
			$data[$k] = self::object2array($v);
		}
		return $data;
	}
	/**
	 * 将对象转换成数据
	 * @param $obj
	 * @return array|void
	 */
	public static function object2array($obj) {

		if(!is_array($obj) && !is_object($obj)) return $obj;
		//获得类名
		$class_name = get_class($obj);
		//这个 键带有类名的
		$obj = (array)$obj;
		$obj2 = array();
		//key 中取出类名
		foreach ($obj as $k => $v) {
			if($class_name){
				$k = str_replace($class_name, "", $k);
				//unicode表达方式
				$k = str_replace("\u{0000}", "", $k);
			}
			$obj2[$k] = $v;
		}
		$obj = $obj2;


		foreach ($obj as $k => $v) {
			if (gettype($v) == 'resource') {
				return;
			}
			if (gettype($v) == 'object' || gettype($v) == 'array') {
				if($v instanceof \DateTimeInterface){
					$obj[$k] = $v->format("Y-m-d H:i:s");
				}else{
					$obj[$k] = self::object2array($v);
				}
			}
		}

		return $obj;
	}

    /**
     * 获取主题类型
     */
	protected function getThemeType(){
        $theme_type = 'pc';
        if(MobileDetect::isWap()){
            $theme_type = 'wap';
        }
        return $theme_type;
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     *
     * @final since version 3.4
     */
    /*protected function render($view, array $parameters = array(), Response $response = null)
    {
	    if(strpos($view,'AdminBundle') !== false){
            $theme_type = $this->getThemeType();
            $view = str_replace('AdminBundle:',"AdminBundle:{$theme_type}/",$view);
        }
        if ($this->container->has('templating')) {
            $content = $this->container->get('templating')->render($view, $parameters);
        } elseif ($this->container->has('twig')) {
            $content = $this->container->get('twig')->render($view, $parameters);
        } else {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available. Try running "composer require symfony/twig-bundle".');
        }
        $request = $this->get('request_stack')->getCurrentRequest();

        if($request->headers->contains('accept','application/json')){
            if (null === $response) {
                $response = new JsonResponse();
            }
            if(!isset($parameters['code'])){
                $parameters['code'] = 200;
            }
            $response->setData(self::loopObject2array($parameters));
        }else{
            if (null === $response) {
                $response = new Response();
            }

            $response->setContent($content);
        }
        return $response;
    }*/

	/**
	 * 响应数据解析
	 * @param code 状态码
	 * @param msg 消息内容
	 * @param title 打开标签所显示的菜单标题
	 * @param openUrl 执行后打开的链接
	 * @param closeCurrTab  是否关闭tab标签, 或是否刷新指定页面
	 * @param other 一些其它参数
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	protected function parseData($params)
	{
		if (is_array($params)) {
			$params['code'] = empty($params['code']) ? '200' : intval($params['code']);
			$params['msg'] = empty($params['msg']) ? '操作成功' : (('500' == $params['code']) ? '<font color="red">' . trim($params['msg']) . '</font>' : trim($params['msg']));
			$params['closeCurrTab'] = !isset($params['closeCurrTab']) ? true : $params['closeCurrTab'];
		} else {
			return false;
		}
		$params['runtime'] = time();
		$params['ip'] = $this->get('request_stack')->getCurrentRequest()->getClientIp();
		return new \Symfony\Component\HttpFoundation\JsonResponse($params,$params['code']);
	}

	/**
	 * @Route("/404.html")
	 */
	public function _404(){
		return $this->show('error/data_error',[]);
	}


	/**
	 *
	 * @param $name  服务名称
	 * @return 服务对象
	 */

	public function createService($serverName)
	{
		return $this->get('application_service')->getService($serverName);
	}

	/**
	 * esb接口服务
	 * @param string $service 服务名
	 * @param string $interface 接口
	 * @param array $data 数据
	 * @return array
	 */
	protected function webService($service, $interface, $data, $callback_type = 0, string $msg_id = '')
	{
		$params = $this->getParameter('admin_bundle');
		return CommonFunction::getEsbInterface($params['ESB'], $service, $interface, $data, $callback_type, $msg_id);
	}

	/**
	 * esb异步响应
	 * @param string $msg_id 消息ID
	 * @return string
	 */
	protected function synServiceRespone(string $msg_id = '')
	{
		$params = $this->getParameter('admin_bundle');
		return CommonFunction::getEsbInterface($params['ESB'], $msg_id);
	}

	/**
	 * 获取列表及分页标识，请求方式POST
	 * @return array
	 */
	protected function getListAndLimt()
	{
		$conditions = $this->ajaxRequest();
		$conditions['limit'] = $this->pageLimit($this->get('request_stack')->getCurrentRequest()->request->get('p', 1));
		return $conditions;
	}

	protected function ajaxRequest($param = "")
	{
		$jdata = json_decode($this->get('request_stack')->getCurrentRequest()->request->get('jdata'), true);
		return $param ? $jdata[$param] : $jdata;
	}

	protected function pageLimit($curr_page = 1, $defaultpage = '')
	{
		if (empty($defaultpage)) {
			$params = $this->getParameter('admin_bundle');
			$defaultpage = $params['pageSize'];
		}
		$startPage = ($curr_page - 1) * $defaultpage < 1 ? 0 : ($curr_page - 1) * $defaultpage;
		$endPage = $defaultpage;
		return array('start' => $startPage, 'end' => $endPage);
	}

	/**
	 * 发送手机短信
	 * @param $phone 手机号码
	 * @param $type 模板类型
	 * @param array $params 根据短信模块定义参数键值
	 * @return array;
	 */
	protected function sendSMS($phone, $type, $params = [])
	{
		return self::webService('channel', 'sms_send', ['phone' => $phone, 'type' => $type, 'params' => json_encode($params)]);
	}

	/**
	 * 通用短信通知
	 * @param string $u_code
	 * @param string $template 模板或内容,根据$flag值填写
	 * @param bool $flag 【true表示使用现有模板内容，false表示自定议模板内容】
	 * @return array|bool
	 */
	protected function notice($u_code = '', $template = '',$b_id=0, $flag = true)
	{
		if (empty($u_code) || empty($b_id)) return false;
		$user_info = $this->createService('User.UserService')->getUserInfo($u_code);
		$true_name = $user_info['ui_true_name'];
		$admin_bundle = $this->getParameter('admin_bundle');
		$contents = $true_name . $template;
		if ($flag) {
			$contents = $true_name . $admin_bundle['sms_info'][$template];
		}
		$t = empty($template) ? 5 : substr($template, 0, 1);
		self::webService('channel', 'assign_send', ['sender' => $user_info['u_push_id'], 'type' => $t, 'content' => $contents]);
		return $this->sendSMS($user_info['ui_phone'], '11', ['name' => $contents]);
	}

    /**
     * 根据申请ID查询进件操作行为记录
     * @param int $b_id 申请编号
     * @Route("/admin/check/trace_{bId}_{type}.html",defaults={"bId":0,"type":0},requirements={"bId":"\d+","type":"[\d+\|\d+]{1,}|\d+"})
     */
    public function behavior_trace_list($bId, $type)
    {
        $result = self::createService('Partner.PartnerService')->getCheckLog($bId, $type);
        return $this->show('partner/partner_check_record', ['results' => $result['data']]);
    }

    /**
     * 进件操作行为记录
     * @param type $id 编号id值
     * @param type $remark 备注
     * @param type $type 类型【类型，1商户审核记录 2商品审核记录】
     * @return int
     */
    protected function behavior_trace($id, $remark = '', $type)
    {
        $behavior_trace = new YsPartnerCheckLog();
        $em = $this->getDoctrine()->getManager();
        $behavior_trace->setPartnerId($id);
        $behavior_trace->setCheckName($this->getUser()->getAName());
        $behavior_trace->setCheckTime(new \DateTime(date('Y-m-d H:i:s')));
        $behavior_trace->setCheckInfo($remark);
        $behavior_trace->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
        $behavior_trace->setCheckType($type);
        $em->persist($behavior_trace);
        $em->flush();
        return $behavior_trace->getId();
    }

    /**
     * 添加商户站内消息
     * @param $parent_id 商户ID
     * @return boole
    **/
	protected function addMessage($parent_id,$title,$content,$type=1){
		$Partner = $this->getDoctrine()->getRepository('AdminBundle:YsPartners')->find($parent_id);
		if(empty($Partner)){
			return false;
		}
		$message = new YsMessage();
		$em = $this->getDoctrine()->getManager();
		$message->setAdminId($Partner->getAdminId());
		$message->setMsgAddTime(new \DateTime(date('Y-m-d H:i:s')));
		$message->setMsgTitle($title);
		$message->setMsgContent($content);
		$message->setMsgSendStatus(2);
		$message->setMsgSysType(3);
		$message->setMsgStatus(0);
		$message->setMsgType($type);
		$em->persist($message);
		$em->flush();
		return true;
	}

	/**
	 * 添加客户站内消息
	 * @param $u_code 用户编码
	 * @return boole
	 **/
	protected function addUserMessage($u_code,$title,$content,$type=1){
		if(empty($u_code)){
			return false;
		}
		$message = new YsMessage();
		$em = $this->getDoctrine()->getManager();
		$message->setUCode($u_code);
		$message->setMsgAddTime(new \DateTime(date('Y-m-d H:i:s')));
		$message->setMsgTitle($title);
		$message->setMsgContent($content);
		$message->setMsgStatus(0);
		$message->setMsgType($type);
		$em->persist($message);
		$em->flush();
		return true;
	}

	/**
	 * 后台的功能禁止商品用户访问
	 */
	protected function forbidPartnerAccess(){
		if($this->getUser()->getAType() == 0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 商户的功能禁止后台用户访问
	 */
	protected function forbidAdminAccess(){
		if($this->getUser()->getAType() != 0){
			return true;
		}else{
			return false;
		}
	}
	
}