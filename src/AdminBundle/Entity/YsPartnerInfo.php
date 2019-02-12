<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsPartnerInfo
 *
 * @ORM\Table(name="qy_partner_info")
 * @ORM\Entity
 */
class YsPartnerInfo
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户表关联主键"})
	 */
	private $partnerId = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="province", type="integer", nullable=false, options={"unsigned"=true,"comment"="省份"})
	 */
	private $province = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="city", type="integer", nullable=false, options={"unsigned"=true,"comment"="市"})
	 */
	private $city = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="county", type="integer", nullable=false, options={"unsigned"=true,"comment"="县"})
	 */
	private $county = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="manage_info", type="text", length=65535, nullable=false, options={"comment"="经营信息"})
	 */
	private $manageInfo = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="asset_info", type="text", length=65535, nullable=false, options={"comment"="资产信息"})
	 */
	private $assetInfo = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="debt_info", type="text", length=65535, nullable=false, options={"comment"="负债信息"})
	 */
	private $debtInfo = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="accept_department", type="string", length=20, nullable=false, options={"comment"="受理营业部"})
	 */
	private $acceptDepartment = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="sale_manager", type="string", length=10, nullable=false, options={"comment"="营业部经理"})
	 */
	private $saleManager = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="accept_officer", type="string", length=10, nullable=false, options={"comment"="办理人员"})
	 */
	private $acceptOfficer = '';

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="update_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="修改时间"})
	 */
	private $updateTime = 'CURRENT_TIMESTAMP';

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="create_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="创建时间"})
	 */
	private $createTime = 'CURRENT_TIMESTAMP';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="partner_detail_address", type="string", length=100, nullable=false, options={"comment"="商家详细地址"})
	 */
	private $partnerDetailAddress = '';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;



	/**
	 * Set partnerId.
	 *
	 * @param int $partnerId
	 *
	 * @return YsPartnerInfo
	 */
	public function setPartnerId($partnerId)
	{
		$this->partnerId = $partnerId;

		return $this;
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
	 * Set province.
	 *
	 * @param int $province
	 *
	 * @return YsPartnerInfo
	 */
	public function setProvince($province)
	{
		$this->province = $province;

		return $this;
	}

	/**
	 * Get province.
	 *
	 * @return int
	 */
	public function getProvince()
	{
		return $this->province;
	}

	/**
	 * Set city.
	 *
	 * @param int $city
	 *
	 * @return YsPartnerInfo
	 */
	public function setCity($city)
	{
		$this->city = $city;

		return $this;
	}

	/**
	 * Get city.
	 *
	 * @return int
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Set county.
	 *
	 * @param int $county
	 *
	 * @return YsPartnerInfo
	 */
	public function setCounty($county)
	{
		$this->county = $county;

		return $this;
	}

	/**
	 * Get county.
	 *
	 * @return int
	 */
	public function getCounty()
	{
		return $this->county;
	}

	/**
	 * Set manageInfo.
	 *
	 * @param string $manageInfo
	 *
	 * @return YsPartnerInfo
	 */
	public function setManageInfo($manageInfo)
	{
		$this->manageInfo = $manageInfo;

		return $this;
	}

	/**
	 * Get manageInfo.
	 *
	 * @return string
	 */
	public function getManageInfo()
	{
		return $this->manageInfo;
	}

	/**
	 * Set assetInfo.
	 *
	 * @param string $assetInfo
	 *
	 * @return YsPartnerInfo
	 */
	public function setAssetInfo($assetInfo)
	{
		$this->assetInfo = $assetInfo;

		return $this;
	}

	/**
	 * Get assetInfo.
	 *
	 * @return string
	 */
	public function getAssetInfo()
	{
		return $this->assetInfo;
	}

	/**
	 * Set debtInfo.
	 *
	 * @param string $debtInfo
	 *
	 * @return YsPartnerInfo
	 */
	public function setDebtInfo($debtInfo)
	{
		$this->debtInfo = $debtInfo;

		return $this;
	}

	/**
	 * Get debtInfo.
	 *
	 * @return string
	 */
	public function getDebtInfo()
	{
		return $this->debtInfo;
	}

	/**
	 * Set acceptDepartment.
	 *
	 * @param string $acceptDepartment
	 *
	 * @return YsPartnerInfo
	 */
	public function setAcceptDepartment($acceptDepartment)
	{
		$this->acceptDepartment = $acceptDepartment;

		return $this;
	}

	/**
	 * Get acceptDepartment.
	 *
	 * @return string
	 */
	public function getAcceptDepartment()
	{
		return $this->acceptDepartment;
	}

	/**
	 * Set saleManager.
	 *
	 * @param string $saleManager
	 *
	 * @return YsPartnerInfo
	 */
	public function setSaleManager($saleManager)
	{
		$this->saleManager = $saleManager;

		return $this;
	}

	/**
	 * Get saleManager.
	 *
	 * @return string
	 */
	public function getSaleManager()
	{
		return $this->saleManager;
	}

	/**
	 * Set acceptOfficer.
	 *
	 * @param string $acceptOfficer
	 *
	 * @return YsPartnerInfo
	 */
	public function setAcceptOfficer($acceptOfficer)
	{
		$this->acceptOfficer = $acceptOfficer;

		return $this;
	}

	/**
	 * Get acceptOfficer.
	 *
	 * @return string
	 */
	public function getAcceptOfficer()
	{
		return $this->acceptOfficer;
	}
	/**
	 * Set updateTime.
	 *
	 * @param \DateTime $updateTime
	 *
	 * @return YsPartnerInfo
	 */
	public function setUpdateTime($updateTime)
	{
		$this->updateTime = $updateTime;

		return $this;
	}

	/**
	 * Get updateTime.
	 *
	 * @return \DateTime
	 */
	public function getUpdateTime()
	{
		return $this->updateTime;
	}

	/**
	 * Set createTime.
	 *
	 * @param \DateTime $createTime
	 *
	 * @return YsPartnerInfo
	 */
	public function setCreateTime($createTime)
	{
		$this->createTime = $createTime;

		return $this;
	}

	/**
	 * Get createTime.
	 *
	 * @return \DateTime
	 */
	public function getCreateTime()
	{
		return $this->createTime;
	}

	/**
	 * Set partnerDetailAddress.
	 *
	 * @param string $partnerDetailAddress
	 *
	 * @return YsPartnerInfo
	 */
	public function setPartnerDetailAddress($partnerDetailAddress)
	{
		$this->partnerDetailAddress = $partnerDetailAddress;

		return $this;
	}

	/**
	 * Get partnerDetailAddress.
	 *
	 * @return string
	 */
	public function getPartnerDetailAddress()
	{
		return $this->partnerDetailAddress;
	}

	/**
	 * Get id.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
}
