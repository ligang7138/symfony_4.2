<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Constant\MerchantConstant;
use App\AdminBundle\Services\Statis\Impl\StatisServiceImpl;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Services\Statis\StatisService;

/**
 * 统计
 * Class StatisController
 * @Route("/statis")
 * @package AdminBundle\Controller
 */
class StatisController extends CommonController{

    //支付类型
	const ONLINE_FULL_PAY   = 2; // 全款
	const ONLINE_CREDIT_PAY = 3; // 赊购

	/**
	 * 前台订单统计数据
	 * @Route("/order_partner.html")
	 */
	public function orderPartnerSelfStatis(){
		if($this->forbidAdminAccess()){
			return $this->_404();
		}
		return $this->show('statis/order_parter_self');
	}
	/**
	 * 后台订单统计数据
	 * @Route("/order.html")
	 */
	public function orderAction(){
		if($this->forbidPartnerAccess()){
			return $this->_404();
		}
		return $this->show('statis/order');
	}

    /**
     * 用户统计数据
     * @Route("/user.html")
     */
    public function userAction(){
	    if($this->forbidPartnerAccess()){
		    return $this->_404();
	    }
    	$this->getDoctrine()->getConnection();
        return $this->show('statis/user');
    }


    /**
     * 后台所有商户的订单统计
     * @Route("/order_num")
     * @param Request $request
     * @return JsonResponse
     */
    public function orderNumAction(Request $request){
	    $conditon = $this->filterRequest($request);
        $result = $apply_statics = $this->getStatisService()->getOderNum($conditon);
	    $xlabel = $this->xDateRange($conditon);

	    $chart_data = [];
	    $chart_data['xAxis']['data'] = $xlabel;

	    foreach ($xlabel as $item){
		    $chart_data['series']['user_num'][] = intval($result[$item]['user_num']);
		    $chart_data['series']['order_num'][] = intval($result[$item]['order_num']);
		    $chart_data['series']['good_num'][] = intval($result[$item]['good_num']);
		    $chart_data['series']['total_amount'][] = sprintf('%.2f',round(floatval($result[$item]['total_amount']),2));
	    }
	    return $this->parseData($chart_data);
    }

    /**
     * 后台当前商户的订单统计
     * @Route("/order_total_num")
     * @param Request $request
     * @return JsonResponse
     */
    public function orderTotalNumAction(Request $request){
        $conditon = $this->filterRequest($request);
        $conditon['partner_id'] = intval($request->getSession()->get('partner_id'));

        $result  = $this->getStatisService()->getOderTotalNum($conditon);

        $chart_data['series'] = $result;
        $chart_data['series']['total_amount'] = sprintf('%.2f',$chart_data['series']['total_amount']);
        $chart_data['series']['pay_amount'] = sprintf('%.2f',$chart_data['series']['pay_amount']);
        $chart_data['series']['good_num'] = sprintf('%d',$chart_data['series']['good_num']);
        $chart_data['series']['order_num'] = sprintf('%d',$chart_data['series']['order_num']);

        return $this->parseData($chart_data);
    }

