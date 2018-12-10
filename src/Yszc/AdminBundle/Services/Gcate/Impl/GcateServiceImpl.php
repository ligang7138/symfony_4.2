<?php
/**
 * Created AdminBundle
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:05
 */

namespace App\Yszc\AdminBundle\Services\Gcate\Impl;
use App\Yszc\AdminBundle\Services\Service;
use App\Yszc\AdminBundle\Services\Gcate\GcateService;

class GcateServiceImpl extends Service implements GcateService
{

    public function getAllGcates($conditions = NULL){
        $curr_login_user = $this->service->get('security.token_storage')->getToken()->getUser();
        $a_type = $curr_login_user->getAType();
        if($a_type == 0){
            $where = " where admin_id=".$curr_login_user->getAId();
        }else{
            $where = " where 1";
        }
//        if($conditions['gc_id'] > 0){
//            $sql = "select gc_node from qy_goods_cate where gc_id=".$conditions['gc_id'];
//            $res = $this->db->fetchAssoc($sql,[]);
//            $conditions['gc_node'] = $res['gc_node'];
//        }
        if($conditions['gc_node'] >0){
            // 获取上级分类信息
            $upInfo = [];
            $sql = "select gc_node,gc_name from qy_goods_cate where gc_id=".$conditions['gc_node']." and gc_node !=0";
            $upres = $this->db->fetchAssoc($sql,[]);
            if(empty($upres)){
                $sql = "select gc_node,gc_name from qy_goods_cate where gc_id=".$conditions['gc_node'];
                $upres = $this->db->fetchAssoc($sql,[]);
            }
            $upInfo['upName'] = $upres['gc_name'];
            if($upres){
                $sql = "select gc_name from qy_goods_cate where gc_id=".$upres['gc_node']." and gc_node =0";
                $topres = $this->db->fetchAssoc($sql,[]);
                $upInfo['topName'] = $topres['gc_name'];
            }
            $where .= " and gc_node=".$conditions['gc_node'];
        }else{
            $where .= " and gc_node=0";
        }
        if($conditions['gc_name']){
            $where .= " and gc_name like '%".trim($conditions['gc_name'])."%'";
        }
        $order = " order by gc_id desc ";
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }
        $sql = "select * from qy_goods_cate ".$where.$order.$limit;
        $res = $this->db->fetchAll($sql,[]);
        $sqlCount = "select count(1) as totalNum from qy_goods_cate ".$where;
        $countRes = $this->db->fetchAssoc($sqlCount,[]);
        $cateInfo=array();
        $packData=array();
        foreach ($res as $menu) {
            $packData[$menu['gc_id']] = ['gc_img'=>$menu['gc_img'],'gc_sort'=>$menu['gc_sort'],'gc_id'=>$menu['gc_id'],'gc_node'=>$menu['gc_node'],'admin_id'=>$menu['admin_id'],'p_id'=>$menu['p_id'],'gc_name'=>$menu['gc_name'],'gc_add_time'=>$menu['gc_add_time'],'gc_update_time'=>$menu['gc_update_time'],'gc_order'=>$menu['gc_order'],'gc_attribute'=>$menu['gc_attribute'],'gc_remark'=>$menu['gc_remark'],'gc_status'=>$menu['gc_status']];
        }
        foreach ($packData as $key =>$val){
            if($val['gc_node']==$conditions['gc_node']){
                $cateInfo[$key]=&$packData[$key];
            }else{
                $packData[$val['gc_node']]['lowerLevel'][]=&$packData[$key];
            }
        }
        $data['data'] = $cateInfo;
        $data['count'] = $countRes['totalNum'];
        $data['upInfo'] = $upInfo;
        return $data;
    }

    public function getLowerLevelInfo($gc_node){
        if($gc_node){
            $where = " WHERE gc_node=".$gc_node;
        }else{
            $where = " WHERE gc_node=0";
        }
        $sql = "SELECT * FROM qy_goods_cate WHERE gc_status=1 AND gc_id IN (SELECT gc_id FROM qy_goods_cate $where)";
        $res = $this->db->fetchAll($sql,[]);
        return $res;
    }

}