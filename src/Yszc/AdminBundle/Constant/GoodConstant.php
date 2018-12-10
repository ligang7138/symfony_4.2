<?php
namespace App\Yszc\AdminBundle\Constant;

class GoodConstant
{
	// 商品状态
    const GOOD_STATUS_AUDIT = 0;
    const GOOD_STATUS_RELEASED = 1;
    const GOOD_STATUS_UNSHELVE = 2;
    const GOOD_STATUS_RELEASE = 3;

	private static $status = [
		self::GOOD_STATUS_AUDIT     => '待审核',
		self::GOOD_STATUS_RELEASED  => '待发布',
		self::GOOD_STATUS_UNSHELVE  => '下架',
		self::GOOD_STATUS_RELEASE   => '发布',
	];


	/**
	 * 获取商品状态
	 * @param null $key
	 * @return array|null
	 */
	public static function getStatus($key = null){
		$status = self::$status;
		if(is_null($key)){
			return $status;
		}else{
			if(in_array($key,array_keys($status))){
				return $status[$key];
			}else{
				return null;
			}
		}
	}
}
