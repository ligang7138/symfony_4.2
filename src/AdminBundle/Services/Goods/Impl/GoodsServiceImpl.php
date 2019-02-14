<?php
/**
 * Created AdminBundle
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:05
 */

namespace App\AdminBundle\Services\Goods\Impl;
use App\AdminBundle\Services\Service;
use App\AdminBundle\Services\Goods\GoodsService;

class GoodsServiceImpl extends Service implements GoodsService
{

    public function getGoodsList($conditions = null){
        $curr_login_user = $this->service->get('security.token_storage')->getToken()->getUser();
        $a_type = $curr_login_user->getAType();
	    $where = ' where 1 ';
	    if('0'===$a_type){
		    $where .= ' and g.partner_id = '.$conditions['partner_id'];
	    }
        if($conditions['g_name']){
            $where .= " and g.g_name like '%".trim($conditions['g_name'])."%'";
        }
        if($conditions['a_name']){
            $where .= " and adi.`a_true_name` like '%".trim($conditions['a_name'])."%'";
        }
        if(is_numeric($conditions['g_status']) && trim($conditions['g_status'])>0){
	        $where .= " and g.g_status in($conditions[g_status])";
        }elseif(trim($conditions['g_status'])==='0'){
            $where .= " and g.g_status in(1,2,3,4,7)";
        }
	    if(trim($conditions['g_check_status']) && trim($conditions['g_check_status'])>0){
        	$status = implode(',',explode('-',$conditions['g_check_status']));
		    $where .= " and g.g_status in($status)";
	    }
        if($conditions['gc_id'] > 0){
            $where .= " and g.gc_id=".trim($conditions['gc_id']);
        }
        if($conditions['gb_id'] > 0){
            $where .= " and g.gb_id=".trim($conditions['gb_id']);
        }
        $order = " order by g.g_add_time desc ";
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }
        $sql = "SELECT g.*,gb.gb_name,gp.partner_name,gp.`partner_code`,adi.`a_true_name`,gc.gc_name FROM `qy_goods` AS g LEFT JOIN `qy_goods_brand` AS gb ON g.gb_id=gb.gb_id LEFT JOIN qy_partners AS gp ON g.`partner_id`=gp.`partner_id` LEFT JOIN `admin_users` AS ad ON gp.`admin_id`=ad.`a_id` left join qy_goods_cate as gc on g.gc_id = gc.gc_id 
LEFT JOIN `admin_user_info` AS adi ON adi.`a_id`=ad.`a_id` ".$where.$order.$limit;
        $query = $this->getList($sql,[]);
        return  $query;
    }

    public function getGBrandList($conditions = NULL){
        $curr_login_user = $this->service->get('security.token_storage')->getToken()->getUser();
        $a_type = $curr_login_user->getAType();
	    $where = ' where 1 ';
	    if('0'===$a_type){
		    $where .= ' and admin_id = '.$curr_login_user->getAId();
	    }
        if($conditions['gb_code']){
            $where .= " and gb_code='".trim($conditions['gb_code'])."'";
        }
        if($conditions['gb_name']){
            $where .= " and gb_name like '%".trim($conditions['gb_name'])."%'";
        }
        $order = " order by gb_id desc ";
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }
        $sql = "select * from qy_goods_brand ".$where.$order.$limit;
        $query = $this->getList($sql,[]);
        return  $query;
    }

    public function delSpecPriceInfo($gnId){
        $res = $this->db->exec("DELETE FROM qy_goods_spec_price WHERE gn_id in($gnId)");
        return $res;
    }

    public function getGoodsStandard($gn_id){
        if($gn_id){
            $sql = "select gn_id,REPLACE(gn_spec_num, '/', '') as gn_spec_num,gn_spec_num as old_gn_spec_num,gn_spec_type,gn_price,gn_stock,gn_total_stock,gn_stock_remind,gn_add_time,gn_update_time from qy_goods_spec_price where gn_id in($gn_id)";
            return $this->db->fetchAll($sql,[]);
        }else{
            return [];
        }
    }

    public function getGoodsInfo($gId){
        $sql = "SELECT g.*,gb.gb_name,gp.partner_name,gp.`partner_code`,gc.`gc_name` FROM `qy_goods` AS g 
LEFT JOIN `qy_goods_brand` AS gb ON g.gb_id=gb.gb_id 
LEFT JOIN qy_partners AS gp ON g.`partner_id`=gp.`partner_id` 
LEFT JOIN `qy_goods_cate` AS gc ON g.gc_id=gc.`gc_id` WHERE g.g_id=".$gId;
        return $this->db->fetchAssoc($sql);
    }

}