	/**
	 * 后台所有商户的订单交易统计
	 * @Route("/order_pay")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function orderPayNumAction(Request $request){
		$conditon = $this->filterRequest($request);
		$result = $apply_statics = $this->getStatisService()->getPaymentOrderStatis($conditon);
		$xlabel = $this->xDateRange($conditon);

		$chart_data = [];
		$chart_data['xAxis']['data'] = $xlabel;

		foreach ($xlabel as $item){
			$chart_data['series']['user_num'][] = intval($result[$item]['user_num']);
			$chart_data['series']['order_num'][] = intval($result[$item]['order_num']);
			$chart_data['series']['good_num'][] = intval($result[$item]['good_num']);
			$chart_data['series']['total_amount'][] = sprintf('%.2f',round(floatval($result[$item]['total_amount']),2));
			$chart_data['series']['per_num'][] = sprintf('%.2f',round(floatval($result[$item]['per_num']),2));
		}
		return $this->parseData($chart_data);
	}

	/**
	 * 前台所有商户的订单统计
	 * @Route("/order_partner_num")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function orderPartnerNumAction(Request $request){
		$conditon = $this->filterRequest($request);
		$result = $apply_statics = $this->getStatisService()->getPartnerOderNum($conditon);
		$xlabel = $this->xDateRange($conditon);

		$chart_data = [];
		$chart_data['xAxis']['data'] = $xlabel;

		foreach ($xlabel as $item){
			$chart_data['series']['user_num'][] = intval($result[$item]['user_num']);
			$chart_data['series']['order_num'][] = intval($result[$item]['order_num']);
			$chart_data['series']['good_num'][] = intval($result[$item]['good_num']);
			$chart_data['series']['total_amount'][] = sprintf('%.2f',round(floatval($result[$item]['total_amount']),2));
		}
		return $this->parseData($chart_data);
	}

	/**
	 * 前台所有商户的订单交易统计
	 * @Route("/order_partner_pay")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function orderPayPartnerNumAction(Request $request){
		$conditon = $this->filterRequest($request);
		$result = $apply_statics = $this->getStatisService()->getPartnerPaymentOrderStatis($conditon);
		$xlabel = $this->xDateRange($conditon);

		$chart_data = [];
		$chart_data['xAxis']['data'] = $xlabel;

		foreach ($xlabel as $item){
			$chart_data['series']['user_num'][] = intval($result[$item]['user_num']);
			$chart_data['series']['order_num'][] = intval($result[$item]['order_num']);
			$chart_data['series']['good_num'][] = intval($result[$item]['good_num']);
			$chart_data['series']['total_amount'][] = sprintf('%.2f',round(floatval($result[$item]['total_amount']),2));
			$chart_data['series']['per_num'][] = sprintf('%.2f',round(floatval($result[$item]['per_num']),2));
		}
		return $this->parseData($chart_data);
	}

    /**
     * 商户的订单交易统计
     * @Route("/order_total_partner_pay")
     * @param Request $request
     * @return JsonResponse
     */
    public function orderTotalPayPartnerAction(Request $request){
        $conditon = $this->filterRequest($request);
        $result  = $this->getStatisService()->getPartnerTotalPaymentOrderStatis($conditon);

        $chart_data['series'] = $result;

        $chart_data['series']['total_amount'] = sprintf('%.2f',$chart_data['series']['total_amount']);
        $chart_data['series']['per_num'] = sprintf('%.2f',$chart_data['series']['per_num']);
        $chart_data['series']['good_num'] = sprintf('%d',$chart_data['series']['good_num']);
        $chart_data['series']['order_num'] = sprintf('%d',$chart_data['series']['order_num']);

        return $this->parseData($chart_data);
    }

	/**
	 * 前台订单查询框
	 * @Route("/order_partner_option")
	 * @return JsonResponse
	 */
	public function orderPartnerOptions(){
		$result = [
			'order_type' => [
				['label' => '全部订单','value' => 0],
				['label' => '全款订单','value' => 2],
				['label' => '赊购订单','value' => 3],
			]
		];

		return $this->parseData(['data'=>$result]);
	}
	/**
	 * 后台订单查询框
	 * @Route("/order_option")
	 * @return JsonResponse
	 */
	public function orderOptions(){
		$result = [
			'partner_type' => [
				['label' => '全部商家','value' => 0],
				['label' => '信用商家','value' => MerchantConstant::MERCHANT_CREDIT],
				['label' => '普通商家','value' => MerchantConstant::MERCHANT_ORDINARY],
				['label' => '自营商家','value' => MerchantConstant::MERCHANT_SELF_SUPPORT],
			],
			'is_credit_buy' => [
				['label' => '是否支持赊购','value' => 0],
				['label' => '是','value' => MerchantConstant::MERCHANT_CREDIT_BUY_YES],
				['label' => '否','value' => MerchantConstant::MERCHANT_CREDIT_BUY_NO],
			],
			'order_type' => [
				['label' => '全部订单','value' => 0],
				['label' => '全款订单','value' => 2],
				['label' => '赊购订单','value' => 3],
			]
		];

		return $this->parseData(['data'=>$result]);
	}

