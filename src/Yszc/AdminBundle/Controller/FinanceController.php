<?php

namespace App\Yszc\AdminBundle\Controller;

use Proxies\__CG__\AdminBundle\Entity\YsOrderSettlement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Yszc\AdminBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\Request;

class FinanceController extends CommonController{

	/**
	 * 商户申请提现列表
	 * @Route("/partner/cash_list.html")
	 */
	public function applyCash(Request $request){
		//可结算金额
		$conditions = $this->ajaxRequest();
		$conditions['partner_id'] = intval($request->getSession()->get('partner_id'));
		$settlement_info = $this->getFinanceService()->getSettlementOrderInfo($conditions['partner_id']);
		//提现记录
		$conditions['u_type'] = $this->getUser()->getAType();
		$conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
		$settlementOrderList = $this->getFinanceService()->getSettlementOrder($conditions);
		$page = $this->get('page_service');
		$page->is_page_nums = 2;
		$page->setPage($settlementOrderList['count'], $request->get('p', '1'), true, 'settlementOrderList');//设置为ajax链接

		return $this->show('finance/transaction_record_list',['params' => $conditions,'tabid'=>'settlementOrderList','settlement_info'=>$settlement_info,'settlementOrderList'=>$settlementOrderList['data'],'page' => $page->show()]);
	}

	/**
	 * 商户申请提现列表
	 * @Route("/partner/cash.html")
	 * @Method("POST")
	 */
	public function cash(Request $request){
		$cash_amt = round($request->get('cash_amt'),2);
		if($cash_amt<1000 || $cash_amt>10000){
			return  $this->parseData(['code'=>500,'msg'=>'提现金额有误，请重新输入！']);
		}else{
			$partner_id = intval($request->getSession()->get('partner_id'));

			$settlement_order = $this->getDoctrine()->getRepository('AdminBundle:YsOrderSettlement')->findOneBy(['partnerId'=>$partner_id,'osStatus'=>0]);
			if($settlement_order){
				return  $this->parseData(['code'=>500,'msg'=>'尚有未处理完的申请！']);
			}
			$settlement_info = $this->getFinanceService()->getSettlementOrderInfo($partner_id);
			if($settlement_info['surplus_settlement_amt']<$cash_amt){
				return  $this->parseData(['code'=>500,'msg'=>'提现金额有误，请重新输入！']);
			}
			$order_ids = $this->getFinanceService()->getOrderIdsByCash($partner_id,$cash_amt);
			if(empty($order_ids)){
				return  $this->parseData(['code'=>500,'msg'=>'没有要处理的订单！']);
			}

			$em = $this->getDoctrine()->getManager();
			$orderSettlement = new YsOrderSettlement();
			$orderSettlement->setOrderId(implode(',',$order_ids));
			$orderSettlement->setOsAmount($cash_amt);
			$orderSettlement->setPartnerId($partner_id);
			$orderSettlement->setOsStatus(0);
			$orderSettlement->setOsChannel(NULL);
			$orderSettlement->setOsApplyTime(new \DateTime(date('Y-m-d H:i:s')));
			$em->persist($orderSettlement);
			$em->flush();
			return  $this->parseData(['openUrl'=>'/partner/cash_list.html','msg'=>'提交成功，等待处理！']);
		}

	}

	/**
	 * 取消提现申请
	 * @Route("/partner/cancle_apply.html")
	 * @Method("POST")
	 */
	public function cancleApply(Request $request){
		$os_id = intval($request->get('os_id'));
		$partner_id = intval($request->getSession()->get('partner_id'));
		$settlement_order = $this->getDoctrine()->getRepository('AdminBundle:YsOrderSettlement')->findOneBy(['osId'=>$os_id,'partnerId'=>$partner_id,'osStatus'=>0]);
		if(empty($settlement_order)){
			return  $this->parseData(['code'=>500,'msg'=>'操作数据不存在或不允许撤销！']);
		}else{
			$em = $this->getDoctrine()->getManager();
			$settlement_order->setOsStatus(3);
			$em->persist($settlement_order);
			$em->flush();
			return  $this->parseData(['openUrl'=>'/partner/cash_list.html','msg'=>'撤销成功，提现申请已取消！']);
		}

	}

	private function getFinanceService(){
		return $this->createService('Finance.FinanceService');
	}

}
