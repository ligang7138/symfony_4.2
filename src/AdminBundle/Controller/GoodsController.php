<?php
namespace App\AdminBundle\Controller;

use App\AdminBundle\Common\CommonFunction;
use App\AdminBundle\Entity\YsGoods;
use App\AdminBundle\Entity\YsGoodsBrand;
use App\AdminBundle\Entity\YsGoodsSpecPrice;
use App\AdminBundle\Entity\YsPartnerDaturm;
use App\AdminBundle\Services\Goods\Impl\GoodsServiceImpl;
use App\AdminBundle\Services\Partner\Impl\PartnerServiceImpl;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/goods")
 */
class GoodsController extends CommonController
{

	/**
	 * 商品列表页
	 * @Route("/goods_list.html")
	 */
    public function goodsList(Request $request){
        $conditions = $this->ajaxRequest();
	    $conditions['partner_id'] = intval($request->getSession()->get('partner_id'));
        $conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
        $goodsList = $this->getGoodsService()->getGoodsList($conditions);
        // 获取商品品牌信息
        $brand_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsBrand')->findBy(['gbStatus'=>1]);
        // 获取商品分类信息
        $service = $this->createService('Gcate.GcateService');
        $lowerLevel = $service->getAllGcates(['gc_order'=>'all']);
        $page = $this->get('page_service');
        $page->setPage($goodsList['count'], $request->get('p', '1'), true, 'goodsList');
        $status = [
            ['key'=>'-1','label'=>'全部'],
            ['key'=>'0','label'=>'未上架'],
            ['key'=>'6','label'=>'下架'],
            ['key'=>'5','label'=>'上架']
        ];

        $check_status = [
            ['key'=>'-1','label'=>'全部'],
            ['key'=>'1-4','label'=>'待审核'],
            ['key'=>'2-5-6','label'=>'审核通过'],
            ['key'=>'3','label'=>'审核打回'],
            ['key'=>'7','label'=>'审核拒绝']
        ];

        return $this->show('goods/goods_list',
            [
                'tabid' => 'goodsList',
                'brand_info' => $brand_info,
                'lower_level' => $lowerLevel,
                'status' => $status,
                'check_status' => $check_status,
                'goods_list' => $goodsList['data'],
                'page' => $page->show(),
                'pageInfo' => $page->getPageInfo(),
                'params' => $conditions,
                'atype' => $this->getUser()->getAType(),
            ]
        );
    }

	/**
	 * 商品详情页
	 * @Route("/goods_detail/{gId}.html",defaults={"gId":0},requirements={"gId":"\d+"})
	 */
	public function detailAction(Request $request){
        $gId = $request->get('gId');
        $goodsData = $this->getGoodsService()->getGoodsInfo($gId);
        if(empty($goodsData)){
            return $this->parseData(['msg'=>'商品信息有误','code'=>500,'closeCurrTab'=>true]);
        }
        $gcId = $goodsData['gc_id'];//三级
        $gcInfos = $this->getALlCateInfo($gcId);
        //规格单位库存量
        $gn_ids = $goodsData['g_standard'];
        $goodsSpec = $this->getGoodsService()->getGoodsStandard($gn_ids);

        if(count($goodsSpec) == 1){
            $show_price = sprintf("%1.2f",$goodsSpec[0]['gn_price']);
        }else{
            $prices = array_column($goodsSpec,'gn_price');
            $show_price = sprintf("%1.2f",min($prices)).'起';
        }
        // 获取商品图片信息
		/** @var PartnerServiceImpl $pimgService */
        $pimgService = $this->createService('Partner.PartnerService');
        $goods_image = $pimgService->getDaturms($gId,'d');
        //获取商品属性
        $gc_attribute = explode(',',$gcInfos['firstInfo']->getGcAttribute());
        $g_attribute = explode(',',$goodsData['g_attribute']);
        $goods_check =$pimgService->getCheckLog($gId, 2);
        return $this->show('goods/goods_details',
            [
                'goods_data' => $goodsData,
                'goods_spec' => $goodsSpec,
                'gId' => $gId,
                'show_price' => $show_price,
                'gcInfos' => $gcInfos,
                'gc_attribute' => $gc_attribute,
                'g_attribute' => $g_attribute,
                'goods_image' => $goods_image,
                'results' => $goods_check['data'],
            ]
        );
	}

