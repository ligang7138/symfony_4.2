<?php
namespace AdminBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GoodCateRepository extends EntityRepository{

	public function findList($conditions = null){
//		$conditions['p_name'] = '测试1';
		$conditions['p_code'] = '0022';
		$conditions['p_type'] = 2;
		$conditions['p_status'] = 1;
//		print_r($conditions);die;
		$where = ' 1 = 1 ';
		$setParams = [];
		if(!is_null($conditions)){
			if(isset($conditions['p_name']) && !empty($conditions['p_name'])){
				$where .= " and m.partnerName = :name";
				$params['p_name'] = trim($conditions['p_name']);
				$setParams['name'] = $conditions['p_name'];
			}

			if(isset($conditions['p_code']) && !empty($conditions['p_code'])){
				$where .= " and m.partnerCode = :code";
				$params['p_code'] = trim($conditions['p_code']);
				$setParams['code'] = $conditions['p_code'];
			}

			if(isset($conditions['p_status']) && !empty($conditions['p_status'])){
				$where .= " and m.partnerStatus = :status";
				$params['p_status'] = trim($conditions['p_status']);
				$setParams['status'] = $conditions['p_status'];
			}

			if(isset($conditions['p_type']) && !empty($conditions['p_type'])){
				$where .= " and m.partnerType = :type";
				$params['p_type'] = trim($conditions['p_type']);
				$setParams['type'] = $conditions['p_type'];
			}

			if(!empty($conditions['limit'])){
				$offset = $conditions['limit']['start'];
				$limit = $conditions['limit']['end'];
			}

		}
		$build = $this->createQueryBuilder('m');
//		echo $where;die;
		$build
			->select('m.partnerId', 'm.partnerStatus')
			->addSelect('m.partnerAddTime')
	/*		->join('building.users', 'users')
			// limit the numbers for 1 result!
			->leftJoin('building.numbers', 'numbers') // only select 1 result instead of more.*/
			->where($where)
			->setParameters($setParams);

		$paginator = new Paginator($build->getQuery(), $fetchJoinCollection = true);
		$result = $paginator->getQuery()
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getArrayResult(); // 获取数组结果集
//			->getResult(); // 获取对象结果集
		return ['data' => $result,'count' => count($build->getQuery()->getResult())];
	}
}
