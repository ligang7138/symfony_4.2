<?php
/**
 * Created by Tourbundel.
 * User: hc
 * Date: 2016/11/30
 * Time: 15:21
 */

namespace App\Yszc\AdminBundle\Services;

abstract class Service
{
    protected $doctrine;
    protected $service;
    protected $db;

    public function __construct($doctrine,$service)
    {
        $this->doctrine = $doctrine;
        $this->service  = $service;
        $this->db       = $this->doctrine->getConnection();
    }

    /**
     * 用于分页
     * @param string $option 如果为数组则为拼接sql，  如果为字符串则为自定义SQL语句
     * @param string array $params 绑定的变量值，默认为NULL，否则按照变量顺序依次写入数组中
     * @return array 返回查询的数据集与数据集的总条数
     */
    protected function getList($sql,$params=null){
        $data = $nums = null;
        if(is_string($sql)){
            $data = $this->doctrine->getConnection()->fetchAll($sql,$params);
            $sql =preg_replace(array('/^select([\s\S]*?)[\s]+from[\s]+(.*)/is','/(order[\s]+by[\s\d\w\,\.]+)?(limit[\s]+[0-9]+(\,[0-9]+)?)?/is'),array('select total_nums from (select count(1) as  total_nums from \\2) ptemp ',''),strtolower($sql));
		$nums = $this->doctrine->getConnection()->fetchAssoc($sql,$params);
        }

        return array('data'=>$data,'count'=>$nums['total_nums']);
    }

}