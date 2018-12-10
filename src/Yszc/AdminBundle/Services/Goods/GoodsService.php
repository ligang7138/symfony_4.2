<?php
/**
 * Created by AdminBundle.
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:00
 */

namespace App\Yszc\AdminBundle\Services\Goods;


interface  GoodsService
{
	//获取商品列表
    public function getGoodsList($conditions = null);

    //获取品牌列表
	public function getGBrandList($conditions = NULL);

	//删除商品规格信息
    public function delSpecPriceInfo($gnId);

    //获取商品对应相关规格信息
    public function getGoodsStandard($gn_id);

    //获取商品详细信息
    public function getGoodsInfo($gId);


}