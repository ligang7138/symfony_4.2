<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsPartnerCheckLog
 *
 * @ORM\Table(name="ys_partner_check_log")
 * @ORM\Entity
 */
class YsPartnerCheckLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商家主键"})
     */
    private $partnerId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="check_name", type="string", length=20, nullable=false, options={"comment"="审核人"})
     */
    private $checkName = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="check_time", type="datetime", nullable=false, options={"comment"="审核时间"})
     */
    private $checkTime;

    /**
     * @var string
     *
     * @ORM\Column(name="check_info", type="string", length=100, nullable=false, options={"comment"="审核信息"})
     */
    private $checkInfo = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false, options={"comment"="记录创建时间"})
     */
    private $createTime;

    /**
     * @var int
     *
     * @ORM\Column(name="check_type", type="integer", nullable=false, options={"comment"="类型【1商户审核 2商品审核】"})
     */
    private $checkType;

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
     * @return YsPartnerCheckLog
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
     * Set checkName.
     *
     * @param string $checkName
     *
     * @return YsPartnerCheckLog
     */
    public function setCheckName($checkName)
    {
        $this->checkName = $checkName;

        return $this;
    }

    /**
     * Get checkName.
     *
     * @return string
     */
    public function getCheckName()
    {
        return $this->checkName;
    }

    /**
     * Set checkTime.
     *
     * @param \DateTime $checkTime
     *
     * @return YsPartnerCheckLog
     */
    public function setCheckTime($checkTime)
    {
        $this->checkTime = $checkTime;

        return $this;
    }

    /**
     * Get checkTime.
     *
     * @return \DateTime
     */
    public function getCheckTime()
    {
        return $this->checkTime;
    }

    /**
     * Set checkInfo.
     *
     * @param string $checkInfo
     *
     * @return YsPartnerCheckLog
     */
    public function setCheckInfo($checkInfo)
    {
        $this->checkInfo = $checkInfo;

        return $this;
    }

    /**
     * Get checkInfo.
     *
     * @return string
     */
    public function getCheckInfo()
    {
        return $this->checkInfo;
    }

    /**
     * Set createTime.
     *
     * @param \DateTime $createTime
     *
     * @return YsPartnerCheckLog
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
     * Set checkType.
     *
     * @param int $checkType
     *
     * @return YsPartnerCheckLog
     */
    public function setCheckType($checkType)
    {
        $this->checkType = $checkType;

        return $this;
    }

    /**
     * Get checkType.
     *
     * @return int
     */
    public function getCheckType()
    {
        return $this->checkType;
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
