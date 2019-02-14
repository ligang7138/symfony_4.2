<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUserInfo
 *
 * @ORM\Table(name="admin_user_info", uniqueConstraints={@ORM\UniqueConstraint(name="index_aui_a_id", columns={"a_id"})}, indexes={@ORM\Index(name="admin_service_no", columns={"ai_service_no"})})
 * @ORM\Entity
 */
class AdminUserInfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="a_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $aId;

    /**
     * @var string
     *
     * @ORM\Column(name="a_true_name", type="string", length=45, nullable=false, options={"comment"="姓名"})
     */
    private $aTrueName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="a_ident_no", type="string", length=20, nullable=false, options={"comment"="身份证"})
     */
    private $aIdentNo = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="a_phone", type="string", length=20, nullable=true, options={"comment"="手机号"})
     */
    private $aPhone = '';

    /**
     * @var string
     *
     * @ORM\Column(name="a_city_id", type="string", length=50, nullable=false, options={"comment"="所属营业部城市"})
     */
    private $aCityId = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ai_type", type="string", length=45, nullable=true, options={"comment"="管理员对应项目【对应多个项目用英文逗号分隔开】"})
     */
    private $aiType = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ai_service_no", type="string", length=15, nullable=true, options={"comment"="服务编号"})
     */
    private $aiServiceNo = '';

    /**
     * @var string
     *
     * @ORM\Column(name="ai_email", type="string", length=60, nullable=false, options={"comment"="邮件地址"})
     */
    private $aiEmail = '';

    /**
     * @var string
     *
     * @ORM\Column(name="a_open_bank_name", type="string", length=30, nullable=false, options={"fixed"=true,"comment"="开户行"})
     */
    private $aOpenBankName = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="a_bank_branch_name", type="string", length=30, nullable=false, options={"fixed"=true,"comment"="支行名"})
	 */
	private $aBankBranchName = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="a_bank_branch_code", type="string", length=30, nullable=false, options={"fixed"=true,"comment"="支行行号"})
	 */
	private $aBankBranchCode = '';
    /**
     * @var string
     *
     * @ORM\Column(name="a_card_no", type="string", length=30, nullable=false, options={"comment"="银行卡"})
     */
    private $aCardNo = '';

    /**
     * @var string
     *
     * @ORM\Column(name="a_live_address", type="string", length=100, nullable=false, options={"comment"="居住地址（省市县）"})
     */
    private $aLiveAddress = '';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="a_province", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户省份"})
	 */
	private $aProvince = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="a_city", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户市"})
	 */
	private $aCity = '0';

	/**
	 * @var int
	 *
	 * @ORM\Column(name="a_county", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户县"})
	 */
	private $aCounty = '0';
    /**
     * @var string
     *
     * @ORM\Column(name="a_detail_address", type="string", length=100, nullable=false, options={"comment"="详细地址"})
     */
    private $aDetailAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="a_home_phone", type="string", length=20, nullable=false, options={"comment"="住宅电话"})
     */
    private $aHomePhone = '';

    /**
     * @var string
     *
     * @ORM\Column(name="a_marital_status", type="string", length=3, nullable=false, options={"default"="10","fixed"=true,"comment"="婚姻状况 10 : 未婚, 20 : 已婚, 30 : 丧偶, 40 : 离异"})
     */
    private $aMaritalStatus = '10';

    /**
     * @var string
     *
     * @ORM\Column(name="a_degree", type="string", length=3, nullable=false, options={"default"="99","fixed"=true,"comment"="教育程度 10 : 研究生及以上, 20 : 本科,30 : 专科,40 : 中等技术学校,50 : 技术学校, 60 : 高中,70 : 初中,80 : 小学,90 : 小学及以下"})
     */
    private $aDegree = '';

    /**
     * @var string
     *
     * @ORM\Column(name="together_live_person", type="string", length=50, nullable=false, options={"comment"="共同居住人"})
     */
    private $togetherLivePerson = '';

    /**
     * @var int
     *
     * @ORM\Column(name="ai_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aiId;



    /**
     * Set aId.
     *
     * @param int $aId
     *
     * @return AdminUserInfo
     */
    public function setAId($aId)
    {
        $this->aId = $aId;

        return $this;
    }

    /**
     * Get aId.
     *
     * @return int
     */
    public function getAId()
    {
        return $this->aId;
    }

    /**
     * Set aTrueName.
     *
     * @param string $aTrueName
     *
     * @return AdminUserInfo
     */
    public function setATrueName($aTrueName)
    {
        $this->aTrueName = $aTrueName;

        return $this;
    }

    /**
     * Get aTrueName.
     *
     * @return string
     */
    public function getATrueName()
    {
        return $this->aTrueName;
    }

    /**
     * Set aIdentNo.
     *
     * @param string $aIdentNo
     *
     * @return AdminUserInfo
     */
    public function setAIdentNo($aIdentNo)
    {
        $this->aIdentNo = $aIdentNo;

        return $this;
    }

    /**
     * Get aIdentNo.
     *
     * @return string
     */
    public function getAIdentNo()
    {
        return $this->aIdentNo;
    }

    /**
     * Set aPhone.
     *
     * @param string|null $aPhone
     *
     * @return AdminUserInfo
     */
    public function setAPhone($aPhone = null)
    {
        $this->aPhone = $aPhone;

        return $this;
    }

    /**
     * Get aPhone.
     *
     * @return string|null
     */
    public function getAPhone()
    {
        return $this->aPhone;
    }

    /**
     * Set aCityId.
     *
     * @param string $aCityId
     *
     * @return AdminUserInfo
     */
    public function setACityId($aCityId)
    {
        $this->aCityId = $aCityId;

        return $this;
    }

    /**
     * Get aCityId.
     *
     * @return string
     */
    public function getACityId()
    {
        return $this->aCityId;
    }

    /**
     * Set aiType.
     *
     * @param string|null $aiType
     *
     * @return AdminUserInfo
     */
    public function setAiType($aiType = null)
    {
        $this->aiType = $aiType;

        return $this;
    }

    /**
     * Get aiType.
     *
     * @return string|null
     */
    public function getAiType()
    {
        return $this->aiType;
    }

    /**
     * Set aiServiceNo.
     *
     * @param string|null $aiServiceNo
     *
     * @return AdminUserInfo
     */
    public function setAiServiceNo($aiServiceNo = null)
    {
        $this->aiServiceNo = $aiServiceNo;

        return $this;
    }

    /**
     * Get aiServiceNo.
     *
     * @return string|null
     */
    public function getAiServiceNo()
    {
        return $this->aiServiceNo;
    }

    /**
     * Set aiEmail.
     *
     * @param string $aiEmail
     *
     * @return AdminUserInfo
     */
    public function setAiEmail($aiEmail)
    {
        $this->aiEmail = $aiEmail;

        return $this;
    }

    /**
     * Get aiEmail.
     *
     * @return string
     */
    public function getAiEmail()
    {
        return $this->aiEmail;
    }

    /**
     * Set aOpenBankName.
     *
     * @param string $aOpenBankName
     *
     * @return AdminUserInfo
     */
    public function setAOpenBankName($aOpenBankName)
    {
        $this->aOpenBankName = $aOpenBankName;

        return $this;
    }

    /**
     * Get aOpenBankName.
     *
     * @return string
     */
    public function getAOpenBankName()
    {
        return $this->aOpenBankName;
    }

	/**
	 * Set aBankBranchCode.
	 *
	 * @param string aBankBranchCode
	 *
	 * @return AdminUserInfo
	 */
	public function setABankBranchCode($aBankBranchCode)
	{
		$this->aBankBranchCode = $aBankBranchCode;

		return $this;
	}

	/**
	 * Get aBankBranchCode.
	 *
	 * @return string
	 */
	public function getABankBranchCode()
	{
		return $this->aBankBranchCode;
	}

	/**
	 * Set aBankBranchName.
	 *
	 * @param string $aBankBranchName
	 *
	 * @return AdminUserInfo
	 */
	public function setABankBranchName($aBankBranchName)
	{
		$this->aBankBranchName = $aBankBranchName;

		return $this;
	}

	/**
	 * Get aOpenBankName.
	 *
	 * @return string
	 */
	public function getABankBranchName()
	{
		return $this->aBankBranchName;
	}

    /**
     * Set aCardNo.
     *
     * @param string $aCardNo
     *
     * @return AdminUserInfo
     */
    public function setACardNo($aCardNo)
    {
        $this->aCardNo = $aCardNo;

        return $this;
    }

    /**
     * Get aCardNo.
     *
     * @return string
     */
    public function getACardNo()
    {
        return $this->aCardNo;
    }

    /**
     * Set aLiveAddress.
     *
     * @param string $aLiveAddress
     *
     * @return AdminUserInfo
     */
    public function setALiveAddress($aLiveAddress)
    {
        $this->aLiveAddress = $aLiveAddress;

        return $this;
    }

    /**
     * Get aLiveAddress.
     *
     * @return string
     */
    public function getALiveAddress()
    {
        return $this->aLiveAddress;
    }

	/**
	 * Set aProvince.
	 *
	 * @param int $province
	 *
	 * @return AdminUserInfo
	 */
	public function setAProvince($province)
	{
		$this->aProvince = $province;

		return $this;
	}

	/**
	 * Get aProvince.
	 *
	 * @return int
	 */
	public function getAProvince()
	{
		return $this->aProvince;
	}

	/**
	 * Set aCity.
	 *
	 * @param int $city
	 *
	 * @return AdminUserInfo
	 */
	public function setACity($city)
	{
		$this->aCity = $city;

		return $this;
	}

	/**
	 * Get aCity.
	 *
	 * @return int
	 */
	public function getACity()
	{
		return $this->aCity;
	}

	/**
	 * Set aCounty.
	 *
	 * @param int $county
	 *
	 * @return AdminUserInfo
	 */
	public function setACounty($county)
	{
		$this->aCounty = $county;

		return $this;
	}

	/**
	 * Get aCounty.
	 *
	 * @return int
	 */
	public function getACounty()
	{
		return $this->aCounty;
	}
    /**
     * Set aDetailAddress.
     *
     * @param string $aDetailAddress
     *
     * @return AdminUserInfo
     */
    public function setADetailAddress($aDetailAddress)
    {
        $this->aDetailAddress = $aDetailAddress;

        return $this;
    }

    /**
     * Get aDetailAddress.
     *
     * @return string
     */
    public function getADetailAddress()
    {
        return $this->aDetailAddress;
    }

    /**
     * Set aHomePhone.
     *
     * @param string $aHomePhone
     *
     * @return AdminUserInfo
     */
    public function setAHomePhone($aHomePhone)
    {
        $this->aHomePhone = $aHomePhone;

        return $this;
    }

    /**
     * Get aHomePhone.
     *
     * @return string
     */
    public function getAHomePhone()
    {
        return $this->aHomePhone;
    }

    /**
     * Set aMaritalStatus.
     *
     * @param string $aMaritalStatus
     *
     * @return AdminUserInfo
     */
    public function setAMaritalStatus($aMaritalStatus)
    {
        $this->aMaritalStatus = $aMaritalStatus;

        return $this;
    }

    /**
     * Get aMaritalStatus.
     *
     * @return string
     */
    public function getAMaritalStatus()
    {
        return $this->aMaritalStatus;
    }

    /**
     * Set aDegree.
     *
     * @param string $aDegree
     *
     * @return AdminUserInfo
     */
    public function setADegree($aDegree)
    {
        $this->aDegree = $aDegree;

        return $this;
    }

    /**
     * Get aDegree.
     *
     * @return string
     */
    public function getADegree()
    {
        return $this->aDegree;
    }

    /**
     * Set togetherLivePerson.
     *
     * @param string $togetherLivePerson
     *
     * @return AdminUserInfo
     */
    public function setTogetherLivePerson($togetherLivePerson)
    {
        $this->togetherLivePerson = $togetherLivePerson;

        return $this;
    }

    /**
     * Get togetherLivePerson.
     *
     * @return string
     */
    public function getTogetherLivePerson()
    {
        return $this->togetherLivePerson;
    }

    /**
     * Get aiId.
     *
     * @return int
     */
    public function getAiId()
    {
        return $this->aiId;
    }
}
