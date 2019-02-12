<?php
/**
 * Created App\AdminBundle
 * User: yangjun
 * Date: 2017/7/5
 * Time: 13:50
 */
namespace App\AdminBundle\Services\Operate\Impl;
use App\AdminBundle\Services\Operate\OperateService;
use App\AdminBundle\Services\Service;

class OperateServiceImpl extends Service implements OperateService
{
	/**
	 * 获取商户列表
	 * @param null $conditions
	 * @return array
	 */
    public function findList($conditions = null){
	    $condition = $this->getCondition($conditions);
	    $sql = 'SELECT * FROM qy_banners WHERE ' .$condition['where'].' order by b_id desc'. $condition['limit'];
//	    echo $sql;die;
	    $query = $this->getList($sql,$condition['setParams']);
//	    print_r($query);die;
	    return  $query;

    }
	/**
	 * 获取消息列表
	 * @param null $conditions
	 * @return array
	 */
	public function findMsgList($conditions = null){
		$condition = $this->getCondition($conditions);
		$sql = 'SELECT m.msg_id,m.msg_title,u.a_name,m.msg_type,m.msg_send_status,m.msg_sys_type,m.msg_add_time FROM qy_message m left join admin_users u on m.publisher_id = u.a_id WHERE m.msg_send_type = 2 and ' .$condition['where'].' order by msg_id desc '. $condition['limit'];

		$query = $this->getList($sql,$condition['setParams']);
		return  $query;

	}

    /**
	 * 获取app发布信息列表
	 * @param null $conditions
	 * @return array
	 */
    public function findAppList($conditions = null){
	    $condition = $this->getCondition($conditions);
	    $sql = 'SELECT * FROM my_release WHERE ' .$condition['where'];
//	    echo $sql;die;
	    $count = $this->getResults($sql,'fenqi');
	    $query = $this->getResults($sql. $condition['limit'],'fenqi');
	    return  ['data' => $query,'count' => count($count)];

    }

	public function getCondition($conditions){
		$where = ' 1 = 1 ';
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