<?php
/**
 * Created App\AdminBundle
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:05
 */

namespace App\AdminBundle\Services\Finance\Impl;
use App\AdminBundle\Services\Service;
use App\AdminBundle\Services\Finance\FinanceService;

class FinanceServiceImpl extends Service implements FinanceService
{

	//获取结算记录
	public function getSettlementOrder($conditions = NULL){
		$where = ' where 1 ';
		if('0'===$conditions['u_type']){
			$where .= ' and os.partner_id = '.$conditions['partner_id'];
		}

		if(is_numeric($conditions['os_status'])){
			$where .= " and os.os_status='$conditions[os_status]'";
		}

		if(is_numeric($conditions['os_channel'])){
			$where .= " and os.os_channel='$conditions[os_channel]'";
		}

		if(trim($conditions['os_start'])){
			$where .= " and os.os_time>='$conditions[os_start]'";
		}

		if(trim($conditions['os_end'])){
			$where .= " and os.os_time<='$conditions[os_end]'";
		}

		$limit = '';
		if(!empty($conditions['limit'])){
			$limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
		}
		$order = ' order by os.os_time desc ';
		$sql = 'SELECT os.* FROM qy_order_settlement os ' . $where . $order . $limit;
		$query = $this->getList($sql,[]);
		return  $query;
	}

	//获取待结算订单数据
	public function getSettlementOrderInfo($partner_id){
		$query = $this->db->fetchAssoc('SELECT sum(o.order_fat_pay_amount-o.order_settlement_amt) as surplus_settlement_amt,sum(o.order_settlement_amt) as y_settlement_amt ,p.partner_name FROM qy_order o LEFT JOIN qy_partners p on p.partner_id=o.partner_id WHERE o.partner_id=? and o.order_pay_time>0 and o.order_status in(3,4,5,8,10,11,12) and DATE_ADD(o.order_pay_time,INTERVAL 1 DAY) <=sysdate()',[$partner_id]);
		$result = $this->db->fetchAssoc('SELECT sum(os_amount) as os_total_amount  FROM qy_order_settlement WHERE os_status=0 and partner_id=?',[$partner_id]);
		$query['surplus_settlement_amt'] = round($query['surplus_settlement_amt']-$result['os_total_amount'],2);
		$query['freeze_settlement_amt'] = $result['os_total_amount'];
		return  $query;
	}

	//根据提现额获取对应订单ID
	public function getOrderIdsByCash($partner_id,$cash_amt=0){
		if($cash_amt<=0){
			return [];
		}
		$sql = 'SELECT o.* FROM qy_order o WHERE o.partner_id=? and o.order_is_settlement=0
                and o.order_pay_time>0 and o.order_status in(3,4,5,8,10,11,12) and DATE_ADD(o.order_pay_time,INTERVAL 1 DAY) <=sysdate() ORDER BY o.order_id ASC ' ;
		$order_list = $this->db->fetchAll($sql,[$partner_id]);
		$order_ids = [];
		$surplus_amt = $cash_amt;
		foreach($order_list as $order){
			$should_pay_amt = round($order['order_fat_pay_amount']-$order['order_settlement_amt'],2);
			if($surplus_amt>=$should_pay_amt){
				$surplus_amt = round($surplus_amt-$should_pay_amt,2);
				$order_ids[] = $order['order_id'];
			}else{
				if($surplus_amt<=0){
					break;
				}else{
					$order_ids[] = $order['order_id'];
					$surplus_amt = 0;
				}
			}

		}
		return $order_ids;
	}
}