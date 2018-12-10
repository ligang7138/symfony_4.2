<?php
/**
 * Created AdminBundle
 * User: yangjun
 * Date: 2017/7/5
 * Time: 13:50
 */

namespace App\Yszc\AdminBundle\Services\Partner\Impl;
use App\Yszc\AdminBundle\Services\Partner\PartnerService;
use App\Yszc\AdminBundle\Services\Service;

class PartnerServiceImpl extends Service implements PartnerService
{
	/**
	 * 获取商户列表
	 * @param null $conditions
	 * @return array
	 */
    public function findList($conditions = null){
	    $condition = $this->getCondition($conditions);
	    $sql = 'SELECT u.a_name,u.a_add_time,u.a_partner_type,p.partner_id,p.partner_name,p.partner_phone,p.is_lonely,p.is_normal,a.a_true_name,a.a_phone,a.a_ident_no,p.partner_type,p.partner_status,p.partner_service_code,p.partner_add_time,t.wait_pay_amt FROM admin_users u LEFT JOIN admin_user_info a ON u.a_id = a.a_id LEFT JOIN qy_partners p ON u.a_id = p.admin_id LEFT JOIN qy_partner_info i ON p.partner_id = i.partner_id left join (select o.partner_id,sum(o.order_fat_pay_amount-o.order_settlement_amt) as wait_pay_amt from qy_partners p left join qy_order o on p.partner_id = o.partner_id where o.order_status in (3,4,5,8,10,11,12) group by o.partner_id) as t on p.partner_id=t.partner_id where ' .$condition['where'].' order by p.partner_id desc'. $condition['limit'];

	    $query = $this->getList($sql,$condition['setParams']);
	    return  $query;

    }

	/**
	 * 获取店铺列表
	 * @param null $conditions
	 * @return array
	 */
	public function findShopList($conditions = null){
		$condition = $this->getCondition($conditions);
		$sql = 'SELECT p.partner_id,p.partner_name,p.partner_phone,p.is_lonely,p.is_normal,a.a_true_name,a.a_phone,a.a_ident_no,p.partner_type,p.partner_status,p.partner_service_code,p.partner_add_time FROM qy_partners p LEFT JOIN admin_users u ON p.admin_id = u.a_id LEFT JOIN admin_user_info a ON a.a_id = u.a_id LEFT JOIN qy_partner_info i ON p.partner_id = i.partner_id WHERE ' .$condition['where'].' and p.is_agree = 2 order by p.partner_id desc'. $condition['limit'];
//		echo $sql;die;
		$query = $this->getList($sql,$condition['setParams']);
//	    print_r($query);die;
		return  $query;

	}

