<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\YsLogs;
use App\AdminBundle\Entity\YsOrder;
use http\Env\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\AdminBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/order")
 */
class OrderController extends CommonController
{
    /**
     * @Route("/list")
     */
    public function orderList(Request $request){
	    $conditions = $this->ajaxRequest();
	    $conditions['partner_id'] = intval($request->getSession()->get('partner_id'));
//	    $conditions['order_status'] = intval($request->get('order_status'));
//	    $conditions['order_pay_type'] = intval($request->get('order_pay_type'));
//	    $conditions['o_start'] = $request->get('o_start','');
//	    $conditions['o_end'] = $request->get('o_end','');
//	    $conditions['search_key'] = $request->get('search_key','');
	    $conditions['u_type'] = $this->getUser()->getAType();
	    $conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
        $admin_bundle = $this->getParameter('admin_bundle');
	    $orderList = $this->getOrderService()->getAllOrders($conditions);
	    $page = $this->get('page_service');
	    $page->is_page_nums = 2;
	    $page->setPage($orderList['count'], $request->get('p', '1'), true, 'orderList');//设置为ajax链接
        return $this->show('order/order_list',[
            'params' => $conditions,
            'tabid' => 'orderList',
            'order_status' => $admin_bundle['order_status'],
            'pay_order_status' => $admin_bundle['pay_order_status'],
            'order_pay_type' => $admin_bundle['order_pay_type'],
            'orderList' => $orderList['data'],
            'page' => $page->show(),
            'pageInfo' => $page->getPageInfo(),
        ]);
    }

	/**
	 * @Route("/transaction")
	 */
	public function transaction(Request $request){
		$conditions = $this->ajaxRequest();
		$conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
		$transactionList = $this->getOrderService()->getTransaction($conditions);
		$page = $this->get('page_service');
		$page->setPage($transactionList['count'], $request->get('p', '1'), true, 'transactionList');//设置为ajax链接
		return $this->show('order/transaction_list',['tabid' => 'transactionList', 'transactionList' => $transactionList['data'], 'page' => $page->show()]);
	}

	/**
	 * 改价
	 * @Route("/modify/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	*/
	public function orderModify(Request $request){
		$order_id = $request->get('oId');
		$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->find($order_id);
		if($order && (in_array($this->getUser()->getAType(),[1,2]) || $order->getPartnerId()==intval($request->getSession()->get('partner_id')))) {
			if ('post' == strtolower($request->getMethod())) {
				$derate_amt = $request->get('derate_amt');
				if($derate_amt>$order->getOrderAmount()){
					return $this->parseData(['msg'=>'金额输入错误，优惠金额不能大于订单额！','code'=>500]);
				}
				if ($derate_amt > 0) {
					$fact_amt = $order->getOrderAmount();//$request->get('fact_amt');
					$o_fact_amt = $derate_amt > 0 ? round($fact_amt - $derate_amt, 2) : $fact_amt;
					$em = $this->getDoctrine()->getManager();
					$order->setOrderFatPayAmount($o_fact_amt);//实际支付金额
					$em->persist($order);
					$em->flush();
					$msg = '修改订单价格:【原价:' . $fact_amt . ',改价后:' . $o_fact_amt . '】';
					$this->addMessage($order->getPartnerId(), '修改订单价格', $msg, 2);
					$this->opRemark($order_id, $order, $msg);
				}
				return $this->parseData(['openUrl' => '/order/list', 'title' => '订单列表']);
			} else {
				return $this->show('order/order_price_change', ['order' => $order]);
			}
		}else{
			return $this::_404();
			//return  $this->parseData(['code'=>500,'msg'=>'订单不存在或无权限!','openUrl'=>'/order/list','title'=>'订单列表']);
		}
	}

	/**
	 * @Route("/goods/list.html")
	 */
	public function getOrderGoodsList(Request $request){
		$order_id = $request->get('oId');
		$goods = $this->getOrderService()->getOrderGoodsList($order_id);
		return $this->show('order/order_goods_list',['orderGoodsList'=>$goods]);
	}

	/**
	 * @Route("/detail/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	 */
	public function orderDetail(Request $request){
		$order_id = $request->get('oId');
		$conditions['a_type'] = $this->getUser()->getAType();
		$conditions['order_id'] = $order_id;
		$conditions['partner_id'] = intval($request->getSession()->get('partner_id'));
		$order = $this->getOrderService()->getOrder($conditions);
		if(empty($order)){
			return $this::_404();
		}
        $order['reduction_amount'] = bcsub($order['order_amount'],$order['order_fat_pay_amount'],2);
        $order['goods_total'] = bcadd(bcsub($order['order_amount'],$order['order_delivery_fee'],2),$order['buy_up_amount'],2);
		$goods = $this->getOrderService()->getOrderGoodsList($order_id);
		$logList = $this->getDoctrine()->getRepository('App\AdminBundle:YsLogs')->findBy(['orderId'=>$order_id]);
		return  $this->show('order/order_detail',['goodsList'=>$goods,'order'=>$order,'logList'=>$logList]);
	}

