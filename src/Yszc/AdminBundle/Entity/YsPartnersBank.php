<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsPartnersBank
 *
 * @ORM\Table(name="qy_partners_bank")
 * @ORM\Entity
 */
class YsPartnersBank
{
    /**
     * @var int
     *
     * @ORM\Column(name="bank_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="银行id"})
     */
    private $bankId;

    /**
     * @var string
     *
     * @ORM\Column(name="partner_user_name", type="string", length=300, nullable=false, options={"comment"="姓名/商户公司名称"})
     */
    private $partnerUserName;

    /**
     * @var string
     *
     * @ORM\Column(name="partner_bank_name", type="string", length=100, nullable=false, options={"comment"="开户行"})
     */
    private $partnerBankName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parnter_bank_nums", type="string", length=30, nullable=true, options={"comment"="银行行号【对公帐号时必填】"})
     */
    private $parnterBankNums;

    /**
     * @var string
     *
     * @ORM\Column(name="partner_bank_code", type="string", length=30, nullable=false, options={"comment"="银行卡号"})
     */
    private $partnerBankCode;

    /**
     * @var string
     *
     * @ORM\Column(name="partner_user_phone", type="string", length=15, nullable=false, options={"comment"="手机号"})
     */
    private $partnerUserPhone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="partner_user_ident", type="string", length=20, nullable=true, options={"comment"="身份证"})
     */
    private $partnerUserIdent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updateTime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="partner_account_type", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="帐号类型【0个人银行卡，1对公银行卡】"})
     */
    private $partnerAccountType = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="partner_account_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="帐号状态【0启用，1停用】"})
     */
    private $partnerAccountStatus = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="partner_child_bank_addr", type="string", length=300, nullable=true, options={"comment"="开户行支行【对公帐号必填】"})
     */
    private $partnerChildBankAddr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="partner_province", type="string", length=100, nullable=true, options={"comment"="开户行省"})
     */
    private $partnerProvince;

    /**
     * @var string|null
     *
     * @ORM\Column(name="partner_city", type="string", length=100, nullable=true, options={"comment"="开户行市"})
     */
    private $partnerCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="partner_area", type="string", length=100, nullable=true, options={"comment"="开户行区县"})
     */
    private $partnerArea;

    /**
     * @var int|null
     *
     * @ORM\Column(name="partner_area_code", type="integer", nullable=true, options={"unsigned"=true,"comment"="区域编号"})
     */
    private $partnerAreaCode;

    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $partnerId;



    /**
     * Set bankId.
     *
     * @param int $bankId
     *
     * @return YsPartnersBank
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;

        return $this;
    }

    /**
     * Get bankId.
     *
     * @return int
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * Set partnerUserName.
     *
     * @param string $partnerUserName
     *
     * @return YsPartnersBank
     */
    public function setPartnerUserName($partnerUserName)
    {
        $this->partnerUserName = $partnerUserName;

        return $this;
    }

    /**
     * Get partnerUserName.
     *
     * @return string
     */
    public function getPartnerUserName()
    {
        return $this->partnerUserName;
    }

    /**
     * Set partnerBankName.
     *
     * @param string $partnerBankName
     *
     * @return YsPartnersBank
     */
    public function setPartnerBankName($partnerBankName)
    {
        $this->partnerBankName = $partnerBankName;

        return $this;
    }

    /**
     * Get partnerBankName.
     *
     * @return string
     */
    public function getPartnerBankName()
    {
        return $this->partnerBankName;
    }

    /**
     * Set parnterBankNums.
     *
     * @param string|null $parnterBankNums
     *
     * @return YsPartnersBank
     */
    public function setParnterBankNums($parnterBankNums = null)
    {
        $this->parnterBankNums = $parnterBankNums;

        return $this;
    }

    /**
     * Get parnterBankNums.
     *
     * @return string|null
     */
    public function getParnterBankNums()
    {
        return $this->parnterBankNums;
    }

    /**
     * Set partnerBankCode.
     *
     * @param string $partnerBankCode
     *
     * @return YsPartnersBank
     */
    public function setPartnerBankCode($partnerBankCode)
    {
        $this->partnerBankCode = $partnerBankCode;

        return $this;
    }

    /**
     * Get partnerBankCode.
     *
     * @return string
     */
    public function getPartnerBankCode()
    {
        return $this->partnerBankCode;
    }

    /**
     * Set partnerUserPhone.
     *
     * @param string $partnerUserPhone
     *
     * @return YsPartnersBank
     */
    public function setPartnerUserPhone($partnerUserPhone)
    {
        $this->partnerUserPhone = $partnerUserPhone;

        return $this;
    }

    /**
     * Get partnerUserPhone.
     *
     * @return string
     */
    public function getPartnerUserPhone()
    {
        return $this->partnerUserPhone;
    }

    /**
     * Set partnerUserIdent.
     *
     * @param string|null $partnerUserIdent
     *
     * @return YsPartnersBank
     */
    public function setPartnerUserIdent($partnerUserIdent = null)
    {
        $this->partnerUserIdent = $partnerUserIdent;

        return $this;
    }

    /**
     * Get partnerUserIdent.
     *
     * @return string|null
     */
    public function getPartnerUserIdent()
    {
        return $this->partnerUserIdent;
    }

    /**
     * Set updateTime.
     *
     * @param \DateTime $updateTime
     *
     * @return YsPartnersBank
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
     * Set partnerAccountType.
     *
     * @param string $partnerAccountType
     *
     * @return YsPartnersBank
     */
    public function setPartnerAccountType($partnerAccountType)
    {
        $this->partnerAccountType = $partnerAccountType;

        return $this;
    }

    /**
     * Get partnerAccountType.
     *
     * @return string
     */
    public function getPartnerAccountType()
    {
        return $this->partnerAccountType;
    }

    /**
     * Set partnerAccountStatus.
     *
     * @param string $partnerAccountStatus
     *
     * @return YsPartnersBank
     */
    public function setPartnerAccountStatus($partnerAccountStatus)
    {
        $this->partnerAccountStatus = $partnerAccountStatus;

        return $this;
    }

    /**
     * Get partnerAccountStatus.
     *
     * @return string
     */
    public function getPartnerAccountStatus()
    {
        return $this->partnerAccountStatus;
    }

    /**
     * Set partnerChildBankAddr.
     *
     * @param string|null $partnerChildBankAddr
     *
     * @return YsPartnersBank
     */
    public function setPartnerChildBankAddr($partnerChildBankAddr = null)
    {
        $this->partnerChildBankAddr = $partnerChildBankAddr;

        return $this;
    }

    /**
     * Get partnerChildBankAddr.
     *
     * @return string|null
     */
    public function getPartnerChildBankAddr()
    {
        return $this->partnerChildBankAddr;
    }

    /**
     * Set partnerProvince.
     *
     * @param string|null $partnerProvince
     *
     * @return YsPartnersBank
     */
    public function setPartnerProvince($partnerProvince = null)
    {
        $this->partnerProvince = $partnerProvince;

        return $this;
    }

    /**
     * Get partnerProvince.
     *
     * @return string|null
     */
    public function getPartnerProvince()
    {
        return $this->partnerProvince;
    }

    /**
     * Set partnerCity.
     *
     * @param string|null $partnerCity
     *
     * @return YsPartnersBank
     */
    public function setPartnerCity($partnerCity = null)
    {
        $this->partnerCity = $partnerCity;

        return $this;
    }

    /**
     * Get partnerCity.
     *
     * @return string|null
     */
    public function getPartnerCity()
    {
        return $this->partnerCity;
    }

    /**
     * Set partnerArea.
     *
     * @param string|null $partnerArea
     *
     * @return YsPartnersBank
     */
    public function setPartnerArea($partnerArea = null)
    {
        $this->partnerArea = $partnerArea;

        return $this;
    }

    /**
     * Get partnerArea.
     *
     * @return string|null
     */
    public function getPartnerArea()
    {
        return $this->partnerArea;
    }

    /**
     * Set partnerAreaCode.
     *
     * @param int|null $partnerAreaCode
     *
     * @return YsPartnersBank
     */
    public function setPartnerAreaCode($partnerAreaCode = null)
    {
        $this->partnerAreaCode = $partnerAreaCode;

        return $this;
    }

    /**
     * Get partnerAreaCode.
     *
     * @return int|null
     */
    public function getPartnerAreaCode()
    {
        return $this->partnerAreaCode;
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
}