	public function getCondition($conditions){
		$where = ' 1 = 1 and u.a_type = 0 ';
		$setParams = [];
		$params = [];
		if(!is_null($conditions)){
			if(isset($conditions['partner_code']) && !empty($conditions['partner_code'])){
				$where .= " and p.partner_id = :id";
				$params['partner_code'] = trim($conditions['partner_code']);
				$setParams['id'] = $conditions['partner_code'];
			}

			if(isset($conditions['phone']) && !empty($conditions['phone'])){
				$where .= " and u.a_name = :phone";
				$params['phone'] = trim($conditions['phone']);
				$setParams['phone'] = $conditions['phone'];
			}

			if(isset($conditions['name']) && !empty($conditions['name'])){
				$where .= " and a.a_true_name = :name";
				$params['name'] = trim($conditions['name']);
				$setParams['name'] = $conditions['name'];
			}

			if(isset($conditions['is_normal']) && !empty($conditions['is_normal'])){
				$where .= " and p.is_normal = :is_normal";
				$params['is_normal'] = trim($conditions['is_normal']);
				$setParams['is_normal'] = $conditions['is_normal'];
			}

			if(isset($conditions['partner_name']) && !empty($conditions['partner_name'])){
				$where .= " and p.partner_name = :partner_name";
				$params['partner_name'] = trim($conditions['partner_name']);
				$setParams['partner_name'] = $conditions['partner_name'];
			}

			if(isset($conditions['partnerCatagory']) && !empty($conditions['partnerCatagory'])){
				$where .= " and p.partner_type = :certification_type";
				$params['partnerCatagory'] = trim($conditions['partnerCatagory']);
				$setParams['certification_type'] = $conditions['partnerCatagory'];
			}

			if(isset($conditions['checkStatus']) && !empty($conditions['checkStatus'])){
				if($conditions['checkStatus'] == 1){
					$where .= ' and p.partner_status is NULL ';
				}else{
					$where .= " and p.partner_status = :partner_status";
				}
				$params['checkStatus'] = trim($conditions['checkStatus']);
				$setParams['partner_status'] = $conditions['checkStatus'];
			}

			$limit = '';
			if(!empty($conditions['limit'])){
				$limit = ' limit '.$conditions['limit']['start'].','.$conditions['limit']['end'];
			}

		}
		return [
			'where'     => $where,
			'limit'     => $limit,
			'params'    => $params,
			'setParams' => $setParams
		];
	}
    // 获取商户审核记录日志
    public function getCheckLog($partnerId,$type){
    	$sql = 'select * from qy_partner_check_log WHERE partner_id=? AND check_type=? ORDER BY id DESC ';
    	$query = $this->getList($sql,[$partnerId,$type]);
    	return $query;
    }

	/**
	 * 获取商户上传资料分类
	 * @param $partner_id
	 * @param $pd_type
	 * @return array
	 */
	public function getDaturms($partner_id,$pd_type){
		if(is_array($pd_type)){
			$ud_type_str = implode("','",$pd_type);
			$other_where = " pd_type in ('{$ud_type_str}')";
		}else{
			$other_where = " pd_type = '{$pd_type}' ";
		}
		$sql = "select * from qy_partner_daturm where partner_id=? AND {$other_where}";
		$result = $this->db->fetchAll($sql,[$partner_id]);
		$list = [];
		foreach ($result as $item){
			$list[$item['pd_type']][] = $item;
		}
		return $list;
	}

	/**
	 * @param $pid 商户id
	 * @param $ptype 资料类型 eg: a , g
	 * @return mixed
	 */
	public function getDaturmInfo($pid,$ptype){
		$sql = "select * from qy_partner_daturm where partner_id =? AND qy_partner_daturm.pd_type in(?)";
		return $this->db->fetchAll($sql,[$pid,$ptype]);
	}

	public function getOpenBankList(){
		$sql = "
			select code,bank_no,`name` from my_open_bank where is_able = 1 and bank_no <> ''
		";
		return $this->getResults($sql,'fenqi');
	}

	public function getTradeInfo(){
		$sql = "
			select pt_name,pt_brokerage_rate,pt_id from my_product_trade where pt_status = 1 ";
		return $this->getResults($sql,'fenqi');
	}
	/**
	 * @param $partnerId 商家id
	 * @param $service_code 商家服务号
	 * @param $isLonely 搜索状态
	 */
	public function addLonelySet($partnerId,$service_code,$isLonely){
		$redis = $this->service->get('redis_service');
		// 公开搜索
		if($isLonely == 3){
			$redis->zrem('lonely:partner',$service_code);
		}elseif($isLonely == 1){
			// 限制全部
			$redis->zadd('lonely:partner',$partnerId,$service_code);
		}
	}
	/**
	 * 将商家的经纬度加入redis，以城市code作为key
	 * @param $city
	 * @param $partnerLng
	 * @param $partnerLat
	 * @param $partnerId
	 */
	public function geoAdd($city,$partnerLng,$partnerLat,$partnerId){
		$redis = $this->service->get('redis_service');
		$redis->zrem('GEO:'.$city, $partnerLng);
		$redis->geoAdd('GEO:'.$city, $partnerLng, $partnerLat, $partnerId);
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