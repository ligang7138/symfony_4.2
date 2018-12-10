<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25 0025
 * Time: 下午 12:26
 */

namespace App\Yszc\AdminBundle\Services\Statis\Impl;


use App\Yszc\AdminBundle\Common\CommonFunction;
use App\Yszc\AdminBundle\DependencyInjection\RedisService;
use App\Yszc\AdminBundle\Services\Service;
use App\Yszc\AdminBundle\Services\Statis\StatisService;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\Connection;

class StatisServiceImpl extends Service implements StatisService{

	// 订单已支付状态
	const ORDER_PAID_STATUS     = 3;
	// 订单退款状态
	const ORDER_REFUND_STATUS   = 7;

	// 用户来源-商城
	const USER_SOURCE_SHOP = 2;
	/**
	 * 后台所有商户的订单统计
	 * @param array $condition
	 * @return mixed
	 */
	public function getOderNum($condition = []){
		$end_date = $condition['end_date'] ?? date('Y-m-d 00:00:00');
		$start_date = $condition['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
		$group_type = $condition['group_type'] ?? self::GROUP_TYPE_DAY;

		$cache_key = self::STATIS_ORDER_NUMS.md5(implode(',',$condition));

		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}

		if($group_type == self::GROUP_TYPE_DAY){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m-%d') as xlabel_name,";
		}else if($group_type == self::GROUP_TYPE_MONTH){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m') as xlabel_name,";
		}
		$where = "1 = 1 and o.order_add_time <= '{$end_date}' and o.order_add_time >= '{$start_date}' ";
		if(!empty($condition['order_pay_type'])){
			$where .= " and o.order_pay_type = {$condition['order_pay_type']} ";
		}
		if(!empty($condition['partner_type'])){
			$where .= " and p.partner_type = {$condition['partner_type']}";
		}
		if(!empty($condition['is_credit_buy'])){
			$where .= " and p.is_credit_buy = {$condition['is_credit_buy']}";
		}
        if(!empty($condition['partner_id'])){
            $where .= " and p.partner_id = {$condition['partner_id']}";
        }

		$sql = "
			select xlabel_name,count(distinct u_code) as user_num,sum(order_num) as order_num,sum(good_num) as good_num,sum(order_amount) as total_amount,sum(pay_amount) as pay_amount from
			(select
				   {$field}
				   u_code,
				   count(distinct o.order_id) as order_num,
				   sum(i.order_goods_nums) as good_num,
				   order_amount,
                   (case when order_status >= 3 THEN order_fat_pay_amount else 0 END ) as pay_amount
			from qy_order o left join qy_order_info i on o.order_id = i.order_id  left join qy_partners p
				     on o.partner_id = p.partner_id
			where {$where} group by o.order_id,xlabel_name) 
			as t group by xlabel_name
		";
		$result = $this->getList($sql,[]);
		$ret = [];
		foreach ($result['data'] as $item){
			$ret[$item['xlabel_name']] = $item;
		}
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}


