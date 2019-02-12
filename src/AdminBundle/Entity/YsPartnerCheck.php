<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsPartnerCheck
 *
 * @ORM\Table(name="qy_partner_check")
 * @ORM\Entity
 */
class YsPartnerCheck
{
    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户主键id"})
     */
    private $partnerId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="check_name", type="string", length=20, nullable=false, options={"comment"="审核人员名字"})
     */
    private $checkName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="check_status", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="审核状态 1：未通过 2：通过 3：打回"})
     */
    private $checkStatus = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="check_remark", type="string", length=150, nullable=false, options={"comment"="审核备注"})
     */
    private $checkRemark = '';

    /**
     * @var string
     *
     * @ORM\Column(name="check_feedback", type="string", length=150, nullable=false, options={"comment"="审核反馈"})
     */
    private $checkFeedback = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="check_update_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="最近一次修改时间"})
     */
    private $checkUpdateTime = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="check_add_time", type="datetime", nullable=false, options={"comment"="记录生成时间"})
     */
    private $checkAddTime;

    /**
     * @var int
     *
     * @ORM\Column(name="check_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $checkId;



    /**
     * Set partnerId.
     *
     * @param int $partnerId
     *
     * @return YsPartnerCheck
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
     * @return YsPartnerCheck
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
     * Set checkStatus.
     *
     * @param string $checkStatus
     *
     * @return YsPartnerCheck
     */
    public function setCheckStatus($checkStatus)
    {
        $this->checkStatus = $checkStatus;

        return $this;
    }

    /**
     * Get checkStatus.
     *
     * @return string
     */
    public function getCheckStatus()
    {
        return $this->checkStatus;
    }

    /**
     * Set checkRemark.
     *
     * @param string $checkRemark
     *
     * @return YsPartnerCheck
     */
    public function setCheckRemark($checkRemark)
    {
        $this->checkRemark = $checkRemark;

        return $this;
    }

    /**
     * Get checkRemark.
     *
     * @return string
     */
    public function getCheckRemark()
    {
        return $this->checkRemark;
    }

    /**
     * Set checkFeedback.
     *
     * @param string $checkFeedback
     *
     * @return YsPartnerCheck
     */
    public function setCheckFeedback($checkFeedback)
    {
        $this->checkFeedback = $checkFeedback;

        return $this;
    }

    /**
     * Get checkFeedback.
     *
     * @return string
     */
    public function getCheckFeedback()
    {
        return $this->checkFeedback;
    }

    /**
     * Set checkUpdateTime.
     *
     * @param \DateTime $checkUpdateTime
     *
     * @return YsPartnerCheck
     */
    public function setCheckUpdateTime($checkUpdateTime)
    {
        $this->checkUpdateTime = $checkUpdateTime;

        return $this;
    }

    /**
     * Get checkUpdateTime.
     *
     * @return \DateTime
     */
    public function getCheckUpdateTime()
    {
        return $this->checkUpdateTime;
    }

    /**
     * Set checkAddTime.
     *
     * @param \DateTime $checkAddTime
     *
     * @return YsPartnerCheck
     */
    public function setCheckAddTime($checkAddTime)
    {
        $this->checkAddTime = $checkAddTime;

        return $this;
    }

    /**
     * Get checkAddTime.
     *
     * @return \DateTime
     */
    public function getCheckAddTime()
    {
        return $this->checkAddTime;
    }

    /**
     * Get checkId.
     *
     * @return int
     */
    public function getCheckId()
    {
        return $this->checkId;
    }
}
