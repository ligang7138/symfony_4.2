<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsRefundRent
 *
 * @ORM\Table(name="qy_refund_rent")
 * @ORM\Entity
 */
class YsRefundRent
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rr_apply_time", type="datetime", nullable=false, options={"comment"="申请时间"})
     */
    private $rrApplyTime;

    /**
     * @var int
     *
     * @ORM\Column(name="b_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="进件ID"})
     */
    private $bId;

    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=60, nullable=false, options={"comment"="申请退款人ID"})
     */
    private $uCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rr_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="退款状态【0处理中，1拒绝退款，2已退款】"})
     */
    private $rrStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="rr_amt", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="实际退款额"})
     */
    private $rrAmt = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="rr_remark", type="string", length=600, nullable=false, options={"comment"="备注"})
     */
    private $rrRemark;

    /**
     * @var string
     *
     * @ORM\Column(name="rr_type", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="退款方式【0线下，1线上】"})
     */
    private $rrType = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rr_op_time", type="datetime", nullable=false, options={"comment"="操作时间"})
     */
    private $rrOpTime;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_id", type="string", length=45, nullable=false, options={"comment"="处理人【后台管理员】"})
     */
    private $adminId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rr_check_remark", type="string", length=300, nullable=true, options={"comment"="备注"})
     */
    private $rrCheckRemark;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_id", type="string", length=20, nullable=true, options={"comment"="订单ID"})
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="rr_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rrId;



    /**
     * Set rrApplyTime.
     *
     * @param \DateTime $rrApplyTime
     *
     * @return YsRefundRent
     */
    public function setRrApplyTime($rrApplyTime)
    {
        $this->rrApplyTime = $rrApplyTime;

        return $this;
    }

    /**
     * Get rrApplyTime.
     *
     * @return \DateTime
     */
    public function getRrApplyTime()
    {
        return $this->rrApplyTime;
    }

    /**
     * Set bId.
     *
     * @param int $bId
     *
     * @return YsRefundRent
     */
    public function setBId($bId)
    {
        $this->bId = $bId;

        return $this;
    }

    /**
     * Get bId.
     *
     * @return int
     */
    public function getBId()
    {
        return $this->bId;
    }

    /**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsRefundRent
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
     * Set rrStatus.
     *
     * @param string $rrStatus
     *
     * @return YsRefundRent
     */
    public function setRrStatus($rrStatus)
    {
        $this->rrStatus = $rrStatus;

        return $this;
    }

    /**
     * Get rrStatus.
     *
     * @return string
     */
    public function getRrStatus()
    {
        return $this->rrStatus;
    }

    /**
     * Set rrAmt.
     *
     * @param string $rrAmt
     *
     * @return YsRefundRent
     */
    public function setRrAmt($rrAmt)
    {
        $this->rrAmt = $rrAmt;

        return $this;
    }

    /**
     * Get rrAmt.
     *
     * @return string
     */
    public function getRrAmt()
    {
        return $this->rrAmt;
    }

    /**
     * Set rrRemark.
     *
     * @param string $rrRemark
     *
     * @return YsRefundRent
     */
    public function setRrRemark($rrRemark)
    {
        $this->rrRemark = $rrRemark;

        return $this;
    }

    /**
     * Get rrRemark.
     *
     * @return string
     */
    public function getRrRemark()
    {
        return $this->rrRemark;
    }

    /**
     * Set rrType.
     *
     * @param string $rrType
     *
     * @return YsRefundRent
     */
    public function setRrType($rrType)
    {
        $this->rrType = $rrType;

        return $this;
    }

    /**
     * Get rrType.
     *
     * @return string
     */
    public function getRrType()
    {
        return $this->rrType;
    }

    /**
     * Set rrOpTime.
     *
     * @param \DateTime $rrOpTime
     *
     * @return YsRefundRent
     */
    public function setRrOpTime($rrOpTime)
    {
        $this->rrOpTime = $rrOpTime;

        return $this;
    }

    /**
     * Get rrOpTime.
     *
     * @return \DateTime
     */
    public function getRrOpTime()
    {
        return $this->rrOpTime;
    }

    /**
     * Set adminId.
     *
     * @param string $adminId
     *
     * @return YsRefundRent
     */
    public function setAdminId($adminId)
    {
        $this->adminId = $adminId;

        return $this;
    }

    /**
     * Get adminId.
     *
     * @return string
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set rrCheckRemark.
     *
     * @param string|null $rrCheckRemark
     *
     * @return YsRefundRent
     */
    public function setRrCheckRemark($rrCheckRemark = null)
    {
        $this->rrCheckRemark = $rrCheckRemark;

        return $this;
    }

    /**
     * Get rrCheckRemark.
     *
     * @return string|null
     */
    public function getRrCheckRemark()
    {
        return $this->rrCheckRemark;
    }

    /**
     * Set orderId.
     *
     * @param string|null $orderId
     *
     * @return YsRefundRent
     */
    public function setOrderId($orderId = null)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId.
     *
     * @return string|null
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Get rrId.
     *
     * @return int
     */
    public function getRrId()
    {
        return $this->rrId;
    }
}
