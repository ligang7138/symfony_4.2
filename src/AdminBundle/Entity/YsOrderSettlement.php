<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsOrderSettlement
 *
 * @ORM\Table(name="ys_order_settlement")
 * @ORM\Entity
 */
class YsOrderSettlement
{
    /**
     * @var string
     *
     * @ORM\Column(name="order_id", type="string", length=1000, nullable=false, options={"comment"="结算订单编号"})
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"comment"="商户ID"})
     */
    private $partnerId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="os_time", type="datetime", nullable=true)
     */
    private $osTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="os_apply_time", type="datetime", nullable=false, options={"comment"="提现申请时间"})
     */
    private $osApplyTime;

    /**
     * @var string
     *
     * @ORM\Column(name="os_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="状态【0待结算，1已结算，2审核拒绝】"})
     */
    private $osStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="os_amount", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00"})
     */
    private $osAmount = '0.00';

    /**
     * @var string|null
     *
     * @ORM\Column(name="os_name", type="string", length=45, nullable=true, options={"comment"="结算人"})
     */
    private $osName;

    /**
     * @var string
     *
     * @ORM\Column(name="os_channel", type="string", length=1, nullable=true, options={"fixed"=true,"comment"="0线下，1线上"})
     */
    private $osChannel = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="os_remark", type="string", length=300, nullable=true, options={"comment"="备注"})
     */
    private $osRemark;

    /**
     * @var int
     *
     * @ORM\Column(name="os_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $osId;



    /**
     * Set orderId.
     *
     * @param string $orderId
     *
     * @return YsOrderSettlement
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set partnerId.
     *
     * @param int $partnerId
     *
     * @return YsOrderSettlement
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
     * Set osTime.
     *
     * @param \DateTime|null $osTime
     *
     * @return YsOrderSettlement
     */
    public function setOsTime($osTime = null)
    {
        $this->osTime = $osTime;

        return $this;
    }

    /**
     * Get osTime.
     *
     * @return \DateTime|null
     */
    public function getOsTime()
    {
        return $this->osTime;
    }

    /**
     * Set osApplyTime.
     *
     * @param \DateTime $osApplyTime
     *
     * @return YsOrderSettlement
     */
    public function setOsApplyTime($osApplyTime)
    {
        $this->osApplyTime = $osApplyTime;

        return $this;
    }

    /**
     * Get osApplyTime.
     *
     * @return \DateTime
     */
    public function getOsApplyTime()
    {
        return $this->osApplyTime;
    }

    /**
     * Set osStatus.
     *
     * @param string $osStatus
     *
     * @return YsOrderSettlement
     */
    public function setOsStatus($osStatus)
    {
        $this->osStatus = $osStatus;

        return $this;
    }

    /**
     * Get osStatus.
     *
     * @return string
     */
    public function getOsStatus()
    {
        return $this->osStatus;
    }

    /**
     * Set osAmount.
     *
     * @param string $osAmount
     *
     * @return YsOrderSettlement
     */
    public function setOsAmount($osAmount)
    {
        $this->osAmount = $osAmount;

        return $this;
    }

    /**
     * Get osAmount.
     *
     * @return string
     */
    public function getOsAmount()
    {
        return $this->osAmount;
    }

    /**
     * Set osName.
     *
     * @param string|null $osName
     *
     * @return YsOrderSettlement
     */
    public function setOsName($osName = null)
    {
        $this->osName = $osName;

        return $this;
    }

    /**
     * Get osName.
     *
     * @return string|null
     */
    public function getOsName()
    {
        return $this->osName;
    }

    /**
     * Set osChannel.
     *
     * @param string $osChannel
     *
     * @return YsOrderSettlement
     */
    public function setOsChannel($osChannel)
    {
        $this->osChannel = $osChannel;

        return $this;
    }

    /**
     * Get osChannel.
     *
     * @return string
     */
    public function getOsChannel()
    {
        return $this->osChannel;
    }

    /**
     * Set osRemark.
     *
     * @param string|null $osRemark
     *
     * @return YsOrderSettlement
     */
    public function setOsRemark($osRemark = null)
    {
        $this->osRemark = $osRemark;

        return $this;
    }

    /**
     * Get osRemark.
     *
     * @return string|null
     */
    public function getOsRemark()
    {
        return $this->osRemark;
    }

    /**
     * Get osId.
     *
     * @return int
     */
    public function getOsId()
    {
        return $this->osId;
    }
}
