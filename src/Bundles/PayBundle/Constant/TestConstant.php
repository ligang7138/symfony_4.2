<?php
namespace Bundles\PayBundle\Constant;

class TestConstant
{
	// 商户状态
    const MERCHANT_STATUS_ENABLED = 1;
    const MERCHANT_STATUS_DISABLED = 0;

	private static $merchant_status = [
		self::MERCHANT_STATUS_DISABLED  => '停用',
		self::MERCHANT_STATUS_ENABLED   => '启用',
	];

	// 商户类别
	const MERCHANT_CREDIT = 1;
	const MERCHANT_ORDINARY = 2;
	private static $merchant_catagory = [
		self::MERCHANT_CREDIT   => '信用商户',
		self::MERCHANT_ORDINARY => '普通商户',
	];

	// 商户审核状态
	const MERCHANT_CHECK_NOT_APPLY = 1;
	const MERCHANT_CHECK_AUDIT = 2;
	const MERCHANT_CHECK_SUCCEED = 3;
	const MERCHANT_CHECK_REPULSE = 4;
	const MERCHANT_CHECK_FAIL = 5;

	private static $merchant_check_status = [
		self::MERCHANT_CHECK_NOT_APPLY  => '未申请',
		self::MERCHANT_CHECK_AUDIT      => '待审核',
		self::MERCHANT_CHECK_SUCCEED    => '成功',
		self::MERCHANT_CHECK_REPULSE    => '打回',
		self::MERCHANT_CHECK_FAIL       => '失败',
	];

	/**
	 * 获取商户状态
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantStatus($key = null){
		$merchant_status = self::$merchant_status;
		if(is_null($key)){
			return $merchant_status;
		}else{
			if(in_array($key,array_keys($merchant_status))){
				return null;
			}else{
				return $merchant_status[$key];
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
			return $merchant_catagory;
		}else{
			if(in_array($key,array_keys($merchant_catagory))){
				return null;
			}else{
				return $merchant_catagory[$key];
			}
		}
	}

	/**
	 * 获取商户类别
	 * @param null $key
	 * @return array|null
	 */
	public static function getMerchantCheckStatus($key = null){
		$checkStatus = self::$merchant_check_status;
		if(is_null($key)){
			return $checkStatus;
		}else{
			if(in_array($key,array_keys($checkStatus))){
				return null;
			}else{
				return $checkStatus[$key];
			}
		}
	}
}
