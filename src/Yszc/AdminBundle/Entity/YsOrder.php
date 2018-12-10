<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsOrder
 *
 * @ORM\Table(name="qy_order", uniqueConstraints={@ORM\UniqueConstraint(name="order_no", columns={"order_no"})})
 * @ORM\Entity
 */
class YsOrder
{
    /**
     * @var string
     *
     * @ORM\Column(name="order_no", type="string", length=28, nullable=false, options={"comment"="订单编号"})
     */
    private $orderNo;

    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=45, nullable=false, options={"comment"="用户code"})
     */
    private $uCode;

    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"comment"="商户ID"})
     */
    private $partnerId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="invite_code", type="string", length=15, nullable=true, options={"comment"="邀请码"})
     */
    private $inviteCode;

    /**
     * @var int
     *
     * @ORM\Column(name="gc_id", type="integer", nullable=false, options={"comment"="商品类别id"})
     */
    private $gcId;

    /**
     * @var string
     *
     * @ORM\Column(name="u_true_name", type="string", length=60, nullable=false, options={"comment"="真实姓名"})
     */
    private $uTrueName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="u_phone", type="string", length=11, nullable=true, options={"comment"="手机号"})
     */
    private $uPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="u_ident_no", type="string", length=18, nullable=false, options={"comment"="身份证号"})
     */
    private $uIdentNo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="u_card_no", type="string", length=20, nullable=true, options={"comment"="银行卡号"})
     */
    private $uCardNo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="u_open_bank", type="string", length=150, nullable=true, options={"comment"="开户行"})
     */
    private $uOpenBank;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee", type="string", length=60, nullable=false, options={"comment"="收货人"})
     */
    private $consignee;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_mbl", type="string", length=15, nullable=false, options={"comment"="收货人手机号"})
     */
    private $consigneeMbl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_province", type="string", length=80, nullable=true, options={"comment"="下单所在省"})
     */
    private $orderProvince;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_city", type="string", length=120, nullable=true, options={"comment"="下单所在市"})
     */
    private $orderCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_area", type="string", length=150, nullable=true, options={"comment"="所在区县"})
     */
    private $orderArea;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_address", type="string", length=120, nullable=true, options={"comment"="下单所在详细地址"})
     */
    private $orderAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_add_time", type="datetime", nullable=false, options={"comment"="订单生成时间"})
     */
    private $orderAddTime;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=2, nullable=false, options={"comment"="订单状态【0待确认, 1待审核,2待支付，3已支付，4发货中，5已发货，6退款处理中，7已退款，8已收货，9已取消，10已完成,11待还款,12还款确认中，13支付处理中】"})
     */
    private $orderStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="order_pay_type", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="支付方式【1线下全款，2线上全款，3赊购】"})
     */
    private $orderPayType = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="order_amount", type="decimal", precision=13, scale=2, nullable=false, options={"comment"="订单金额"})
     */
    private $orderAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="order_deposit", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="保证金"})
     */
    private $orderDeposit = '0.00';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="order_deposit_pay_time", type="datetime", nullable=true, options={"comment"="保证金交纳时间"})
     */
    private $orderDepositPayTime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_advance_payment", type="decimal", precision=13, scale=2, nullable=true, options={"default"="0.00","comment"="首付款"})
     */
    private $orderAdvancePayment = '0.00';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="order_advance_payment_time", type="datetime", nullable=true, options={"comment"="首付款付款时间"})
     */
    private $orderAdvancePaymentTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="order_pay_time", type="datetime", nullable=true, options={"comment"="支付时间"})
     */
    private $orderPayTime;

    /**
     * @var string
     *
     * @ORM\Column(name="order_fat_pay_amount", type="decimal", precision=13, scale=2, nullable=false, options={"comment"="实际支付金额"})
     */
    private $orderFatPayAmount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_coupon_ids", type="string", length=100, nullable=true, options={"comment"="(预留)优惠券id【逗号分隔】"})
     */
    private $orderCouponIds;

    /**
     * @var string|null
     *
     * @ORM\Column(name="buy_up_amount", type="decimal", precision=13, scale=2, nullable=true, options={"default"="0.00","comment"="满减金额"})
     */
    private $buyUpAmount = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="order_delivery_addr", type="string", length=300, nullable=false, options={"comment"="送货地址"})
     */
    private $orderDeliveryAddr;

    /**
     * @var string
     *
     * @ORM\Column(name="order_delivery_type", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="送货方式【1配送，2自提】"})
     */
    private $orderDeliveryType = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="order_delivery_fee", type="decimal", precision=8, scale=2, nullable=false, options={"default"="0.00","comment"="送货费用"})
     */
    private $orderDeliveryFee = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="order_is_settlement", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="是否结算"})
     */
    private $orderIsSettlement = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="order_settlement_amt", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="已结算金额"})
     */
    private $orderSettlementAmt = '0.00';

    /**
     * @var string|null
     *
     * @ORM\Column(name="order_remark", type="string", length=150, nullable=true, options={"comment"="备注"})
     */
    private $orderRemark;

    /**
     * @var string
     *
     * @ORM\Column(name="pay_result_msg", type="string", length=100, nullable=false, options={"comment"="支付结果描述"})
     */
    private $payResultMsg = '';

    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderId;



    /**
     * Set orderNo.
     *
     * @param string $orderNo
     *
     * @return YsOrder
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    /**
     * Get orderNo.
     *
     * @return string
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsOrder
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
     * Set partnerId.
     *
     * @param int $partnerId
     *
     * @return YsOrder
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
     * Set inviteCode.
     *
     * @param string|null $inviteCode
     *
     * @return YsOrder
     */
    public function setInviteCode($inviteCode = null)
    {
        $this->inviteCode = $inviteCode;

        return $this;
    }

    /**
     * Get inviteCode.
     *
     * @return string|null
     */
    public function getInviteCode()
    {
        return $this->inviteCode;
    }

    /**
     * Set gcId.
     *
     * @param int $gcId
     *
     * @return YsOrder
     */
    public function setGcId($gcId)
    {
        $this->gcId = $gcId;

        return $this;
    }

    /**
     * Get gcId.
     *
     * @return int
     */
    public function getGcId()
    {
        return $this->gcId;
    }

    /**
     * Set uTrueName.
     *
     * @param string $uTrueName
     *
     * @return YsOrder
     */
    public function setUTrueName($uTrueName)
    {
        $this->uTrueName = $uTrueName;

        return $this;
    }

    /**
     * Get uTrueName.
     *
     * @return string
     */
    public function getUTrueName()
    {
        return $this->uTrueName;
    }

    /**
     * Set uPhone.
     *
     * @param string|null $uPhone
     *
     * @return YsOrder
     */
    public function setUPhone($uPhone = null)
    {
        $this->uPhone = $uPhone;

        return $this;
    }

    /**
     * Get uPhone.
     *
     * @return string|null
     */
    public function getUPhone()
    {
        return $this->uPhone;
    }

    /**
     * Set uIdentNo.
     *
     * @param string $uIdentNo
     *
     * @return YsOrder
     */
    public function setUIdentNo($uIdentNo)
    {
        $this->uIdentNo = $uIdentNo;

        return $this;
    }

    /**
     * Get uIdentNo.
     *
     * @return string
     */
    public function getUIdentNo()
    {
        return $this->uIdentNo;
    }

    /**
     * Set uCardNo.
     *
     * @param string|null $uCardNo
     *
     * @return YsOrder
     */
    public function setUCardNo($uCardNo = null)
    {
        $this->uCardNo = $uCardNo;

        return $this;
    }

    /**
     * Get uCardNo.
     *
     * @return string|null
     */
    public function getUCardNo()
    {
        return $this->uCardNo;
    }

    /**
     * Set uOpenBank.
     *
     * @param string|null $uOpenBank
     *
     * @return YsOrder
     */
    public function setUOpenBank($uOpenBank = null)
    {
        $this->uOpenBank = $uOpenBank;

        return $this;
    }

    /**
     * Get uOpenBank.
     *
     * @return string|null
     */
    public function getUOpenBank()
    {
        return $this->uOpenBank;
    }

    /**
     * Set consignee.
     *
     * @param string $consignee
     *
     * @return YsOrder
     */
    public function setConsignee($consignee)
    {
        $this->consignee = $consignee;

        return $this;
    }

    /**
     * Get consignee.
     *
     * @return string
     */
    public function getConsignee()
    {
        return $this->consignee;
    }

    /**
     * Set consigneeMbl.
     *
     * @param string $consigneeMbl
     *
     * @return YsOrder
     */
    public function setConsigneeMbl($consigneeMbl)
    {
        $this->consigneeMbl = $consigneeMbl;

        return $this;
    }

    /**
     * Get consigneeMbl.
     *
     * @return string
     */
    public function getConsigneeMbl()
    {
        return $this->consigneeMbl;
    }

    /**
     * Set orderProvince.
     *
     * @param string|null $orderProvince
     *
     * @return YsOrder
     */
    public function setOrderProvince($orderProvince = null)
    {
        $this->orderProvince = $orderProvince;

        return $this;
    }

    /**
     * Get orderProvince.
     *
     * @return string|null
     */
    public function getOrderProvince()
    {
        return $this->orderProvince;
    }

    /**
     * Set orderCity.
     *
     * @param string|null $orderCity
     *
     * @return YsOrder
     */
    public function setOrderCity($orderCity = null)
    {
        $this->orderCity = $orderCity;

        return $this;
    }

    /**
     * Get orderCity.
     *
     * @return string|null
     */
    public function getOrderCity()
    {
        return $this->orderCity;
    }

    /**
     * Set orderArea.
     *
     * @param string|null $orderArea
     *
     * @return YsOrder
     */
    public function setOrderArea($orderArea = null)
    {
        $this->orderArea = $orderArea;

        return $this;
    }

    /**
     * Get orderArea.
     *
     * @return string|null
     */
    public function getOrderArea()
    {
        return $this->orderArea;
    }

    /**
     * Set orderAddress.
     *
     * @param string|null $orderAddress
     *
     * @return YsOrder
     */
    public function setOrderAddress($orderAddress = null)
    {
        $this->orderAddress = $orderAddress;

        return $this;
    }

    /**
     * Get orderAddress.
     *
     * @return string|null
     */
    public function getOrderAddress()
    {
        return $this->orderAddress;
    }

    /**
     * Set orderAddTime.
     *
     * @param \DateTime $orderAddTime
     *
     * @return YsOrder
     */
    public function setOrderAddTime($orderAddTime)
    {
        $this->orderAddTime = $orderAddTime;

        return $this;
    }

    /**
     * Get orderAddTime.
     *
     * @return \DateTime
     */
    public function getOrderAddTime()
    {
        return $this->orderAddTime;
    }

    /**
     * Set orderStatus.
     *
     * @param string $orderStatus
     *
     * @return YsOrder
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus.
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set orderPayType.
     *
     * @param string $orderPayType
     *
     * @return YsOrder
     */
    public function setOrderPayType($orderPayType)
    {
        $this->orderPayType = $orderPayType;

        return $this;
    }

    /**
     * Get orderPayType.
     *
     * @return string
     */
    public function getOrderPayType()
    {
        return $this->orderPayType;
    }

    /**
     * Set orderAmount.
     *
     * @param string $orderAmount
     *
     * @return YsOrder
     */
    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;

        return $this;
    }

    /**
     * Get orderAmount.
     *
     * @return string
     */
    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    /**
     * Set orderDeposit.
     *
     * @param string $orderDeposit
     *
     * @return YsOrder
     */
    public function setOrderDeposit($orderDeposit)
    {
        $this->orderDeposit = $orderDeposit;

        return $this;
    }

    /**
     * Get orderDeposit.
     *
     * @return string
     */
    public function getOrderDeposit()
    {
        return $this->orderDeposit;
    }

    /**
     * Set orderDepositPayTime.
     *
     * @param \DateTime|null $orderDepositPayTime
     *
     * @return YsOrder
     */
    public function setOrderDepositPayTime($orderDepositPayTime = null)
    {
        $this->orderDepositPayTime = $orderDepositPayTime;

        return $this;
    }

    /**
     * Get orderDepositPayTime.
     *
     * @return \DateTime|null
     */
    public function getOrderDepositPayTime()
    {
        return $this->orderDepositPayTime;
    }

    /**
     * Set orderAdvancePayment.
     *
     * @param string|null $orderAdvancePayment
     *
     * @return YsOrder
     */
    public function setOrderAdvancePayment($orderAdvancePayment = null)
    {
        $this->orderAdvancePayment = $orderAdvancePayment;

        return $this;
    }

    /**
     * Get orderAdvancePayment.
     *
     * @return string|null
     */
    public function getOrderAdvancePayment()
    {
        return $this->orderAdvancePayment;
    }

    /**
     * Set orderAdvancePaymentTime.
     *
     * @param \DateTime|null $orderAdvancePaymentTime
     *
     * @return YsOrder
     */
    public function setOrderAdvancePaymentTime($orderAdvancePaymentTime = null)
    {
        $this->orderAdvancePaymentTime = $orderAdvancePaymentTime;

        return $this;
    }

    /**
     * Get orderAdvancePaymentTime.
     *
     * @return \DateTime|null
     */
    public function getOrderAdvancePaymentTime()
    {
        return $this->orderAdvancePaymentTime;
    }

    /**
     * Set orderPayTime.
     *
     * @param \DateTime|null $orderPayTime
     *
     * @return YsOrder
     */
    public function setOrderPayTime($orderPayTime = null)
    {
        $this->orderPayTime = $orderPayTime;

        return $this;
    }

    /**
     * Get orderPayTime.
     *
     * @return \DateTime|null
     */
    public function getOrderPayTime()
    {
        return $this->orderPayTime;
    }

    /**
     * Set orderFatPayAmount.
     *
     * @param string $orderFatPayAmount
     *
     * @return YsOrder
     */
    public function setOrderFatPayAmount($orderFatPayAmount)
    {
        $this->orderFatPayAmount = $orderFatPayAmount;

        return $this;
    }

    /**
     * Get orderFatPayAmount.
     *
     * @return string
     */
    public function getOrderFatPayAmount()
    {
        return $this->orderFatPayAmount;
    }

    /**
     * Set orderCouponIds.
     *
     * @param string|null $orderCouponIds
     *
     * @return YsOrder
     */
    public function setOrderCouponIds($orderCouponIds = null)
    {
        $this->orderCouponIds = $orderCouponIds;

        return $this;
    }

    /**
     * Get orderCouponIds.
     *
     * @return string|null
     */
    public function getOrderCouponIds()
    {
        return $this->orderCouponIds;
    }

    /**
     * Set buyUpAmount.
     *
     * @param string|null $buyUpAmount
     *
     * @return YsOrder
     */
    public function setBuyUpAmount($buyUpAmount = null)
    {
        $this->buyUpAmount = $buyUpAmount;

        return $this;
    }

    /**
     * Get buyUpAmount.
     *
     * @return string|null
     */
    public function getBuyUpAmount()
    {
        return $this->buyUpAmount;
    }

    /**
     * Set orderDeliveryAddr.
     *
     * @param string $orderDeliveryAddr
     *
     * @return YsOrder
     */
    public function setOrderDeliveryAddr($orderDeliveryAddr)
    {
        $this->orderDeliveryAddr = $orderDeliveryAddr;

        return $this;
    }

    /**
     * Get orderDeliveryAddr.
     *
     * @return string
     */
    public function getOrderDeliveryAddr()
    {
        return $this->orderDeliveryAddr;
    }

    /**
     * Set orderDeliveryType.
     *
     * @param string $orderDeliveryType
     *
     * @return YsOrder
     */
    public function setOrderDeliveryType($orderDeliveryType)
    {
        $this->orderDeliveryType = $orderDeliveryType;

        return $this;
    }

    /**
     * Get orderDeliveryType.
     *
     * @return string
     */
    public function getOrderDeliveryType()
    {
        return $this->orderDeliveryType;
    }

    /**
     * Set orderDeliveryFee.
     *
     * @param string $orderDeliveryFee
     *
     * @return YsOrder
     */
    public function setOrderDeliveryFee($orderDeliveryFee)
    {
        $this->orderDeliveryFee = $orderDeliveryFee;

        return $this;
    }

    /**
     * Get orderDeliveryFee.
     *
     * @return string
     */
    public function getOrderDeliveryFee()
    {
        return $this->orderDeliveryFee;
    }

    /**
     * Set orderIsSettlement.
     *
     * @param string $orderIsSettlement
     *
     * @return YsOrder
     */
    public function setOrderIsSettlement($orderIsSettlement)
    {
        $this->orderIsSettlement = $orderIsSettlement;

        return $this;
    }

    /**
     * Get orderIsSettlement.
     *
     * @return string
     */
    public function getOrderIsSettlement()
    {
        return $this->orderIsSettlement;
    }

    /**
     * Set orderSettlementAmt.
     *
     * @param string $orderSettlementAmt
     *
     * @return YsOrder
     */
    public function setOrderSettlementAmt($orderSettlementAmt)
    {
        $this->orderSettlementAmt = $orderSettlementAmt;

        return $this;
    }

    /**
     * Get orderSettlementAmt.
     *
     * @return string
     */
    public function getOrderSettlementAmt()
    {
        return $this->orderSettlementAmt;
    }

    /**
     * Set orderRemark.
     *
     * @param string|null $orderRemark
     *
     * @return YsOrder
     */
    public function setOrderRemark($orderRemark = null)
    {
        $this->orderRemark = $orderRemark;

        return $this;
    }

    /**
     * Get orderRemark.
     *
     * @return string|null
     */
    public function getOrderRemark()
    {
        return $this->orderRemark;
    }

    /**
     * Set payResultMsg.
     *
     * @param string $payResultMsg
     *
     * @return YsOrder
     */
    public function setPayResultMsg($payResultMsg)
    {
        $this->payResultMsg = $payResultMsg;

        return $this;
    }

    /**
     * Get payResultMsg.
     *
     * @return string
     */
    public function getPayResultMsg()
    {
        return $this->payResultMsg;
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
}