	/**
	 * @Route("/transaction/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	 */
	public function transactionDetail(Request $request){
		$order_id = $request->get('oId');
		$order = $this->getOrderService()->getTransaction($order_id);
		return  $this->parseData($order);
	}

	/**
	 * 确认发货
	 * @Route("/deliver_goods/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	 */
	public function confirmDeliverGoods(Request $request){
		$order_id = $request->get('oId');
		if(in_array($this->getUser()->getAType(),[1,2])){
			$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->findOneBy(['orderId'=>$order_id]);
		}else{
			$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->findOneBy(['orderId'=>$order_id,'partnerId'=>intval($request->getSession()->get('partner_id'))]);
		}
		if($order){
			$em = $this->getDoctrine()->getManager();
			$order->setOrderStatus(5);
			$em->persist($order);
			$em->flush();
			$this->opRemark($order_id,$order,'确认发货');
			$this->addMessage($order->getPartnerId(),'确认发货','订单于'.date('Y-m-d H:i:s').'已发货',2);
			return  $this->parseData(['openUrl'=>'/order/list','title'=>'订单列表']);
		}else{
			return  $this->parseData(['msg'=>'订单不存在或无权限查看','openUrl'=>'/order/list','title'=>'订单列表']);
		}
	}

	/**
	 * 取消订单
	 * @Route("/cancle/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	 */
	public function cancleOrder(Request $request){
		$order_id = $request->get('oId');
		if(in_array($this->getUser()->getAType(),[1,2])){
			$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->findOneBy(['orderId'=>$order_id]);
		}else{
			$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->findOneBy(['orderId'=>$order_id,'partnerId'=>intval($request->getSession()->get('partner_id'))]);
		}
		if($order){
			/*$em = $this->getDoctrine()->getManager();
			$order->setOrderStatus(9);
			$em->persist($order);
			$em->flush();*/
			self::webService('api','cancel_order',['order_id'=>$order_id]);
			$this->opRemark($order_id,$order,'取消订单');
			$this->addMessage($order->getPartnerId(),'取消订单','订单于'.date('Y-m-d H:i:s').'已取消',2);
			return  $this->parseData(['openUrl'=>'/order/list','title'=>'订单列表']);
		}else{
			return  $this->parseData(['msg'=>'订单不存在或无权限查看','openUrl'=>'/order/list','title'=>'订单列表']);
		}
	}

	/**
	 * 关闭订单
	 * @Route("/close/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	 */
	public function closeOrder(Request $request){
		$order_id = $request->get('oId');
		$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->findOneBy(['orderId'=>$order_id,'partnerId'=>intval($request->getSession()->get('partner_id'))]);
		if($order){
			$em = $this->getDoctrine()->getManager();
			$order->setOrderStatus(10);
			$em->persist($order);
			$em->flush();
			$this->opRemark($order_id,$order,'关闭订单');
			$this->addMessage($order->getPartnerId(),'关闭订单','订单于'.date('Y-m-d H:i:s').'已关闭',2);
			return  $this->parseData(['openUrl'=>'/order/list','title'=>'订单列表']);
		}else{
			return  $this->parseData(['msg'=>'订单不存在或无权限查看','openUrl'=>'/order/list','title'=>'订单列表']);
		}
	}

	/**
	 * 备注订单
	 * @Route("/remark/{oId}.html",defaults={"oId":0},requirements={"oId":"\d+"})
	 */
	public function modifyReceiveGoodsAddr(Request $request){
		$order_id = $request->get('oId');
		$remark = $request->get('remark');

		$order = $this->getDoctrine()->getRepository('App\AdminBundle:YsOrder')->find($order_id);
		if($order && (in_array($this->getUser()->getAType(),[0,1,2]) || $order->getPartnerId()==intval($request->getSession()->get('partner_id')))) {

			if (strlen($remark) > 100) {
				return $this->parseData(['code' => 500, 'msg' => '备注超过最大字数限制!']);
			}

			if ('post' == strtolower($request->getMethod())) {

				$this->opRemark($order_id, $order, $remark);
				return $this->parseData(['openUrl' => '/order/list', 'title' => '订单列表']);
			} else {
				return $this->show('order/remark_order', ['order' => $order]);
			}
		}else{
			return $this::_404();
		}

	}

	//订单操作记录
	private function opRemark($order_id,$order,$remark){
		$em = $this->getDoctrine()->getManager();
		$logs = new YsLogs();
		$logs->setCurrStatus($order->getOrderStatus());
		$logs->setUpStatus($order->getOrderStatus());
		$logs->setLogAddTime(new \DateTime(date('Y-m-d H:i:s')));
		$logs->setLogAdminName($this->getUser()->getAName());
		$logs->setOrderId($order_id);
		$logs->setLogRemark($remark);
		$em->persist($logs);
		$em->flush();
		return $logs->getLogId();
	}

    private function getOrderService(){
	    return $this->createService('Order.OrderService');
    }

}
