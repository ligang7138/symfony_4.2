<?php
/**
 * Created by App\AdminBundle.
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:00
 */

namespace App\AdminBundle\Services\Order;


interface  OrderService
{
	//获取所有订单
	public function getAllOrders($conditions = NULL);

	//获取所有交易记录
	public function getTransaction($conditions = NULL);

	//查询订单详情
	public function getOrder($conditions = NULL);

	//获取订单商品详情
	public function getOrderGoodsList($order_id=0);

	// 获取处理中的订单
    public function getHandleOrderInfo($order_status);

}