    /**
     * 获取商户的汇总订单统计
     * @param array $condition
     * @return mixed
     */
    public function getOderTotalNum($condition = []){
        $end_date = $condition['end_date'] ?? date('Y-m-d 00:00:00');
        $start_date = $condition['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
        $group_type = $condition['group_type'] ?? self::GROUP_TYPE_DAY;

        $cache_key = self::STATIS_ORDER_NUMS.md5(implode(',',$condition));

        $ret = $this->getRedis()->cache($cache_key);
        if($ret){
            return 	$ret;
        }

        $where = "1 = 1 and o.order_add_time <= '{$end_date}' and o.order_add_time >= '{$start_date}' ";
        if(!empty($condition['order_pay_type'])){
            $where .= " and o.order_pay_type = {$condition['order_pay_type']} ";
        }
        if(!empty($condition['partner_type'])){
            $where .= " and p.partner_type = {$condition['partner_type']}";
        }
        if(!empty($condition['is_credit_buy'])){
            $where .= " and p.is_credit_buy = {$condition['is_credit_buy']}";
        }
        if(!empty($condition['partner_id'])){
            $where .= " and p.partner_id = {$condition['partner_id']}";
        }
        //and ((order_pay_type in (1,2) and order_status in (3,8,10)) or (order_pay_type in (3) and order_status in (10,11)))
        $sql = "
			select count(distinct u_code) as user_num,sum(order_num) as order_num,sum(good_num) as good_num,sum(order_amount) as total_amount,sum(pay_amount) as pay_amount from
			(select
				   u_code,
				   count(distinct o.order_id) as order_num,
				   sum(i.order_goods_nums) as good_num,
				   order_amount,
                   (case when ((order_pay_type in (1,2) and order_status in (3,8,10)) or (order_pay_type in (3) and order_status in (10,11))) THEN order_fat_pay_amount else 0 END ) as pay_amount
			from qy_order o left join qy_order_info i on o.order_id = i.order_id  left join qy_partners p
				     on o.partner_id = p.partner_id
			where {$where} group by o.order_id) 
			as t 
		";
        $result = $this->getList($sql,[]);
        $ret = [];

        foreach ($result['data'] as $item){
            $ret = $item;
        }
        $config = $this->service->getParameter('admin_bundle');
        $this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
        return $ret;
    }

	/**
	 * 后台所有商户的订单交易统计
	 * @param array $condition
	 * @return mixed
	 */
	public function getPaymentOrderStatis($condition = []){
		$end_date = $condition['end_date'] ?? date('Y-m-d 00:00:00');
		$start_date = $condition['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
		$group_type = $condition['group_type'] ?? self::GROUP_TYPE_DAY;

		$cache_key = self::STATIS_PAY_NUMS.md5(implode(',',$condition));

		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}

		if($group_type == self::GROUP_TYPE_DAY){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m-%d') as xlabel_name,";
		}else if($group_type == self::GROUP_TYPE_MONTH){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m') as xlabel_name,";
		}
		$where = '1 = 1 and o.order_add_time <= ? and o.order_add_time >= ? ';
		if(!empty($condition['order_pay_type'])){
			if($condition['order_pay_type'] == 2){
				// 全款包括线上和线下，字段里有区分
				$where .= " and o.order_pay_type in (1,2) and o.order_status in (3,8,10) ";
			}elseif($condition['order_pay_type'] == 3){
				// 赊购
				$where .= " and o.order_pay_type in (3) and o.order_status in (11,10) ";
			}
		}else{

			$where .= " and ((o.order_pay_type in (1,2) and o.order_status in (3,8,10)) or (o.order_pay_type in (3) and o.order_status in (10,11)))";
		}
		if(!empty($condition['partner_type'])){
			$where .= " and p.partner_type = {$condition['partner_type']}";
		}
		if(!empty($condition['is_credit_buy'])){
			$where .= " and p.is_credit_buy = {$condition['is_credit_buy']}";
		}
		$sql = "
			select xlabel_name,count(distinct u_code) as user_num,sum(order_num) as order_num,sum(good_num) as good_num,sum(order_fat_pay_amount) as total_amount,sum(order_fat_pay_amount)/count(distinct u_code) as per_num from
			(select
				   {$field}
				   u_code,
				   count(distinct o.order_id) as order_num,
				   sum(i.order_goods_nums) as good_num,
				   order_fat_pay_amount
			from qy_order o left join qy_order_info i on o.order_id = i.order_id  left join qy_partners p
				     on o.partner_id = p.partner_id
			where {$where} group by o.order_id,xlabel_name) 
			as t group by xlabel_name
		";
		$result = $this->getList($sql,[$end_date,$start_date]);
		$ret = [];
		foreach ($result['data'] as $item){
			$ret[$item['xlabel_name']] = $item;
		}
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}

	/**
	 * 前台所有商户的订单统计
	 * @param array $condition
	 * @return mixed
	 */
	public function getPartnerOderNum($condition = []){
		$aid = $this->service->get('security.token_storage')->getToken()->getUser()->getAId();
		$end_date = $condition['end_date'] ?? date('Y-m-d 00:00:00');
		$start_date = $condition['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
		$group_type = $condition['group_type'] ?? self::GROUP_TYPE_DAY;

		$cache_key = self::STATIS_ORDER_NUMS.md5(implode(',',$condition));

		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}

		if($group_type == self::GROUP_TYPE_DAY){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m-%d') as xlabel_name,";
		}else if($group_type == self::GROUP_TYPE_MONTH){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m') as xlabel_name,";
		}
		$where = "1 = 1 and p.admin_id = {$aid} and o.order_add_time <= '{$end_date}' and o.order_add_time >= '{$start_date}' ";
		if(!empty($condition['order_pay_type'])){
			$where .= " and o.order_pay_type = {$condition['order_pay_type']} ";
		}

		$sql = "
			select xlabel_name,count(distinct u_code) as user_num,sum(order_num) as order_num,sum(good_num) as good_num,sum(order_amount) as total_amount from
			(select
				   {$field}
				   u_code,
				   count(distinct o.order_id) as order_num,
				   sum(i.order_goods_nums) as good_num,                 
				   order_amount
			from qy_order o left join qy_order_info i on o.order_id = i.order_id  left join qy_partners p
				     on o.partner_id = p.partner_id
			where {$where} group by o.order_id,xlabel_name) 
			as t group by xlabel_name
		";
		$result = $this->getList($sql,[]);
		$ret = [];
		foreach ($result['data'] as $item){
			$ret[$item['xlabel_name']] = $item;
		}
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}
	/**
	 * 前台付款订单的统计
	 * @param array $condition
	 * @return mixed
	 */
	public function getPartnerPaymentOrderStatis($condition = []){
		$aid = $this->service->get('security.token_storage')->getToken()->getUser()->getAId();
		$end_date = $condition['end_date'] ?? date('Y-m-d 00:00:00');
		$start_date = $condition['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
		$group_type = $condition['group_type'] ?? self::GROUP_TYPE_DAY;

		$cache_key = self::STATIS_PAY_NUMS.md5(implode(',',$condition));

		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}

		if($group_type == self::GROUP_TYPE_DAY){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m-%d') as xlabel_name,";
		}else if($group_type == self::GROUP_TYPE_MONTH){
			$field = "DATE_FORMAT(order_add_time, '%Y-%m') as xlabel_name,";
		}
		$where = "1 = 1 and p.admin_id = {$aid} and o.order_add_time <= ? and o.order_add_time >= ? ";
		if(!empty($condition['order_pay_type'])){
			if($condition['order_pay_type'] == 2){
				// 全款包括线上和线下，字段里有区分
				$where .= " and o.order_pay_type in (1,2) and o.order_status in (3,8,10) ";
			}elseif($condition['order_pay_type'] == 3){
				// 赊购
				$where .= " and o.order_pay_type in (3) and o.order_status in (11,10) ";
			}
		}else{

			$where .= " and ((o.order_pay_type in (1,2) and o.order_status in (3,8,10)) or (o.order_pay_type in (3) and o.order_status in (10,11)))";
		}

		$sql = "
			select xlabel_name,count(distinct u_code) as user_num,sum(order_num) as order_num,sum(good_num) as good_num,sum(order_fat_pay_amount) as total_amount,sum(order_fat_pay_amount)/count(distinct u_code) as per_num from
			(select
				   {$field}
				   u_code,
				   count(distinct o.order_id) as order_num,
				   sum(i.order_goods_nums) as good_num,
				   order_fat_pay_amount
			from qy_order o left join qy_order_info i on o.order_id = i.order_id  left join qy_partners p
				     on o.partner_id = p.partner_id
			where {$where} group by o.order_id,xlabel_name) 
			as t group by xlabel_name
		";
		$result = $this->getList($sql,[$end_date,$start_date]);
		$ret = [];
		foreach ($result['data'] as $item){
			$ret[$item['xlabel_name']] = $item;
		}
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}

    /**
     * 前台汇总付款订单的统计
     * @param array $condition
     * @return mixed
     */
    public function getPartnerTotalPaymentOrderStatis($condition = []){
        $aid = $this->service->get('security.token_storage')->getToken()->getUser()->getAId();
        $end_date = $condition['end_date'] ?? date('Y-m-d 00:00:00');
        $start_date = $condition['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));

        $cache_key = self::STATIS_PAY_NUMS.md5(implode(',',$condition));

        $ret = $this->getRedis()->cache($cache_key);
        if($ret){
            return 	$ret;
        }

        $where = "1 = 1 and p.admin_id = {$aid} and o.order_add_time <= ? and o.order_add_time >= ? ";
        if(!empty($condition['order_pay_type'])){
            if($condition['order_pay_type'] == 2){
                // 全款包括线上和线下，字段里有区分
                $where .= " and o.order_pay_type in (1,2) and o.order_status in (3,8,10) ";
            }elseif($condition['order_pay_type'] == 3){
                // 赊购
                $where .= " and o.order_pay_type in (3) and o.order_status in (11,10) ";
            }
        }else{

            $where .= " and ((o.order_pay_type in (1,2) and o.order_status in (3,8,10)) or (o.order_pay_type in (3) and o.order_status in (10,11)))";
        }

        $sql = "
			select count(distinct u_code) as user_num,sum(order_num) as order_num,sum(good_num) as good_num,sum(order_fat_pay_amount) as total_amount,sum(order_fat_pay_amount)/count(distinct u_code) as per_num from
			(select
				   u_code,
				   count(distinct o.order_id) as order_num,
				   sum(i.order_goods_nums) as good_num,
				   order_fat_pay_amount
			from qy_order o left join qy_order_info i on o.order_id = i.order_id  left join qy_partners p
				     on o.partner_id = p.partner_id
			where {$where} group by o.order_id) 
			as t 
		";
        $result = $this->getList($sql,[$end_date,$start_date]);
        $ret = [];
        foreach ($result['data'] as $item){
            $ret = $item;
        }
        $config = $this->service->getParameter('admin_bundle');
        $this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
        return $ret;
    }

	/**
	 * 所有商户的订单交易统计
	 * @return mixed
	 */
	public function getTotalNums(){
		$date = date('Y-m-d');
		$cache_key = self::STATIS_TOTAL_NUMS.$date;
		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}
		$shop_source = self::USER_SOURCE_SHOP;
		// 获取累计注册用户数
		$total_user_sql = "select count(u_id) as num from my_users where u_sys_source = {$shop_source}";
		// 获取累计实名用户数
		$total_verify_user_sql = "select count(i.u_code) as num from my_user_info i left join my_users u on i.u_code = u.u_code where u.u_sys_source = {$shop_source}";

		// 获取累计用户授信人数
		$total_credit_user_sql = "select count(v.u_code) as num from my_user_credit_value v left join my_users u on v.u_code = u.u_code where u.u_sys_source = {$shop_source}";
		// 获取累计用户绑卡人数
		$total_bank_user_sql = "select count(b.u_code) as num from my_user_bank b left join my_users u on b.u_code = u.u_code where u.u_sys_source = {$shop_source}  and b.ub_is_bind = 1";
		// 获取累计付款用户
		$total_pay_user_sql = 'select COUNT(distinct u_code) as num from ys_order o where  ((o.order_pay_type in (1,2) and o.order_status in (3,8,10)) or (o.order_pay_type in (3) and o.order_status in (10,11))) ';

		$ret = [
			'data'=>[
				//获取累计注册用户数
				'totalRegister' => intval($this->getResult($total_user_sql,'num','fenqi')),
				//获取累计实名用户数
				'totalRealName' => intval($this->getResult($total_verify_user_sql,'num','fenqi')),
				//获取累计授信用户数
				'totalCreditUser' => intval($this->getResult($total_credit_user_sql,'num','fenqi')),
				//获取累计用户绑卡人数
				'totalBankUser' => intval($this->getResult($total_bank_user_sql,'num','fenqi')),
				//获取累计付款用户
				'totalPayUser' => intval($this->getResult($total_pay_user_sql,'num')),

			]
		];
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}


    /**
     * 获取用户注册统计
     */
    public function getUserRegStatis($filter_datas = []){
        $end_date = $filter_datas['end_date'] ?? date('Y-m-d 00:00:00');
        $start_date = $filter_datas['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
        $group_type = $filter_datas['group_type'] ?? self::GROUP_TYPE_DAY;

        $cache_key = self::STATIS_USERREG_NUMS.md5(implode(',',$filter_datas));

        $ret = $this->getRedis()->cache($cache_key);
        if($ret){
            return 	$ret;
        }

        if($group_type == self::GROUP_TYPE_DAY){
            $field = "DATE_FORMAT(u_reg_time, '%Y-%m-%d') as xlabel_name,";
        }else if($group_type == self::GROUP_TYPE_MONTH){
            $field = "DATE_FORMAT(u_reg_time, '%Y-%m') as xlabel_name,";
        }
        $sql = "select 
                    {$field}
			        count(u_id) as y_data 
		    	    from  my_users
		    	    where u_sys_source = 2 and u_reg_time >= '{$start_date}' and u_reg_time <='{$end_date}'
		    	    group by xlabel_name ";
	    $temp_ret = $this->getResults($sql,'fenqi');
        $ret = [];
        foreach ($temp_ret as $item){
            $ret[$item['xlabel_name']] = $item;
        }
        $config = $this->service->getParameter('admin_bundle');
        $this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
        return $ret;
    }

	/**
	 * 获取新增实名用户统计
	 */
	public function getRealUserRegStatis($filter_datas = []){
		$end_date = $filter_datas['end_date'] ?? date('Y-m-d 00:00:00');
		$start_date = $filter_datas['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
		$group_type = $filter_datas['group_type'] ?? self::GROUP_TYPE_DAY;

		$cache_key = self::STATIS_REAL_USERREG_NUMS.md5(implode(',',$filter_datas));

		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}

		if($group_type == self::GROUP_TYPE_DAY){
			$field = "DATE_FORMAT(ui_ident_time, '%Y-%m-%d') as xlabel_name,";
		}else if($group_type == self::GROUP_TYPE_MONTH){
			$field = "DATE_FORMAT(ui_ident_time, '%Y-%m') as xlabel_name,";
		}
		$sql = "select 
                    {$field}
			        count(i.u_code) as y_data 
		    	    from  my_user_info i
		    	    left join my_users u
		    	    on i.u_code = u.u_code
		    	    where u.u_sys_source = 2 and i.ui_ident_time >= '{$start_date}' and ui_ident_time <='{$end_date}'
		    	    group by xlabel_name ";
		$temp_ret = $this->getResults($sql,'fenqi');
		$ret = [];
		foreach ($temp_ret as $item){
			$ret[$item['xlabel_name']] = $item;
		}
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}

	/**
	 * 获取新增绑卡用户统计
	 */
	public function getBankUserRegStatis($filter_datas = []){
		$end_date = $filter_datas['end_date'] ?? date('Y-m-d 00:00:00');
		$start_date = $filter_datas['start_date'] ?? date('Y-m-d 23:59:59',strtotime('- 6 day ',strtotime($end_date)));
		$group_type = $filter_datas['group_type'] ?? self::GROUP_TYPE_DAY;

		$cache_key = self::STATIS_BANK_USERREG_NUMS.md5(implode(',',$filter_datas));

		$ret = $this->getRedis()->cache($cache_key);
		if($ret){
			return 	$ret;
		}

		if($group_type == self::GROUP_TYPE_DAY){
			$field = "DATE_FORMAT(ub_add_time, '%Y-%m-%d') as xlabel_name,";
		}else if($group_type == self::GROUP_TYPE_MONTH){
			$field = "DATE_FORMAT(ub_add_time, '%Y-%m') as xlabel_name,";
		}
		$sql = "select 
                    {$field}
			        count(b.u_code) as y_data 
		    	    from  my_user_bank b
		    	    left join my_users u
		    	    on b.u_code = u.u_code
		    	    where u.u_sys_source = 2 and ub_add_time >= '{$start_date}' and ub_add_time <='{$end_date}'  and b.ub_is_bind = 1
		    	    group by xlabel_name ";
		$temp_ret = $this->getResults($sql,'fenqi');
		$ret = [];
		foreach ($temp_ret as $item){
			$ret[$item['xlabel_name']] = $item;
		}
		$config = $this->service->getParameter('admin_bundle');
		$this->getRedis()->cache($cache_key,$ret,eval("return $config[cacheTime];"));
		return $ret;
	}



	/**
	 * 返回查询结果(单条)
	 * @param $sql
	 * @return mixed
	 */
	private function getResult($sql,$key='',$database = 'default'){
	    $result = $this->doctrine->getManager($database)->getConnection()->fetchAssoc($sql);
		return empty($key)?$result:$result[$key];
	}

    /**
     * 返回查询结果(单条)
     * @param $sql
     * @return mixed
     */
    private function getTotal($sql,$key=''){
        $cache_key = self::STATIS_TOTAL_NUMS.md5($sql);
        $result = $this->getRedis()->cache($cache_key);
        if(!$result){
            $result = $this->doctrine->getConnection()->fetchAssoc($sql);
            $config = $this->service->getParameter('admin_bundle');
            $this->getRedis()->cache($cache_key,$result,(eval("return $config[cacheTime];")+mt_rand(60,300))); //随机过期时间
        }

        return empty($key)?$result:$result[$key];
    }


	/**
	 * 获取redis实例
	 * @return RedisService
	 */
	private function getRedis(){
		return $this->service->get('redis_service');
	}
	/**
	 * 返回查询结果集
	 * @param string $sql
	 * @param string $database
	 * @return mixed
	 */
	public function getResults($sql,$database = 'default'){
		return $this->doctrine->getManager($database)->getConnection()->fetchAll($sql);
	}

}