	/**
	 * @Route("/user_total")
	 */
	public function userTotalAction(){
		$statisServiceImpl = $this->getStatisService();
		$result = $statisServiceImpl->getTotalNums();
		return $this->parseData($result);
	}
    /**
     * 用户增量统计数据
     * @Route("/user_increment")
     * @param Request $request
     * @return JsonResponse
     */
    public function userIncrementAction(Request $request){
        $filter_data = $this->filterRequest($request);
		/** @var StatisServiceImpl $statisServiceImpl */
	    $statisServiceImpl = $this->getStatisService();
	    $user_real_statics = $statisServiceImpl->getRealUserRegStatis($filter_data);
        $user_reg_statics = $statisServiceImpl->getUserRegStatis($filter_data);
        $user_bank_statics = $statisServiceImpl->getBankUserRegStatis($filter_data);

        $xlabel = $this->xDateRange($filter_data);

        $chart_data = [];
        $chart_data['xAxis']['data'] = $xlabel;

        foreach ($xlabel as $item){
            $chart_data['series']['user_real'][] = intval($user_real_statics[$item]['y_data']);
            $chart_data['series']['user_reg'][] = intval($user_reg_statics[$item]['y_data']);
            $chart_data['series']['user_bank'][] = intval($user_bank_statics[$item]['y_data']);
        }
        return $this->parseData($chart_data);
    }
	/**
	 *
	 * @param $filter_data
	 * @return array
	 */
	private function xDateRange($filter_data){
		$data_range = [];
		if($filter_data['group_type'] == StatisService::GROUP_TYPE_MONTH){
			$temp_stamp = strtotime(date('Y-m',strtotime($filter_data['end_date'])));
			for ($i=0 ;$i <= $filter_data['interval'];$i++){
				$data_range[] = date('Y-m',$temp_stamp);
				$temp_stamp = strtotime('-1 month',$temp_stamp);
			}
		}else{
			$temp_stamp = strtotime(date('Y-m-d',strtotime($filter_data['end_date'])));
			for ($i=0 ;$i <= $filter_data['interval'];$i++){
				$data_range[] = date('Y-m-d',$temp_stamp);
				$temp_stamp = strtotime('-1 day',$temp_stamp);
			}
		}
		sort($data_range);
		return $data_range;
	}

	private function filterRequest($request){
		$filer_data = [];
		$end_date = $request->get('end_date') ?? date('Y-m-d');
		$start_date = $request->get('start_date') ?? date('Y-m-d',strtotime('- 29 day ',strtotime($end_date)));

		// 默认支付方式全款
		$order_pay_type = $request->get('order_pay_type',null) ?? 0;

		// 商家类型
		$partner_type = $request->get('partner_type',null) ?? 0;
		// 商家是否支持赊购
		$is_credit_buy = $request->get('is_credit_buy',null) ?? 0;
		// 商家id
		$partner_id = $request->get('partner_id',0);

		$interval = date_diff(new \DateTime($start_date),new \DateTime($end_date));

		$diff_day = $interval->format('%r%a');
		$diff_month = $interval->format('%r%m');
		$diff_year = $interval->format('%r%y');
		$diff_month = $diff_month + $diff_year * 12;
		if($diff_day < 0){
			return $this->parseData('非法时间区间');
		}else if($diff_day > 30 || $diff_month > 0){
			$filer_data['start_date'] = $start_date.' 00:00:00';
			$filer_data['end_date'] = $end_date.' 23:59:59';
			$filer_data['group_type'] = StatisService::GROUP_TYPE_MONTH;
			$filer_data['interval'] = $diff_month;
		}else{
			$filer_data['start_date'] = $start_date.' 00:00:00';
			$filer_data['end_date'] = $end_date.' 23:59:59';
			$filer_data['group_type'] = StatisService::GROUP_TYPE_DAY;
			$filer_data['interval'] = $diff_day;
		}
		$filer_data['partner_type'] = $partner_type;
		$filer_data['order_pay_type'] = $order_pay_type;
		$filer_data['is_credit_buy'] = $is_credit_buy;
		$filer_data['partner_id'] = $partner_id;

		return $filer_data;
	}

	/**
	 * 获取统计service
	 * @return StatisServiceImpl
	 */
	private function getStatisService(){
		return $this->get('application_service')->getService('Statis.StatisService');
	}

}