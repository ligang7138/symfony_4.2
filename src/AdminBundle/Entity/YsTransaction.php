<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsTransaction
 *
 * @ORM\Table(name="qy_transaction")
 * @ORM\Entity
 */
class YsTransaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false, options={"comment"="订单id"})
     */
    private $orderId;

    /**
     * @var string
     *
     * @ORM\Column(name="t_amount", type="decimal", precision=13, scale=2, nullable=false, options={"default"="0.00","comment"="交易金额"})
     */
    private $tAmount = '0.00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="t_time", type="datetime", nullable=false, options={"comment"="交易时间"})
     */
    private $tTime;

    /**
     * @var string
     *
     * @ORM\Column(name="t_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="交易状态【1待支付，2线上支付成功，3线下支付，4信用支付，5支付取消,6支付处理中,7支付失败】"})
     */
    private $tStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=45, nullable=false, options={"comment"="用户code"})
     */
    private $uCode;

    /**
     * @var string
     *
     * @ORM\Column(name="t_msg", type="string", length=300, nullable=false, options={"comment"="交易说明"})
     */
    private $tMsg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_serial_num", type="string", length=45, nullable=true, options={"comment"="交易流水号"})
     */
    private $orderSerialNum;

    /**
     * @var int
     *
     * @ORM\Column(name="t_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tId;



    /**
     * Set orderId.
     *
     * @param int $orderId
     *
     * @return YsTransaction
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
     * Set tAmount.
     *
     * @param string $tAmount
     *
     * @return YsTransaction
     */
    public function setTAmount($tAmount)
    {
        $this->tAmount = $tAmount;

        return $this;
    }

    /**
     * Get tAmount.
     *
     * @return string
     */
    public function getTAmount()
    {
        return $this->tAmount;
    }

    /**
     * Set tTime.
     *
     * @param \DateTime $tTime
     *
     * @return YsTransaction
     */
    public function setTTime($tTime)
    {
        $this->tTime = $tTime;

        return $this;
    }

    /**
     * Get tTime.
     *
     * @return \DateTime
     */
    public function getTTime()
    {
        return $this->tTime;
    }

    /**
     * Set tStatus.
     *
     * @param string $tStatus
     *
     * @return YsTransaction
     */
    public function setTStatus($tStatus)
    {
        $this->tStatus = $tStatus;

        return $this;
    }

    /**
     * Get tStatus.
     *
     * @return string
     */
    public function getTStatus()
    {
        return $this->tStatus;
    }

    /**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsTransaction
     */
    public function setUCode($uCode)
    {
        $this->uCode = $uCode;

        return $this;
    }

    /**
     * Get uCode.
     *
     * @return string
     */
    public function getUCode()
    {
        return $this->uCode;
    }

    /**
     * Set tMsg.
     *
     * @param string $tMsg
     *
     * @return YsTransaction
     */
    public function setTMsg($tMsg)
    {
        $this->tMsg = $tMsg;

        return $this;
    }

    /**
     * Get tMsg.
     *
     * @return string
     */
    public function getTMsg()
    {
        return $this->tMsg;
    }

    /**
     * Set orderSerialNum.
     *
     * @param string|null $orderSerialNum
     *
     * @return YsTransaction
     */
    public function setOrderSerialNum($orderSerialNum = null)
    {
        $this->orderSerialNum = $orderSerialNum;

        return $this;
    }

    /**
     * Get orderSerialNum.
     *
     * @return string|null
     */
    public function getOrderSerialNum()
    {
        return $this->orderSerialNum;
    }

    /**
     * Get tId.
     *
     * @return int
     */
    public function getTId()
    {
        return $this->tId;
    }
}
