<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * YsPartners
 * @ORM\Table(name="qy_partners")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 */
class YsPartners
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_name", type="string", length=150, nullable=false, options={"comment"="商户名称"})
	 */
	private $partnerName = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_phone", type="string", length=12, nullable=false, options={"comment"="商户电话"})
	 */
	private $partnerPhone = '';

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="partner_header_img", type="string", length=150, nullable=true, options={"comment"="商户头像"})
	 */
	private $partnerHeaderImg = '';

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="partner_notice", type="string", length=300, nullable=true, options={"comment"="商户公告"})
	 */
	private $partnerNotice = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_status", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="商户状态【1.未申请 2.待审核 3.打回 4.拒绝 5.通过】，审核通过后启用"})
	 */
	private $partnerStatus = '1';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="partner_parent_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="上级商户"})
	 */
	private $partnerParentId = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_type", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="类型：1仅支持全款，2全款和赊购"})
	 */
	private $partnerType = '1';

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="partner_intention", type="string", length=20, nullable=true, options={"comment"="商品意向【指主要销售方向】"})
	 */
	private $partnerIntention = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_lat", type="decimal", precision=12, scale=8, nullable=false, options={"default"="0.00000000","comment"="商户纬度"})
	 */
	private $partnerLat = '0.00000000';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_lng", type="decimal", precision=12, scale=8, nullable=false, options={"default"="0.00000000","comment"="商户经度"})
	 */
	private $partnerLng = '0.00000000';

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="partner_add_time", type="datetime", nullable=false)
	 */
	private $partnerAddTime;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="partner_code", type="string", length=45, nullable=true, options={"comment"="商户号"})
	 */
	private $partnerCode = '';

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="partner_intro", type="string", length=3000, nullable=true, options={"comment"="商户简介"})
	 */
	private $partnerIntro = '';

	/**
	 * @var \DateTime|null
	 *
	 * @ORM\Column(name="partner_update_time", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP","comment"="修改时间"})
	 */
	private $partnerUpdateTime = 'CURRENT_TIMESTAMP';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="partner_settlement_time", type="smallint", nullable=false, options={"unsigned"=true,"comment"="结算周期【0按月，2按天】"})
	 */
	private $partnerSettlementTime = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_settlement_service_rate", type="decimal", precision=8, scale=5, nullable=false, options={"default"="0.00000","comment"="结算服务费率"})
	 */
	private $partnerSettlementServiceRate = '0.00000';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_overdue_type", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="0正常，1逾期费不累计"})
	 */
	private $partnerOverdueType = '0';

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="partner_auto_pay", type="boolean", nullable=false, options={"default"="1","comment"="1:线下人工放款  2：线上自动放款"})
	 */
	private $partnerAutoPay = '1';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_is_brand", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="是否品牌商户【0否，1是】"})
	 */
	private $partnerIsBrand = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="admin_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户用户id"})
	 */
	private $adminId;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="is_lonely", type="string", length=1, nullable=true, options={"fixed"=true,"comment"="商户是否只能被自己推广用户看到"})
	 */
	private $isLonely = '3';


	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="is_normal", type="string", length=1, nullable=false, options={"comment"="店铺是否正常营业 1:否 2:是"})
	 */
	private $isNormal = '1';

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="partner_service_code", type="string", length=15, nullable=true)
	 */
	private $partnerServiceCode = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="is_support_distribut", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="是否支持配送 1：不支持 2：支持"})
	 */
	private $isSupportDistribut = '1';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="is_credit_buy", type="string", length=1, nullable=false, options={"default"="0","fixed"=true,"comment"="是否支持赊购 1：是 2：否"})
	 */
	private $isCreditBuy = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="distribut_distance", type="smallint", nullable=false, options={"unsigned"=true,"comment"="配送距离"})
	 */
	private $distributDistance = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="send_out_money", type="smallint", nullable=false, options={"unsigned"=true,"comment"="起送金额"})
	 */
	private $sendOutMoney = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="begin_distribut_time", type="string", length=20, nullable=false, options={"comment"="配送开始时间"})
	 */
	private $beginDistributTime = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="end_distribut_time", type="string", length=20, nullable=false, options={"comment"="配送结束时间"})
	 */
	private $endDistributTime = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="is_agree", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="是否同意开店服务协议 1：不同意 2：同意"})
	 */
	private $isAgree = '1';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="free_freight", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="是否免运费 1：否 2：是"})
	 */
	private $freeFreight = '1';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="lowest_freight_distance", type="smallint", nullable=false, options={"unsigned"=true,"comment"="最底运费距离"})
	 */
	private $lowestFreightDistance = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="lowest_freight_money", type="smallint", nullable=false, options={"unsigned"=true,"comment"="最低运费统一价"})
	 */
	private $lowestFreightMoney = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="addition_freight_distance", type="smallint", nullable=false, options={"unsigned"=true,"comment"="附加运费距离"})
	 */
	private $additionFreightDistance = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="addition_freight_money", type="smallint", nullable=false, options={"unsigned"=true,"comment"="附加运费价"})
	 */
	private $additionFreightMoney = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="trade_name", type="string", length=30, nullable=false)
	 */
	private $tradeName = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="trade_brokerage_rate", type="decimal", precision=10, scale=8, nullable=false)
	 */
	private $tradeBrokerageRate = '0.00000000';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="partner_id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $partnerId;



	/**
	 * Set partnerName.
	 *
	 * @param string $partnerName
	 *
	 * @return YsPartners
	 */
	public function setPartnerName($partnerName)
	{
		$this->partnerName = $partnerName;

		return $this;
	}

	/**
	 * Get partnerName.
	 *
	 * @return string
	 */
	public function getPartnerName()
	{
		return $this->partnerName;
	}

	/**
	 * Set partnerPhone.
	 *
	 * @param string $partnerPhone
	 *
	 * @return YsPartners
	 */
	public function setPartnerPhone($partnerPhone)
	{
		$this->partnerPhone = $partnerPhone;

		return $this;
	}

	/**
	 * Get partnerPhone.
	 *
	 * @return string
	 */
	public function getPartnerPhone()
	{
		return $this->partnerPhone;
	}

	/**
	 * Set partnerHeaderImg.
	 *
	 * @param string|null $partnerHeaderImg
	 *
	 * @return YsPartners
	 */
	public function setPartnerHeaderImg($partnerHeaderImg = null)
	{
		$this->partnerHeaderImg = $partnerHeaderImg;

		return $this;
	}

	/**
	 * Get partnerHeaderImg.
	 *
	 * @return string|null
	 */
	public function getPartnerHeaderImg()
	{
		return $this->partnerHeaderImg;
	}

	/**
	 * Set partnerNotice.
	 *
	 * @param string|null $partnerNotice
	 *
	 * @return YsPartners
	 */
	public function setPartnerNotice($partnerNotice = null)
	{
		$this->partnerNotice = $partnerNotice;

		return $this;
	}

	/**
	 * Get partnerNotice.
	 *
	 * @return string|null
	 */
	public function getPartnerNotice()
	{
		return $this->partnerNotice;
	}

	/**
	 * Set partnerStatus.
	 *
	 * @param string $partnerStatus
	 *
	 * @return YsPartners
	 */
	public function setPartnerStatus($partnerStatus)
	{
		$this->partnerStatus = $partnerStatus;

		return $this;
	}

	/**
	 * Get partnerStatus.
	 *
	 * @return string
	 */
	public function getPartnerStatus()
	{
		return $this->partnerStatus;
	}

	/**
	 * Set partnerParentId.
	 *
	 * @param int $partnerParentId
	 *
	 * @return YsPartners
	 */
	public function setPartnerParentId($partnerParentId)
	{
		$this->partnerParentId = $partnerParentId;

		return $this;
	}

	/**
	 * Get partnerParentId.
	 *
	 * @return int
	 */
	public function getPartnerParentId()
	{
		return $this->partnerParentId;
	}

	/**
	 * Set partnerType.
	 *
	 * @param string $partnerType
	 *
	 * @return YsPartners
	 */
	public function setPartnerType($partnerType)
	{
		$this->partnerType = $partnerType;

		return $this;
	}

	/**
	 * Get partnerType.
	 *
	 * @return string
	 */
	public function getPartnerType()
	{
		return $this->partnerType;
	}

	/**
	 * Set partnerIntention.
	 *
	 * @param string|null $partnerIntention
	 *
	 * @return YsPartners
	 */
	public function setPartnerIntention($partnerIntention = null)
	{
		$this->partnerIntention = $partnerIntention;

		return $this;
	}

	/**
	 * Get partnerIntention.
	 *
	 * @return string|null
	 */
	public function getPartnerIntention()
	{
		return $this->partnerIntention;
	}

	/**
	 * Set partnerLat.
	 *
	 * @param string $partnerLat
	 *
	 * @return YsPartners
	 */
	public function setPartnerLat($partnerLat)
	{
		$this->partnerLat = $partnerLat;

		return $this;
	}

	/**
	 * Get partnerLat.
	 *
	 * @return string
	 */
	public function getPartnerLat()
	{
		return $this->partnerLat;
	}

	/**
	 * Set partnerLng.
	 *
	 * @param string $partnerLng
	 *
	 * @return YsPartners
	 */
	public function setPartnerLng($partnerLng)
	{
		$this->partnerLng = $partnerLng;

		return $this;
	}

	/**
	 * Get partnerLng.
	 *
	 * @return string
	 */
	public function getPartnerLng()
	{
		return $this->partnerLng;
	}

	/**
	 * Set partnerAddTime.
	 *
	 * @param \DateTime $partnerAddTime
	 *
	 * @return YsPartners
	 */
	public function setPartnerAddTime($partnerAddTime)
	{
		$this->partnerAddTime = $partnerAddTime;

		return $this;
	}

	/**
	 * Get partnerAddTime.
	 *
	 * @return \DateTime
	 */
	public function getPartnerAddTime()
	{
		return $this->partnerAddTime;
	}

	/**
	 * Set partnerCode.
	 *
	 * @param string|null $partnerCode
	 *
	 * @return YsPartners
	 */
	public function setPartnerCode($partnerCode = null)
	{
		$this->partnerCode = $partnerCode;

		return $this;
	}

	/**
	 * Get partnerCode.
	 *
	 * @return string|null
	 */
	public function getPartnerCode()
	{
		return $this->partnerCode;
	}

	/**
	 * Set partnerIntro.
	 *
	 * @param string|null $partnerIntro
	 *
	 * @return YsPartners
	 */
	public function setPartnerIntro($partnerIntro = null)
	{
		$this->partnerIntro = $partnerIntro;

		return $this;
	}

	/**
	 * Get partnerIntro.
	 *
	 * @return string|null
	 */
	public function getPartnerIntro()
	{
		return $this->partnerIntro;
	}

	/**
	 * Set partnerUpdateTime.
	 *
	 * @param \DateTime|null $partnerUpdateTime
	 * @return YsPartners
	 */
	public function setPartnerUpdateTime($partnerUpdateTime = null)
	{
		$this->partnerUpdateTime = $partnerUpdateTime;
		return $this;
	}

	/**
	 * Get partnerUpdateTime.
	 *
	 * @return \DateTime|null
	 */
	public function getPartnerUpdateTime()
	{
		return $this->partnerUpdateTime;
	}

	/**
	 * Set partnerSettlementTime.
	 *
	 * @param int $partnerSettlementTime
	 *
	 * @return YsPartners
	 */
	public function setPartnerSettlementTime($partnerSettlementTime)
	{
		$this->partnerSettlementTime = $partnerSettlementTime;

		return $this;
	}

	/**
	 * Get partnerSettlementTime.
	 *
	 * @return int
	 */
	public function getPartnerSettlementTime()
	{
		return $this->partnerSettlementTime;
	}

	/**
	 * Set partnerSettlementServiceRate.
	 *
	 * @param string $partnerSettlementServiceRate
	 *
	 * @return YsPartners
	 */
	public function setPartnerSettlementServiceRate($partnerSettlementServiceRate)
	{
		$this->partnerSettlementServiceRate = $partnerSettlementServiceRate;

		return $this;
	}

	/**
	 * Get partnerSettlementServiceRate.
	 *
	 * @return string
	 */
	public function getPartnerSettlementServiceRate()
	{
		return $this->partnerSettlementServiceRate;
	}

	/**
	 * Set partnerOverdueType.
	 *
	 * @param string $partnerOverdueType
	 *
	 * @return YsPartners
	 */
	public function setPartnerOverdueType($partnerOverdueType)
	{
		$this->partnerOverdueType = $partnerOverdueType;

		return $this;
	}

	/**
	 * Get partnerOverdueType.
	 *
	 * @return string
	 */
	public function getPartnerOverdueType()
	{
		return $this->partnerOverdueType;
	}

	/**
	 * Set partnerAutoPay.
	 *
	 * @param bool $partnerAutoPay
	 *
	 * @return YsPartners
	 */
	public function setPartnerAutoPay($partnerAutoPay)
	{
		$this->partnerAutoPay = $partnerAutoPay;

		return $this;
	}

	/**
	 * Get partnerAutoPay.
	 *
	 * @return bool
	 */
	public function getPartnerAutoPay()
	{
		return $this->partnerAutoPay;
	}

	/**
	 * Set partnerIsBrand.
	 *
	 * @param string $partnerIsBrand
	 *
	 * @return YsPartners
	 */
	public function setPartnerIsBrand($partnerIsBrand)
	{
		$this->partnerIsBrand = $partnerIsBrand;

		return $this;
	}

	/**
	 * Get partnerIsBrand.
	 *
	 * @return string
	 */
	public function getPartnerIsBrand()
	{
		return $this->partnerIsBrand;
	}

	/**
	 * Set adminId.
	 *
	 * @param int $adminId
	 *
	 * @return YsPartners
	 */
	public function setAdminId($adminId)
	{
		$this->adminId = $adminId;

		return $this;
	}

	/**
	 * Get adminId.
	 *
	 * @return int
	 */
	public function getAdminId()
	{
		return $this->adminId;
	}

	/**
	 * Set isLonely.
	 *
	 * @param string|null $isLonely
	 *
	 * @return YsPartners
	 */
	public function setIsLonely($isLonely = null)
	{
		$this->isLonely = $isLonely;

		return $this;
	}

	/**
	 * Get isLonely.
	 *
	 * @return string|null
	 */
	public function getIsLonely()
	{
		return $this->isLonely;
	}

	/**
	 * Set isNormal.
	 *
	 * @param string|null $isNormal
	 *
	 * @return YsPartners
	 */
	public function setIsNormal($isNormal = null)
	{
		$this->isNormal = $isNormal;

		return $this;
	}

	/**
	 * Get isNormal.
	 *
	 * @return string|null
	 */
	public function getIsNormal()
	{
		return $this->isNormal;
	}

	/**
	 * Set partnerServiceCode.
	 *
	 * @param string|null $partnerServiceCode
	 *
	 * @return YsPartners
	 */
	public function setPartnerServiceCode($partnerServiceCode = null)
	{
		$this->partnerServiceCode = $partnerServiceCode;

		return $this;
	}

	/**
	 * Get partnerServiceCode.
	 *
	 * @return string|null
	 */
	public function getPartnerServiceCode()
	{
		return $this->partnerServiceCode;
	}

	/**
	 * Set isSupportDistribut.
	 *
	 * @param string $isSupportDistribut
	 *
	 * @return YsPartners
	 */
	public function setIsSupportDistribut($isSupportDistribut)
	{
		$this->isSupportDistribut = $isSupportDistribut;

		return $this;
	}

	/**
	 * Get isSupportDistribut.
	 *
	 * @return string
	 */
	public function getIsSupportDistribut()
	{
		return $this->isSupportDistribut;
	}

	/**
	 * Set isCreditBuy.
	 *
	 * @param string $isCreditBuy
	 *
	 * @return YsPartners
	 */
	public function setIsCreditBuy($isCreditBuy)
	{
		$this->isCreditBuy = $isCreditBuy;

		return $this;
	}

	/**
	 * Get isCreditBuy.
	 *
	 * @return string
	 */
	public function getIsCreditBuy()
	{
		return $this->isCreditBuy;
	}
	/**
	 * Set distributDistance.
	 *
	 * @param int $distributDistance
	 *
	 * @return YsPartners
	 */
	public function setDistributDistance($distributDistance)
	{
		$this->distributDistance = $distributDistance;

		return $this;
	}

	/**
	 * Get distributDistance.
	 *
	 * @return int
	 */
	public function getDistributDistance()
	{
		return $this->distributDistance;
	}

	/**
	 * Set sendOutMoney.
	 *
	 * @param int $sendOutMoney
	 *
	 * @return YsPartners
	 */
	public function setSendOutMoney($sendOutMoney)
	{
		$this->sendOutMoney = $sendOutMoney;

		return $this;
	}

	/**
	 * Get sendOutMoney.
	 *
	 * @return int
	 */
	public function getSendOutMoney()
	{
		return $this->sendOutMoney;
	}

	/**
	 * Set beginDistributTime.
	 *
	 * @param string $beginDistributTime
	 *
	 * @return YsPartners
	 */
	public function setBeginDistributTime($beginDistributTime)
	{
		$this->beginDistributTime = $beginDistributTime;

		return $this;
	}

	/**
	 * Get beginDistributTime.
	 *
	 * @return string
	 */
	public function getBeginDistributTime()
	{
		return $this->beginDistributTime;
	}

	/**
	 * Set endDistributTime.
	 *
	 * @param string $endDistributTime
	 *
	 * @return YsPartners
	 */
	public function setEndDistributTime($endDistributTime)
	{
		$this->endDistributTime = $endDistributTime;

		return $this;
	}

	/**
	 * Get endDistributTime.
	 *
	 * @return string
	 */
	public function getEndDistributTime()
	{
		return $this->endDistributTime;
	}

	/**
	 * Set isAgree.
	 *
	 * @param string $isAgree
	 *
	 * @return YsPartners
	 */
	public function setIsAgree($isAgree)
	{
		$this->isAgree = $isAgree;

		return $this;
	}

	/**
	 * Get isAgree.
	 *
	 * @return string
	 */
	public function getIsAgree()
	{
		return $this->isAgree;
	}

	/**
	 * Set freeFreight.
	 *
	 * @param string $freeFreight
	 *
	 * @return YsPartners
	 */
	public function setFreeFreight($freeFreight)
	{
		$this->freeFreight = $freeFreight;

		return $this;
	}

	/**
	 * Get freeFreight.
	 *
	 * @return string
	 */
	public function getFreeFreight()
	{
		return $this->freeFreight;
	}

	/**
	 * Set lowestFreightDistance.
	 *
	 * @param int $lowestFreightDistance
	 *
	 * @return YsPartners
	 */
	public function setLowestFreightDistance($lowestFreightDistance)
	{
		$this->lowestFreightDistance = $lowestFreightDistance;

		return $this;
	}

	/**
	 * Set tradeName
	 *
	 * @param string $tradeName
	 *
	 * @return YsPartners
	 */
	public function setTradeName($tradeName)
	{
		$this->tradeName = $tradeName;

		return $this;
	}

	/**
	 * Get tradeName
	 *
	 * @return string
	 */
	public function getTradeName()
	{
		return $this->tradeName;
	}

	/**
	 * Get lowestFreightDistance.
	 *
	 * @return int
	 */
	public function getLowestFreightDistance()
	{
		return $this->lowestFreightDistance;
	}

	/**
	 * Set tradeBrokerageRate
	 *
	 * @param string $tradeBrokerageRate
	 *
	 * @return YsPartners
	 */
	public function setTradeBrokerageRate($tradeBrokerageRate)
	{
		$this->tradeBrokerageRate = $tradeBrokerageRate;

		return $this;
	}

	/**
	 * Get tradeBrokerageRate
	 *
	 * @return string
	 */
	public function getTradeBrokerageRate()
	{
		return $this->tradeBrokerageRate;
	}
	/**
	 * Set lowestFreightMoney.
	 *
	 * @param int $lowestFreightMoney
	 *
	 * @return YsPartners
	 */
	public function setLowestFreightMoney($lowestFreightMoney)
	{
		$this->lowestFreightMoney = $lowestFreightMoney;

		return $this;
	}

	/**
	 * Get lowestFreightMoney.
	 *
	 * @return int
	 */
	public function getLowestFreightMoney()
	{
		return $this->lowestFreightMoney;
	}

	/**
	 * Set additionFreightDistance.
	 *
	 * @param int $additionFreightDistance
	 *
	 * @return YsPartners
	 */
	public function setAdditionFreightDistance($additionFreightDistance)
	{
		$this->additionFreightDistance = $additionFreightDistance;

		return $this;
	}

	/**
	 * Get additionFreightDistance.
	 *
	 * @return int
	 */
	public function getAdditionFreightDistance()
	{
		return $this->additionFreightDistance;
	}

	/**
	 * Set additionFreightMoney.
	 *
	 * @param int $additionFreightMoney
	 *
	 * @return YsPartners
	 */
	public function setAdditionFreightMoney($additionFreightMoney)
	{
		$this->additionFreightMoney = $additionFreightMoney;

		return $this;
	}

	/**
	 * Get additionFreightMoney.
	 *
	 * @return int
	 */
	public function getAdditionFreightMoney()
	{
		return $this->additionFreightMoney;
	}

	/**
	 * Get partnerId.
	 *
	 * @return int
	 */
	public function getPartnerId()
	{
		return $this->partnerId;
	}

	/**
	 * @PreUpdate
	 */
	public function preUpdate(){

		$this->partnerUpdateTime = new \DateTime(date('Y-m-d H:i:s'));

	}
}
