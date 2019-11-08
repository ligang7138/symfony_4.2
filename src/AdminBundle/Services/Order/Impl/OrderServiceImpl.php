<?php
/**
 * Created App\AdminBundle
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:05
 */

namespace App\AdminBundle\Services\Order\Impl;
use App\AdminBundle\Services\Service;
use App\AdminBundle\Services\Order\OrderService;

class OrderServiceImpl extends Service implements OrderService
{

    public function getAllOrders($conditions = NULL){
    	$where = ' where 1 ';
    	if('0'===$conditions['u_type']){
			$where .= ' and p.partner_id = '.$conditions['partner_id'];
	    }
        if(!empty($conditions['order_no'])){
	        $where .= " and o.order_no='$conditions[order_no]'";
        }

	    if(!empty($conditions['u_phone'])){
		    $where .= " and o.u_phone='$conditions[u_phone]'";
	    }

        if(!empty($conditions['search_key'])){
            $where .= " and (o.consignee_mbl like '{$conditions['search_key']}%' or o.order_no like'{$conditions['search_key']}%') ";
        }

	    if(!empty($conditions['order_status']) || $conditions['order_status'] === '0'){
		    $where .= " and o.order_status='$conditions[order_status]'";
	    }

	    if(!empty($conditions['order_pay_type'])){
		    $where .= " and o.order_pay_type='$conditions[order_pay_type]'";
	    }

	    if(!empty($conditions['o_start'])){
		    $where .= " and o.order_add_time>='$conditions[o_start]'";
	    }

	    if(!empty($conditions['o_end'])){
		    $where .= " and o.order_add_time<='$conditions[o_end]'";
	    }
        
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }
	    $order = ' order by o.order_id desc ';
		$sql = 'SELECT o.*,p.* FROM ys_order o LEFT JOIN ys_partners p ON p.partner_id=o.partner_id ' . $where . $order . $limit;

        $query = $this->getList($sql,[]);
        return  $query;
    }

	public function getTransaction($conditions = NULL){
		$limit = '';
		if(!empty($conditions['limit'])){
			$limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
		}

		$query = $this->getList('SELECT t.*,o.*,p.* FROM ys_transaction t  LEFT JOIN ys_order o ON t.order_id=o.order_id LEFT JOIN ys_partners p ON p.partner_id=o.partner_id WHERE o.u_true_name like ? ' . $limit,[$conditions['uname'] . '%']);
		return  $query;
	}

	public function getOrder($conditions = NULL){
		$where = '';
		if(0==$conditions['a_type']){
			$where  = ' and o.partner_id='.$conditions['partner_id'];
		}
		$query = $this->db->fetchAssoc('SELECT o.*,p.* FROM ys_order o LEFT JOIN ys_partners p ON p.partner_id=o.partner_id WHERE o.order_id = ? '.$where,[$conditions['order_id']]);
		return  $query;
	}

	public function getOrderGoodsList($order_id=0){
		$query = $this->db->fetchAll('SELECT oi.*,gc.*,g.*,gsp.* FROM ys_order_info oi LEFT JOIN ys_goods g ON oi.g_id=g.g_id LEFT JOIN ys_goods_cate gc ON g.gc_id=gc.gc_id LEFT JOIN ys_goods_spec_price gsp ON gsp.gn_id=oi.gn_id WHERE oi.order_id = ? ',[$order_id]);
		return  $query;
	}

    /**
     * 更新订单状态
     * @param $order_id
     * @param $new_status
     * @param $old_status
     * @return mixed
     */
    public function updateOrderStatus($order_id,$new_status,$old_status,$msg=''){
        $res = $this->db->exec("UPDATE ys_order set order_status = {$new_status},pay_result_msg={$msg} WHERE order_id = {$order_id} and order_status = {$old_status}");
        return $res;
    }

	public function getHandleOrderInfo($order_status){
        $sql = "SELECT o.order_id,o.order_no,o.order_status,t.t_id,t.t_status,t.order_serial_num FROM `ys_order` AS o LEFT JOIN `ys_transaction` AS t ON o.`order_id`=t.order_id WHERE o.order_status=? AND o.order_pay_type=2 AND t.t_status=6";
        $ret = $this->db->fetchAll($sql,[$order_status]);
        return $ret;
    }
}