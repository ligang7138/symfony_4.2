<?php
namespace App\Yszc\AdminBundle\Constant;

class MerchantConstant
{
	// 商户状态
	const MERCHANT_STATUS_DISABLED  = 1;
	const MERCHANT_STATUS_ENABLED   = 2;

	public static $merchant_status = [
		self::MERCHANT_STATUS_DISABLED  => '停用',
		self::MERCHANT_STATUS_ENABLED   => '正常',
	];

	// 商城搜索限制 zAdd('lonely:partner',4,$sessoin['u_service_code'])
	const IS_LONELY_ALL     = 1;
	const IS_LONELY_SAME    = 2;
	const IS_LONELY_PUBLIC  = 3;

	public static $is_lonely_status = [
		self::IS_LONELY_PUBLIC  => '公开搜索',
		self::IS_LONELY_SAME    => '限制同类',
		self::IS_LONELY_ALL     => '限制全部',
	];
	// 商城搜索限制

	// 商户类别
	const MERCHANT_ORDINARY     = 1;
	const MERCHANT_CREDIT       = 2;
	const MERCHANT_SELF_SUPPORT = 3;
	public static $merchant_catagory = [
		self::MERCHANT_CREDIT       => '信用商户',
		self::MERCHANT_ORDINARY     => '普通商户',
		self::MERCHANT_SELF_SUPPORT => '自营商户',
	];

	// 商户是否支持赊购
	const MERCHANT_CREDIT_BUY_YES = 1;
	const MERCHANT_CREDIT_BUY_NO  = 2;
	public static $merchant_credit_buy = [
		self::MERCHANT_CREDIT_BUY_YES     => '是',
		self::MERCHANT_CREDIT_BUY_NO      => '否',
	];

	// 商户审核状态
	const MERCHANT_CHECK_NOT_APPLY  = 1;
	const MERCHANT_CHECK_AUDIT      = 2;
	const MERCHANT_CHECK_REPULSE    = 3;
	const MERCHANT_CHECK_FAIL       = 4;
	const MERCHANT_CHECK_SUCCEED    = 5;

	// 商户审核状态

	public static $merchant_check_status = [
		self::MERCHANT_CHECK_NOT_APPLY  => '未申请',
		self::MERCHANT_CHECK_AUDIT      => '待审核',
		self::MERCHANT_CHECK_REPULSE    => '打回',
		self::MERCHANT_CHECK_FAIL       => '拒绝',
		self::MERCHANT_CHECK_SUCCEED    => '通过',

	];

	// 是否为品牌商户
	const MERCHANT_BRAND_YES = 1;
	const MERCHANT_BRAND_NO = 0;

	public static $merchant_is_brand = [
		self::MERCHANT_BRAND_YES  => '是',
		self::MERCHANT_BRAND_NO   => '否',
	];

	// 销售品类
	const SALES_CATEGORY_FEED = 1;
	const SALES_CATEGORY_MACHINE = 2;
	const SALES_CATEGORY_FERTILIZER = 3;
	const SALES_CATEGORY_SEED = 4;
	const SALES_CATEGORY_PESTICIDES = 5;
	const SALES_CATEGORY_FARM_Articles = 6;

	/**
	 * 获取商户状态
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantStatus($key = null){
		$merchant_status = self::$merchant_status;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($merchant_status))){
				return $merchant_status[$key];
			}else{
				return '';
			}
		}
	}
	/**
	 * 获取商户状态
	 * @param null $key
	 * @return array|null
	 */
	public static function getIsLonelyStatus($key = null){
		$status = self::$is_lonely_status;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($status))){
				return $status[$key];
			}else{
				return '';
			}
		}
	}

	/**
	 * 获取商户类别
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantCategory($key = null){
		$merchant_catagory = self::$merchant_catagory;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($merchant_catagory))){
				return $merchant_catagory[$key];
			}else{
				return '';
			}
		}
	}

	/**
	 * 获取商户是否支持赊购的状态
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantSelfSupport($key = null){
		$merchant_self_support = self::$merchant_credit_buy;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($merchant_self_support))){
				return $merchant_self_support[$key];
			}else{
				return '';
			}
		}
	}

	/**
	 * 获取商户审核状态
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantCheckStatus($key = null){
		$checkStatus = self::$merchant_check_status;
		if(is_null($key)){
			return '未申请';
		}else{
			if(in_array($key,array_keys($checkStatus))){
				return $checkStatus[$key];
			}else{
				return '';
			}
		}
	}

	/**
	 * 获取商户是否为品牌
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantIsBrand($key = null){
		$is_brand = self::$merchant_is_brand;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($is_brand))){
				return $is_brand[$key];
			}else{
				return '';
			}
		}
	}
}