    /**
     * 上下架操作
     * @Route("/update_status.html")
     */
    public function upGoodsStatus(Request $request){
        $gId = $request->get('gId');
        $goods_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find($gId);
        if(empty($goods_info)){
            return $this->parseData(['msg'=>'商品信息有误','code'=>500]);
        }
        $curr_status = $goods_info->getGStatus();
        if(!in_array($curr_status,[2,5,6])){
            return $this->parseData(['msg'=>'该状态不允许修改','code'=>500]);
        }
        if(in_array($curr_status,[2,6])){
            $new_status = 5;
        }elseif($curr_status == 5){
            $new_status = 6;
        }
        $em = $this->getDoctrine()->getManager();
        $goods_info->setGStatus($new_status);
        $goods_info->setGUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
        $em->persist($goods_info);
        $em->flush();
        return $this->parseData(['msg'=>'操作成功','code'=>200,'new_status'=>$new_status,'openUrl'=>'/goods/goods_list.html']);
    }

    /**
     * 品牌列表
     * @Route("/brands.html")
     */
    public function brands(Request $request){
        //商品品牌
        $brandInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsBrand')->findBy(['gbStatus'=>1]);
        return $this->parseData([
            'options' => self::object2array($brandInfo)
        ]);
    }

	/**
	 * 添加/编辑商品信息
	 * @Route("/edit_goods/{gId}.html",defaults={"gId":0},requirements={"gId":"\d+"})
	 */
	public function editGoodsInfo(Request $request){
		if(strtolower($request->getMethod()) == 'post'){
		    $goodsImg = $request->get('goodsImg');
            $g_id = trim($request->get('g_id'));
            $gc_first_id = trim($request->get('gc_first_id'));
            $gc_second_id = trim($request->get('gc_second_id'));
            $gc_three_id = trim($request->get('gc_three_id'));
            $g_name = trim($request->get('g_name'));
            $gb_id = trim($request->get('gb_id'));
            $g_code = trim($request->get('g_code'));
            $g_desc = trim($request->get('g_desc'));
            $is_join_activity = trim($request->get('is_join_activity'));
            $g_standard = trim($request->get('g_standard'));
            $g_order_num = trim($request->get('g_order_num'));
            $total_attr = trim($request->get('total_attr'));
            $goodsHeadImg = trim($request->get('goodsHeadImg'));
            $g_attr = [];
            for ($i=0;$i<$total_attr;$i++){
                $g_attr[$i] = trim($request->get('gc_attribute'.$i));
                if(empty($g_attr[$i])){
                    return $this->parseData(['msg'=>'商品属性不能为空','code'=>500,'closeCurrTab'=>false]);
                }
            }
            $g_status = trim($request->get('g_status'));
            if(empty($gc_first_id)){
                return $this->parseData(['msg'=>'请选择商品一级分类','code'=>500,'closeCurrTab'=>false]);
            }
            if(empty($g_name)){
                return $this->parseData(['msg'=>'商品名称不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            if(strlen($g_name) > 60){
                return $this->parseData(['msg'=>'商品名称长度不能超过20个字','code'=>500,'closeCurrTab'=>false]);
            }

            if(0==$this->getUser()->getAType()){
                $partnerInfo = $this->getDoctrine()->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId'=>$this->getUser()->getAId()]);
                $partner_id = $partnerInfo->getPartnerId();
                $gNameInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->findOneBy(['gName'=>$g_name,'partnerId'=>$partner_id]);
                $currId = $gNameInfo ? $gNameInfo->getGId() : '';
                if($g_id != $currId){
                    if($gNameInfo){
                        return $this->parseData(['msg'=>'商品名称已存在!','code'=>500,'closeCurrTab'=>false]);
                    }
                }
            }
//            if(empty($gb_id)){
//                return $this->parseData(['msg'=>'请选择商品品牌','code'=>500,'closeCurrTab'=>false]);
//            }
            if(empty($goodsHeadImg)){
                return $this->parseData(['msg'=>'请上传商品头图','code'=>500,'closeCurrTab'=>false]);
            }
            if($g_id == ''){
                if(empty($goodsImg[0])){
                    return $this->parseData(['msg'=>'请上传商品图片','code'=>500,'closeCurrTab'=>false]);
                }
                if(count($goodsImg) > 3){
                    return $this->parseData(['msg'=>'商品图片最多限制3张','code'=>500,'closeCurrTab'=>false]);
                }
            }
            if(empty($g_desc)){
                return $this->parseData(['msg'=>'商品备注信息不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            if(strlen($g_desc) > 300){
                return $this->parseData(['msg'=>'商品备注信息不能超过100个字','code'=>500,'closeCurrTab'=>false]);
            }
            if(empty($is_join_activity)){
                return $this->parseData(['msg'=>'请选择商品是否参加活动','code'=>500,'closeCurrTab'=>false]);
            }
            $em = $this->getDoctrine()->getManager();
            $curr_status = '';$goods_img = '';
			$goods = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find(intval($g_id));
            if($goods){
                $curr_status = $goods->getGStatus();
                $goods_img = $goods->getGImgs();
	            if(0==$this->getUser()->getAType()){
		            if(!in_array($curr_status,[2,3,6,5])){
			            return $this->parseData(['msg'=>'该商品当前不可编辑','code'=>500,'closeCurrTab'=>false]);
		            }
	            }else{
	                if($curr_status == 7){
                        return $this->parseData(['msg'=>'该商品当前不可编辑','code'=>500,'closeCurrTab'=>false]);
                    }
                }
            }else{
	            $goods = new YsGoods();
	            $goods->setGAddTime(new \DateTime(date('Y-m-d H:i:s')));
            }
            if($curr_status == '' || $curr_status == 3){
                if(empty($g_status)){
                    return $this->parseData(['msg'=>'请选择商品上架时间','code'=>500,'closeCurrTab'=>false]);
                }
                if(!in_array($g_status,[1,4])){
                    return $this->parseData(['msg'=>'商品上架时间选项有误','code'=>500,'closeCurrTab'=>false]);
                }
            }else{
                $g_status = $curr_status;
            }
            $g_code = $g_code ? $g_code : 'G'.date('ymd').substr(microtime(),3,4).mt_rand(10,99);
            $goods->setGCode($g_code);
            if($gc_three_id > 0){
                $gcId = $gc_three_id;
            }elseif($gc_second_id > 0){
                $gcId = $gc_second_id;
            }else{
                $gcId = $gc_first_id;
            }
            $goods->setGcId($gcId);
            $goods->setAdminId($this->getUser()->getAId());
            if($this->getUser()->getAType() == 0){
	            $partnerInfo = $this->getDoctrine()->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId'=>$this->getUser()->getAId()]);
	            $partner_id = $partnerInfo->getPartnerId();
                $goods->setPartnerId($partner_id);
            }
            $goods->setGbId($gb_id);
            $goods->setGName($g_name);
            $goods->setGImgs($goodsHeadImg);
            $goods->setGDesc($g_desc);
            $goods->setGStandard(rtrim($g_standard,','));
			$goods->setGStatus($g_status);
            $goods->setGUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
            $goods->setIsJoinActivity($is_join_activity);
            $goods->setGAttribute(implode(',',$g_attr));
            $goods->setGOrderNum($g_order_num);
            $goods->setGcTopId($gc_first_id);
            $em->persist($goods);
            $em->flush();
            CommonFunction::wlog($goods,'goods/');
            foreach ($goodsImg as $k => $v){
                $goos_img = new YsPartnerDaturm();
                $goos_img->setPdAddTime(new \DateTime(date('Y-m-d H:i:s')));
                $goos_img->setPartnerId($goods->getGId());
                $goos_img->setAdminId($this->getUser()->getAId());
                $goos_img->setPdType('d');
                $goos_img->setPdUrl($v);
                $em->persist($goos_img);
                $em->flush();
                CommonFunction::wlog($goos_img,'goos_img/');
            }
            $this->addMessage($partner_id,'商品审核','尊敬的商户，您添加的'.$g_name.'商品已提交审核，请等待审核结果！');
            return $this->parseData(['msg'=>'操作成功','code'=>200,'openUrl'=>'/goods/goods_list.html','title'=>'商品列表']);
		}else{
            $atype = $this->getUser()->getAType();
            if($atype == 0){
                $partnerInfo = $this->getDoctrine()->getRepository('AdminBundle:YsPartners')->findOneBy(['adminId'=>$this->getUser()->getAId()]);
                $is_normal = '';
                if(empty($partnerInfo)){
                    echo "<script>alert('店铺未激活不能添加商品');setTimeout(function(){close_tab();},1500)</script>";exit;
                }else{
                    $is_normal = $partnerInfo->getIsNormal();
                    if($is_normal == 1){
                        echo "<script>alert('店铺非正常营业，不能添加商品');setTimeout(function(){close_tab();},1500)";exit;
                    }
                }
            }
            $gId = $request->get('gId');
            if($gId == 0){
                if($this->forbidAdminAccess()){
                    return $this->_404();
                }
            }
			$goodsData = $this->getDoctrine()->getManager()->getRepository('AdminBundle:YsGoods')->findOneBy(['gId' => $gId]);
			if($goodsData){
			    $gcId = $goodsData->getGcId();//三级
                $gcInfos = $this->getALlCateInfo($gcId);
                //规格单位库存量
                $gn_ids = $goodsData->getGStandard();
                $goodsSpec = $this->getGoodsService()->getGoodsStandard($gn_ids);
            }
            // 获取商品一级分类信息
            $topLevel = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->findBy(['gcNode'=>0,'gcStatus'=>1]);
            //商品品牌
            $brandInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsBrand')->findBy(['gbStatus'=>1]);
            // 获取商品图片信息
            $pimgService = $this->createService('Partner.PartnerService');
            $goods_image = $pimgService->getDaturms($gId,'d');
			return $this->show('goods/add_goods',
				[
					'goods_data' => $goodsData,
					'top_level' => $topLevel,
					'brand_info' => $brandInfo,
					'goods_spec' => $goodsSpec,
                    'gId' => $gId,
                    'gcId' => $gcId,
					'gcInfos' => $gcInfos,
					'atype' => $atype,
					'goods_image' => $goods_image
				]
			);
		}
	}

    /**
     * 通过分类id获取三级信息
     */
    private function getALlCateInfo($gcId){
        $firstId = $secongId = $threeId = '';
        $firstInfo = $secongInfo = $threeInfo = [];
        $gcInfo1 = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcId);
        $gcId1 = $gcInfo1->getGcNode();
        if($gcId1 == 0){
            $firstId = $gcId;
            $firstInfo = $gcInfo1;
        }else{
            $gcInfo2 = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcId1);
            $gcId2 = $gcInfo2->getGcNode();
            if($gcId2 == 0){
                $firstId = $gcId1;
                $firstInfo = $gcInfo2;
                $secongId = $gcId;
                $secongInfo = $gcInfo1;
            }else{
                $gcInfo3 = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcId2);
                $gcId3 = $gcInfo3->getGcNode();
                if($gcId3 == 0){
                    $firstId = $gcId2;
                    $firstInfo = $gcInfo3;
                    $secongId = $gcId1;
                    $secongInfo = $gcInfo2;
                    $threeId = $gcId;
                    $threeInfo = $gcInfo1;
                }
            }
        }
        return ['firstId'=>$firstId,'firstInfo'=>$firstInfo,'secongId'=>$secongId,'secongInfo'=>$secongInfo,'threeId'=>$threeId,'threeInfo'=>$threeInfo];
    }

    /**
     * 添加/编辑规格信息
     * @Route("/edit_spec_price.html")
     */
    public function editSpecpriceInfo(Request $request){
        if(strtolower($request->getMethod()) == 'post'){
            $gn_id = $request->get('gn_id');
            $gn_spec_type = trim($request->get('gn_spec_type'));
            $gn_spec_num = trim($request->get('gn_spec_num'));
            $gn_spec_unit = trim($request->get('gn_spec_unit'));
            $gn_price = trim($request->get('gn_price'));
            $gn_stock = trim($request->get('gn_stock'));
            $gn_stock_remind = trim($request->get('gn_stock_remind'));
            $act = empty($gn_id) ? 'add' : 'edit';
            if($gn_spec_type < 0){
                return $this->parseData(['msg'=>'请选择规格类型','code'=>500,'closeCurrTab'=>false,'act'=>$act]);
            }
            if(!is_numeric($gn_spec_num) || $gn_spec_num < 0){
                return $this->parseData(['msg'=>'请输入规格数值','code'=>500,'colseCurrTab'=>false,'act'=>$act]);
            }
            if($gn_spec_unit < 0){
                return $this->parseData(['msg'=>'请选择规格单位','code'=>500,'colseCurrTab'=>false,'act'=>$act]);
            }
            if($gn_price < 0){
                return $this->parseData(['msg'=>'请输入单价','code'=>500,'colseCurrTab'=>false,'act'=>$act]);
            }
            if(!is_numeric($gn_stock) || $gn_stock < 0){
                return $this->parseData(['msg'=>'请输入库存值','code'=>500,'colseCurrTab'=>false,'act'=>$act]);
            }
            if(!is_numeric($gn_stock_remind) || $gn_stock_remind < 0){
                return $this->parseData(['msg'=>'请输入库存提醒值','code'=>500,'colseCurrTab'=>false,'act'=>$act]);
            }
            $em = $this->getDoctrine()->getManager();
            $spec_price = null;
            if($gn_id){
                $spec_price = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsSpecPrice')->find($gn_id);
            }
            if(empty($spec_price)){
                $spec_price = new YsGoodsSpecPrice();
                $spec_price->setGnAddTime(new \DateTime(date('Y-m-d H:i:s')));
                $curr_total_stock = $gn_stock;
            }else{
                $curr_stock = $spec_price->getGnStock();// 剩余
                $curr_total_stock = $spec_price->getGnTotalStock();// 总
                if($gn_stock > $curr_stock){
                    $curr_total_stock += $gn_stock-$curr_stock;
                }elseif($gn_stock < $curr_stock){
                    $curr_total_stock -= $curr_stock-$gn_stock;
                }
            }
            $spec_num = $gn_spec_num.'/'.$gn_spec_unit;
            $spec_price->setGnSpecType($gn_spec_type);
            $spec_price->setGnSpecNum($spec_num);
            $spec_price->setGnPrice($gn_price);
            $spec_price->setGnStock($gn_stock);
            $spec_price->setGnTotalStock($curr_total_stock);
            $spec_price->setGnStockRemind($gn_stock_remind);
            $spec_price->setGnUpdateTime(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($spec_price);
            $em->flush();
            $gn_id = $spec_price->getGnId();
            $dataArr = [];
            $dataArr['gn_id'] = $gn_id;
            if($gn_spec_type == 1){
                $dataArr['gn_spec_type'] = '重量';
            }elseif($gn_spec_type == 2){
                $dataArr['gn_spec_type'] = '容量';
            }
            $dataArr['gn_spec_type_value'] = $gn_spec_type;
            $dataArr['gn_spec_num'] = $gn_spec_num.$gn_spec_unit.'/件';
            $dataArr['gn_price'] = $gn_price;
            $dataArr['gn_stock'] = $curr_total_stock;
            $dataArr['curr_stock'] = $gn_stock;
            $dataArr['gn_stock_remind'] = $gn_stock_remind;
            return $this->parseData(['msg'=>'操作成功','code'=>'200','data'=>$dataArr,'act'=>$act,'gn_id'=>$gn_id]);
        }else{
            $gn_id = $request->get('gn_id');
            $spec_price = null;
            if($gn_id != 'add'){
                $spec_price = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsSpecPrice')->find($gn_id);
            }
            $str = $spec_price ? $spec_price->getGnSpecNum() : '';
            $arr = explode('/',$str);
            $gn_spec_num = $arr[0];
            $gn_spec_unit = $arr[1];
            return $this->show('goods/add_spec_price',['gn_id'=>$gn_id,'spec_price'=>$spec_price,'gn_spec_num'=>$gn_spec_num,'gn_spec_unit'=>$gn_spec_unit]);
        }
    }

    /**
     * 删除规格信息
     * @Route("/del_spec_price.html")
     */
    public function delSpecPrice(Request $request){
        $gn_id = ltrim($request->get('gnId'),',');
        $res = $this->getGoodsService()->delSpecPriceInfo($gn_id);
        if($res){
            return $this->parseData(['msg'=>'删除成功','code'=>'200']);
        }else{
            return $this->parseData(['msg'=>'删除失败','code'=>'500']);
        }
    }

    /**
     * 下级列表
     * @Route("/get_lower_level.html")
     */
    public function getlowerLevel(Request $request){
        $v      = $request->get("v");
        $select      = $request->get("select");
        $service = $this->createService('Gcate.GcateService');
        $lowerLevel = $service->getLowerLevelInfo($select);
        $html = '';
        foreach ($lowerLevel as $key => $value) {
            if($v == $value['gc_id']){
                $html .= '<option value="'.$value['gc_id'].'" selected>'.$value['gc_name'].'</option>';
            }else{
                $html .= '<option value="'.$value['gc_id'].'">'.$value['gc_name'].'</option>';
            }
        }
        echo $html;exit();
    }

    /**
     * 获取一级分类属性信息
     * @Route("/get_attribute_info.html")
     */
    public function getAttributeInfo(Request $request){
        $gcId = $request->get('select');
        $gId = $request->get('v');
        if($gId > 0){
            $goods = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find($gId);
            $g_attribute = explode(',',$goods->getGAttribute());
        }

        if($gcId){
            $gcate_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcId);
            $gcAttribute = explode(',',$gcate_info->getGcAttribute());
        }
        $html = '<input type="hidden" name="total_attr" value="'.count($gcAttribute).'">';
        foreach ($gcAttribute as $key=>$val){
            $html .= '<div class="form-bolck col-sm-6 padding-bottom-20">
                                            <label class="col-sm-4 control-label text-right"><span class="must">*</span>'.$val.':</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="gc_attribute'.$key.'" class="form-control" placeholder="请输入"  value="'.$g_attribute[$key].'">
                                            </div>
                                        </div>';
        }
        echo $html;exit();
    }

    /**
     * 获取一级分类属性信息接口
     * @Route("/get_attribute_data.html")
     */
    public function getAttributeData(Request $request){
        $gcId = $request->get('gc_id');
        $gId = $request->get('g_id');
        if($gId > 0){
            $goods = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find($gId);
            $g_attribute = explode(',',$goods->getGAttribute());
        }

        if($gcId){
            $gcate_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsCate')->find($gcId);
            $gcAttribute = explode(',',trim($gcate_info->getGcAttribute(),','));
        }

        $attributes = [];
        $attributes[] = [
            'name' => 'total_attr',
            'type' => 'hidden',
            'value' => count($gcAttribute),
        ];

        foreach ($gcAttribute as $key=>$val){
            $attributes[] = [
                'name' => 'gc_attribute'.$key,
                'type' => 'text',
                'label' => $val,
                'value' => isset($g_attribute[$key])?$g_attribute[$key]:'',
            ];

        }
        return $this->parseData([
            'data' => $attributes
        ]);
    }

    /**
     * 商品审核
     * @Route("/check_goods.html")
     */
    public function checkGoodsInfo(Request $request){
        if(strtolower($request->getMethod()) == 'post'){
            $g_id = trim($request->get('g_id'));
            $g_check_status = trim($request->get('g_check_status'));
            $g_check_remark = trim($request->get('g_check_remark'));
            if(empty($g_check_status)){
                return $this->parseData(['msg'=>'请选择审核状态','code'=>500,'closeCurrTab'=>false]);
            }
            if(empty($g_check_remark)){
                return $this->parseData(['msg'=>'审核反馈不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            $goods_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find($g_id);
            if(empty($goods_info)){
                return $this->parseData(['msg'=>'商品信息不存在','code'=>500,'closeCurrTab'=>true]);
            }
            if(!in_array($g_check_status,[1,2,3])){
	            return $this->parseData(['msg'=>'商品审核状态选项有误','code'=>500,'closeCurrTab'=>true]);
            }

            $before_status = $goods_info->getGStatus();
            $partner_id = $goods_info->getPartnerId();
            if(in_array($before_status,[2,3,7])){
	            return $this->parseData(['msg'=>'该商品已审核过,请勿重复操作','code'=>500,'closeCurrTab'=>true]);
            }

	        $g_status = 2;//审核通过
            if($g_check_status == 2){
	            $g_status = 3;//审核打回重新编辑
            }else if($g_check_status == 3){
	            $g_status = 7;//审核拒绝
            }
            if(1==$g_check_status && 4==$before_status){
	            $g_status=5;//上架
            }
            $em = $this->getDoctrine()->getManager();
            $goods_info->setGStatus($g_status);
            $goods_info->setGCheckStatus($g_check_status);
            $goods_info->setGCheckRemark($g_check_remark);
            $goods_info->setGCheckTime(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($goods_info);
            $em->flush();
            // 商品审核记录操作日志
            $check_info = '';
            if($g_check_status == 1){
                $check_info .= "审核结果：通过,";
            }elseif($g_check_status == 3){
                $check_info .= "审核结果：拒绝,";
            }else{
                $check_info .= "审核结果：打回(补充资料),";
            }
            $check_info .= "审核反馈：".$g_check_remark;
            $content = $goods_info->getGName()."于".date('Y-m-d H:i:s')."已经审核,".$check_info;
            $this->behavior_trace($goods_info->getGId(), $check_info, 2);// 记录操作日志用.
            $this->addMessage($partner_id,'商品审核',$content);
            return $this->parseData(['msg'=>'操作成功','title'=>'商品列表','openUrl'=>'/goods/goods_list.html']);
        }else{
            $g_id = $request->get('g_id');
            $goods_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find($g_id);
            return $this->show("goods/goods_check",['goods_info'=>$goods_info,'g_id'=>$g_id]);
        }
    }

    /**
     * 商品品牌列表页
     * @Route("/brand_list.html")
     */
    public function gBrandList(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        $conditions = $this->ajaxRequest();
	    $conditions['partner_id'] = intval($request->getSession()->get('partner_id'));
        $conditions['limit'] = $this->pageLimit($request->request->get('p', 1));
        $brandList = $this->getGoodsService()->getGBrandList($conditions);
        $page = $this->get('page_service');
        $page->setPage($brandList['count'], $request->get('p', '1'), true, 'gBrandList');//设置为ajax链接
        return $this->show(
            'goods/goods_brand_list',
            [
                'tabid' => 'gBrandList',
                'brand_list' => $brandList['data'],
                'page' => $page->show(),
                'params' => $conditions,
            ]
        );
    }

    /**
     * 商品品牌添加/编辑
     *@Route("/edit_brand.html")
     */
    public function editBrandInfo(Request $request){
        if($this->forbidPartnerAccess()){
            return $this->_404();
        }
        if(strtolower($request->getMethod()) == 'post'){
            $gb_id = trim($request->get('gb_id'));
            $gb_name = trim($request->get('gb_name'));
            $gb_maker = trim($request->get('gb_maker'));
            $gb_status = trim($request->get('gb_status'));
            if(empty($gb_name)){
                return $this->parseData(['msg'=>'品牌名称不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            if(strlen($gb_name) > 60){
                return $this->parseData(['msg'=>'品牌名称长度不能超过20个字','code'=>500,'closeCurrTab'=>false]);
            }
            $gbNameInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsBrand')->findOneBy(['gbName'=>$gb_name]);
            $currId = $gbNameInfo ? $gbNameInfo->getGbId() : '';
            if($gb_id != $currId){
                if($gbNameInfo){
                    return $this->parseData(['msg'=>'品牌名称已存在','code'=>500,'closeCurrTab'=>false]);
                }
            }
            if(empty($gb_maker)){
                return $this->parseData(['msg'=>'品牌制造商不能为空','code'=>500,'closeCurrTab'=>false]);
            }
            if(strlen($gb_maker) > 60){
                return $this->parseData(['msg'=>'品牌制造商长度不能超过20个字','code'=>500,'closeCurrTab'=>false]);
            }
            if(empty($gb_status)){
                return $this->parseData(['msg'=>'请选择品牌状态','code'=>500,'closeCurrTab'=>false]);
            }
            $gbrand_info = null;
            $gb_code = '';
            if($gb_id){
                $gbrand_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsBrand')->find($gb_id);
                $gb_code = $gbrand_info->getGbCode();
            }
            if(empty($gbrand_info)){
                $gbrand_info = new YsGoodsBrand();
                $gbrand_info->setGbAddTime(new \DateTime(date('Y-m-d H:i:s')));
            }
            $gb_code = $gb_code ? $gb_code :'B'.substr(microtime(),3,4).mt_rand(10,99);
            $em = $this->getDoctrine()->getManager();
            $gbrand_info->setGbCode($gb_code);
            $gbrand_info->setGbName($gb_name);
            $gbrand_info->setGbMaker($gb_maker);
            $gbrand_info->setGbStatus($gb_status);
            $gbrand_info->setAdminId($this->getUser()->getAId());
            $em->persist($gbrand_info);
            $em->flush();
            return $this->parseData(['msg'=>'操作成功','title'=>'商品品牌列表','openUrl'=>'/goods/brand_list.html']);
        }else{
            $gbId = $request->get('gb_id');
            if($gbId){
                $brand_info = $this->getDoctrine()->getRepository('AdminBundle:YsGoodsBrand')->find($gbId);
            }else{
                $brand_info = '';
            }
            return $this->show('goods/edit_brand',['brand_info'=>$brand_info,'gb_id'=>$gbId]);
        }
    }

    /**
     * 商品排序
     * @Route("/goods_sort.html")
     */
    public function editSort(Request $request){
        $g_id = $request->get('g_id');
        $g_sort = trim($request->get('g_sort'));
        $num_preg = '/^\d+$/';
        if(!preg_match($num_preg, $g_sort)){
            return $this->parseData(['msg'=>'请输入正确的排序数值!','code'=>500,'closeCurrTab'=>false]);
        }
        $goodsInfo = $this->getDoctrine()->getRepository('AdminBundle:YsGoods')->find($g_id);
        if(empty($goodsInfo)){
            echo "<script>alert('没有该商品');</script>";exit;
        }
        $em = $this->getDoctrine()->getManager();
        $goodsInfo->setGSort($g_sort);
        $em->persist($goodsInfo);
        $em->flush();
        return $this->parseData(['msg'=>'排序成功','openUrl'=>'/goods/goods_list.html','title'=>'商品列表']);
    }

    /**
     * @return GoodsServiceImpl
     */
    private function getGoodsService(){
        return $this->get('application_service')->getService('Goods.GoodsService');
    }

}
