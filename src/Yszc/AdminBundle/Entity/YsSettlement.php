<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsSettlement
 *
 * @ORM\Table(name="qy_settlement")
 * @ORM\Entity
 */
class YsSettlement
{
    /**
     * @var string
     *
     * @ORM\Column(name="s_amount", type="decimal", precision=13, scale=2, nullable=false, options={"default"="0.00","comment"="结算金额"})
     */
    private $sAmount = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="s_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="结算状态【0未结算，1已结算，2部分结算】"})
     */
    private $sStatus = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="s_time", type="datetime", nullable=false, options={"comment"="结算时间"})
     */
    private $sTime;

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false, options={"comment"="结算人"})
     */
    private $adminId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false, options={"comment"="结算订单"})
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="s_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sId;



    /**
     * Set sAmount.
     *
     * @param string $sAmount
     *
     * @return YsSettlement
     */
    public function setSAmount($sAmount)
    {
        $this->sAmount = $sAmount;

        return $this;
    }

    /**
     * Get sAmount.
     *
     * @return string
     */
    public function getSAmount()
    {
        return $this->sAmount;
    }

    /**
     * Set sStatus.
     *
     * @param string $sStatus
     *
     * @return YsSettlement
     */
    public function setSStatus($sStatus)
    {
        $this->sStatus = $sStatus;

        return $this;
    }

    /**
     * Get sStatus.
     *
     * @return string
     */
    public function getSStatus()
    {
        return $this->sStatus;
    }

    /**
     * Set sTime.
     *
     * @param \DateTime $sTime
     *
     * @return YsSettlement
     */
    public function setSTime($sTime)
    {
        $this->sTime = $sTime;

        return $this;
    }

    /**
     * Get sTime.
     *
     * @return \DateTime
     */
    public function getSTime()
    {
        return $this->sTime;
    }

    /**
     * Set adminId.
     *
     * @param int $adminId
     *
     * @return YsSettlement
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
     * Set orderId.
     *
     * @param int $orderId
     *
     * @return YsSettlement
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId.
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Get sId.
     *
     * @return int
     */
    public function getSId()
    {
        return $this->sId;
    }
}
