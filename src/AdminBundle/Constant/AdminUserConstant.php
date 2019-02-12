<?php
namespace AdminBundle\Constant;

class AdminUserConstant
{
	// 商户婚姻状况
    const MARITAL_STATUS_TEN = 10;
    const MARITAL_STATUS_TWENTY = 20;
    const MARITAL_STATUS_THIRTY = 30;
    const MARITAL_STATUS_FORTY = 40;

	public static $maritalStatus = [
		self::MARITAL_STATUS_TEN      => '未婚',
		self::MARITAL_STATUS_TWENTY   => '已婚',
		self::MARITAL_STATUS_THIRTY   => '丧偶',
		self::MARITAL_STATUS_FORTY    => '离异',
	];

	// 商户教育状况

	const EDUCATE_STATUS_TEN        = 10;
	const EDUCATE_STATUS_TWENTY     = 20;
	const EDUCATE_STATUS_THIRTY     = 30;
	const EDUCATE_STATUS_FORTY      = 40;
	const EDUCATE_STATUS_FIFTY      = 50;
	const EDUCATE_STATUS_SIXTY      = 60;
	const EDUCATE_STATUS_SEVENTY    = 70;
	const EDUCATE_STATUS_EIGHTY     = 80;
	const EDUCATE_STATUS_NINETY     = 90;

	public static $educateStatus = [
		self::EDUCATE_STATUS_TEN        => '研究生及以上',
		self::EDUCATE_STATUS_TWENTY     => '本科',
		self::EDUCATE_STATUS_THIRTY     => '专科',
		self::EDUCATE_STATUS_FORTY      => '中等技术学校',
		self::EDUCATE_STATUS_FIFTY      => '技术学校',
		self::EDUCATE_STATUS_SIXTY      => '高中',
		self::EDUCATE_STATUS_SEVENTY    => '初中',
		self::EDUCATE_STATUS_EIGHTY     => '小学',
		self::EDUCATE_STATUS_NINETY     => '小学以下',
	];

	// 联系人是否删除

	const CONCACT_DEL_YES        = 2;
	const CONCACT_DEL_NO         = 1;

	public static $concactDelStatus = [
		self::CONCACT_DEL_YES        => '是',
		self::CONCACT_DEL_NO     => '否',
	];

	// 后台用户类型

	const ADMIN_ZERO        = 0;
	const ADMIN_ONE         = 1;
	const ADMIN_TWO         = 2;

	public static $adminType = [
		self::ADMIN_ZERO    => '商户',
		self::ADMIN_ONE     => '管理员',
		self::ADMIN_TWO     => '业务员',
	];

	/**
	 * 获取商户的婚姻状况
	 * @param null $key
	 * @return array|null
	 */
	public static function getMaritalStatus($key = null){
		$maritalStatus = self::$maritalStatus;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($maritalStatus))){
				return $maritalStatus[$key];
			}else{
				return '';
			}
		}
	}

	/**
	 * 获取后台用户类型
	 * @param null $key
	 * @return array|null
	 */
	public static function getAdminType($key = null){
		$adminType = self::$adminType;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($adminType))){
				return $adminType[$key];
			}else{
				return '';
			}
		}
	}

	/**
	 * 获取联系人是否被删除
	 * @param null $key
	 * @return array|null
	 */
	public static function getConcactDelStatus($key = null){
		$concactDelStatus = self::$concactDelStatus;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($concactDelStatus))){
				return $concactDelStatus[$key];
			}else{
				return '';
			}
		}
	}
	/**
	 * 获取商户教育状况
	 * @param null $key
	 * @return array|null
	 */
	public static function getEducateStatus($key = null){
		$educateStatus = self::$educateStatus;
		if(is_null($key)){
			return '';
		}else{
			if(in_array($key,array_keys($educateStatus))){
				return $educateStatus[$key];
			}else{
				return '';
			}
		}
	}


}
