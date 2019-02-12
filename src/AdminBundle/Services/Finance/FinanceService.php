<?php
/**
 * Created by App\AdminBundle.
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:00
 */

namespace App\AdminBundle\Services\Finance;


interface  FinanceService
{
	//获取结算记录
	public function getSettlementOrder($conditions = NULL);

	//获取待结算订单数据
    public function getSettlementOrderInfo($partner_id);

}