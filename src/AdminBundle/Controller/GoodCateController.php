<?php
namespace App\AdminBundle\Controller;
use App\AdminBundle\Constant\MerchantConstant;
use App\AdminBundle\Entity\YsGoodsCate;
use App\AdminBundle\Services\Gcate\Impl\GcateServiceImpl;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * @Route("/gcate")
 */
class GoodCateController extends CommonController
{

    /**
     * 商品分类列表页
     * @Route("/list.html")
     */
    public function gCateList(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        $gc_id = $request->get('gc_id');
        $gc_node = $request->get('gc_node');
        $conditions = $this->ajaxRequest();
        if($gc_id){
            $conditions['gc_id'] = $gc_id;
            $res = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gc_id);
            $gc_node = $res->getGcNode();
        }
        if($gc_node){
            $conditions['gc_node'] = $gc_node;
        }else{
            $conditions['gc_node'] = 0;
        }
        if($gc_node > 0){
            $nodeInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gc_node);
            if($gc_id){
                $nodeInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gc_id);
                $gc_order = $nodeInfo->getGcOrder()-1;
            }else{
                $gc_order = $nodeInfo->getGcOrder();
            }
        }else{
            $gc_order = 0;
        }
        $conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
	    $goodCateList = $this->getGcateService()->getAllGcates($conditions);
        $page = $this->get('page_service');
        $page->setPage($goodCateList['count'], $request->get('p', '1'), true, 'gCateList');//设置为ajax链接
	    return $this->show(
		    'good_cate/good_cate_list',
		    [
			    'tabid' => 'gCateList',
			    'gcate_list' => $goodCateList['data'],
			    'upInfo' => $goodCateList['upInfo'],
			    'page' => $page->show(),
			    'params' => $conditions,
                'gc_order' => $gc_order,
                'gc_upId' => $gc_node,
                'gc_uid' => $gc_id
		    ]
	    );
    }

	/**
	 * 商品分类编辑页-添加下级
	 * @Route("/edit_gcate/{gcId}.html",defaults={"gcId":0},requirements={"gcId":"\d+"})
	 */
	public function editAction(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
		if(strtolower($request->getMethod()) == 'post'){
            $gc_node = $request->get('gc_upNode') ? $request->get('gc_upNode') : $request->get('gc_node');
            $p_id = $request->get('p_id') ? $request->get('p_id') : '';
            $gc_name = trim($request->get('gc_name'));
            $gc_id = $request->get('gc_id');
            $gc_order = $request->get('gc_order');
            $gc_remark = trim($request->get('gc_remark'));
            $gc_status = $request->get('gc_status');
            $att_total = $request->get('att_total');
            $gc_img = $request->get('gc_img') ? $request->get('gc_img') : '';
            $attribute_goods = [];
            for ($i=1;$i<=$att_total;$i++){
                if($request->get('gc_attribute'.$i)){
                    $attribute_goods[] = $request->get('gc_attribute'.$i);
                }
            }
            if(empty($gc_name)){
                return $this->parseData(['msg'=>'分类名称不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            if(strlen($gc_name) > 60){
                return $this->parseData(['msg'=>'分类名称长度不能超过20个字','code'=>500,'closeCurrTab'=>false]);
            }
            $gcNameInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->findOneBy(['gcName'=>$gc_name]);
            $currId = $gcNameInfo ? $gcNameInfo->getGcId() : '';
            if($gc_id != $currId){
                if($gcNameInfo){
                    return $this->parseData(['msg'=>'分类名称已存在!','code'=>500,'closeCurrTab'=>false]);
                }
            }
            if($gc_order == 1){
                if(empty($p_id)){
                    return $this->parseData(['msg'=>'一级分类必须选择产品信息!','code'=>500,'closeCurrTab'=>false]);
                }
                if(empty($attribute_goods[0])){
                    return $this->parseData(['msg'=>'请添加属性信息!','code'=>500,'closeCurrTab'=>false]);
                }
                if(empty($gc_img)){
                    return $this->parseData(['msg'=>'请上传分类图片!','code'=>500,'closeCurrTab'=>false]);
                }
                $gc_node =  0;
            }
            if(empty($gc_remark)){
                return $this->parseData(['msg'=>'备注信息不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            if(empty($gc_status)){
                return $this->parseData(['msg'=>'请选择分类状态','code'=>500,'closeCurrTab'=>false]);
            }
            $em = $this->getDoctrine()->getManager();
            $gcInfo = null;
            if($gc_id){
                $gcInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gc_id);
            }
            if(empty($gcInfo)){
                $gcInfo = new YsGoodsCate();
                $gcInfo->setGcAddTime(new \DateTime(date('Y-m-d H:i:s')));
            }
            $gcInfo->setGcName($gc_name);
            $gcInfo->setGcNode($gc_node);
            $gcInfo->setGcOrder($gc_order);
            $gcInfo->setPId($p_id);
            $gcInfo->setAdminId($this->getUser()->getAId());
            $gcInfo->setGcUpdateTime(new \DateTime(date('Y-m-d H:I:s')));
            $gcInfo->setGcAttribute(implode(',',$attribute_goods));
            $gcInfo->setGcRemark($gc_remark);
            $gcInfo->setGcStatus($gc_status);
            $img = $gc_img ? $gc_img : '';
            $gcInfo->setGcImg($img);
            $em->persist($gcInfo);
            $em->flush();
            $openUrl = "/gcate/list.html?gc_node=".$gc_node;
            return $this->parseData(['msg'=>'操作成功','openUrl'=>$openUrl,'code'=>'200']);
		}else{
            $gcOder = $request->get('gcOder');
            $gcUpId = $request->get('gcUpId');
            if($gcUpId > 0){
                $gcate_upinfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcUpId);
            }
            if($gcOder == 2){
                $gcTopId = $gcate_upinfo->getGcNode();
                $gcateTopinfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcTopId);
            }
            $gcId = $request->get('gcId');
            $gcate_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->findOneBy(['gcId'=>$gcId]);
            $gcAttribute = '';
            if($gcate_info){
                $gcAttribute = explode(',',$gcate_info->getGcAttribute());
                $att_total = count($gcAttribute);
            }
            if($gcOder){
                $gcOder = $gcOder+1;
            }else{
                $gcOder = 1;
            }
            //获取审批系统对应产品信息
            $emA = $this->get('doctrine')->getManager('fenqi');
            $sql = "select pr_id from my_product_cate where pr_sign=2";
            $res = $emA->getConnection()->fetchAll($sql);
            $pr_id = '';
            foreach ($res as $key=>$val){
                $pr_id .=$val['pr_id'].',';
            }
            if(rtrim($pr_id,',') == ''){
                echo "<script>alert('请先添加审批系统项目');setTimeout(function(){close_tab();},1500)</script>";exit;
            }
            $sql = "SELECT p_id,pr_id,p_name from my_product WHERE pr_id in(".rtrim($pr_id,',').") and p_status=1";
            $proInfo = $emA->getConnection()->fetchAll($sql);
			return $this->show(
				'good_cate/edit_cate',
				[
					'product_info' => $proInfo,
					'gcate_info' => $gcate_info,
                    'gcOder' => $gcOder,
					'gc_attribute' => $gcAttribute,
					'att_total' => $att_total,
					'gcate_upinfo' => $gcate_upinfo,
					'gcate_topinfo' => $gcateTopinfo,
				]
			);
		}
	}

    /**
     * 商品分类添加/编辑下级-页面展示-操作公用添加顶级分类操作--暂时没用
     * @Route("/add_lower_level/{gcId}.html",defaults={"gcId":0},requirements={"gcId":"\d+"})
     */
	public function addAction(Request $request){
			$gcId = $request->get('gcId');
            $gcate_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->findOneBy(['gcNode'=>0,'gcId'=>$gcId]);
            $upGcId = '';
            if($gcate_info){
                $upGcId = $gcId;
                $gcate_info = '';
            }else{
                $gcate_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcId);
                $upGcId = $gcate_info ? $gcate_info->getGcNode() : '';
            }
            //获取所有顶级分类
            $top_cate_info = $this->getGcateService()->getAllGcates();
            //获取审批系统对应产品信息
            $emA = $this->get('doctrine')->getManager('fenqi');
            $sql = "select pr_id from my_product_cate where pr_sign=2";
            $res = $emA->getConnection()->fetchAssoc($sql);
            $sql = "SELECT p_id,pr_id,p_name from my_product WHERE pr_id=".$res['pr_id']." and p_status=1";
            $proInfo = $emA->getConnection()->fetchAll($sql);
			return $this->show(
				'good_cate/edit_cate',
				[
					'product_info' => $proInfo,
					'upGcId' => $upGcId,
					'add_lower' => 1,
                    'gcate_info' => $gcate_info,
					'top_cate_info' => $top_cate_info['data'],
				]
			);
	}

    /**
     * 获取商品分类
     * @Route("/cate_options.html")
     */
    public function getCates(Request $request){
        $cate_id      = $request->get("cate_id",0);
        $service = $this->createService('Gcate.GcateService');
        $lowerLevel = $service->getLowerLevelInfo($cate_id);
        $options = [];
        foreach ($lowerLevel as $key => $value) {
            $row = [];
            $row['gc_id'] = $value['gc_id'];
            $row['gc_name'] = $value['gc_name'];
            $options[] = $row;
        }
        return $this->parseData(['options'=>$options]);
    }

    /**
     * 商品分类排序
     * @Route("/gcate_sort.html")
     */
    public function editSort(Request $request){
        $gc_id = $request->get('gc_id');
        $gc_sort = trim($request->get('gc_sort'));
        $num_preg = '/^\d+$/';
        if(!preg_match($num_preg, $gc_sort)){
            return $this->parseData(['msg'=>'<font color="red">请输入正确的排序数值!</font>','closeCurrTab'=>false]);
        }
        $gcateInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gc_id);
        if(empty($gcateInfo)){
            echo "<script>alert('没有该分类');</script>";exit;
        }
        $em = $this->getDoctrine()->getManager();
        $gcateInfo->setGcSort($gc_sort);
        $em->persist($gcateInfo);
        $em->flush();
        return $this->parseData(['msg'=>'排序成功','openUrl'=>'/gcate/list.html','title'=>'商品分类列表']);
    }

    /**
     * @return GcateServiceImpl
     */
    private function getGcateService(){
        return $this->get('application_service')->getService('Gcate.GcateService');
    }

}
