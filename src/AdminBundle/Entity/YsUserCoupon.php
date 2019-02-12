<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsUserCoupon
 *
 * @ORM\Table(name="qy_user_coupon")
 * @ORM\Entity
 */
class YsUserCoupon
{
    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=45, nullable=false, options={"comment"="用户code"})
     */
    private $uCode;

    /**
     * @var int
     *
     * @ORM\Column(name="pc_id", type="integer", nullable=false, options={"comment"="商户优惠券ID"})
     */
    private $pcId;

    /**
     * @var string
     *
     * @ORM\Column(name="uc_type", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="优惠券/红包类型【0满减，1抵现】"})
     */
    private $ucType;

    /**
     * @var string
     *
     * @ORM\Column(name="uc_use_amt", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="要求使用金额"})
     */
    private $ucUseAmt = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="uc_coupon_amt", type="decimal", precision=10, scale=2, nullable=false, options={"comment"="优惠券金额"})
     */
    private $ucCouponAmt;

    /**
     * @var string
     *
     * @ORM\Column(name="uc_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="状态【0有效，1失效】"})
     */
    private $ucStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="uc_end_time", type="decimal", precision=10, scale=0, nullable=false, options={"comment"="结束时间"})
     */
    private $ucEndTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uc_add_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $ucAddTime = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="uc_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ucId;



    /**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsUserCoupon
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
     * Set pcId.
     *
     * @param int $pcId
     *
     * @return YsUserCoupon
     */
    public function setPcId($pcId)
    {
        $this->pcId = $pcId;

        return $this;
    }

    /**
     * Get pcId.
     *
     * @return int
     */
    public function getPcId()
    {
        return $this->pcId;
    }

    /**
     * Set ucType.
     *
     * @param string $ucType
     *
     * @return YsUserCoupon
     */
    public function setUcType($ucType)
    {
        $this->ucType = $ucType;

        return $this;
    }

    /**
     * Get ucType.
     *
     * @return string
     */
    public function getUcType()
    {
        return $this->ucType;
    }

    /**
     * Set ucUseAmt.
     *
     * @param string $ucUseAmt
     *
     * @return YsUserCoupon
     */
    public function setUcUseAmt($ucUseAmt)
    {
        $this->ucUseAmt = $ucUseAmt;

        return $this;
    }

    /**
     * Get ucUseAmt.
     *
     * @return string
     */
    public function getUcUseAmt()
    {
        return $this->ucUseAmt;
    }

    /**
     * Set ucCouponAmt.
     *
     * @param string $ucCouponAmt
     *
     * @return YsUserCoupon
     */
    public function setUcCouponAmt($ucCouponAmt)
    {
        $this->ucCouponAmt = $ucCouponAmt;

        return $this;
    }

    /**
     * Get ucCouponAmt.
     *
     * @return string
     */
    public function getUcCouponAmt()
    {
        return $this->ucCouponAmt;
    }

    /**
     * Set ucStatus.
     *
     * @param string $ucStatus
     *
     * @return YsUserCoupon
     */
    public function setUcStatus($ucStatus)
    {
        $this->ucStatus = $ucStatus;

        return $this;
    }

    /**
     * Get ucStatus.
     *
     * @return string
     */
    public function getUcStatus()
    {
        return $this->ucStatus;
    }

    /**
     * Set ucEndTime.
     *
     * @param string $ucEndTime
     *
     * @return YsUserCoupon
     */
    public function setUcEndTime($ucEndTime)
    {
        $this->ucEndTime = $ucEndTime;

        return $this;
    }

    /**
     * Get ucEndTime.
     *
     * @return string
     */
    public function getUcEndTime()
    {
        return $this->ucEndTime;
    }

    /**
     * Set ucAddTime.
     *
     * @param \DateTime $ucAddTime
     *
     * @return YsUserCoupon
     */
    public function setUcAddTime($ucAddTime)
    {
        $this->ucAddTime = $ucAddTime;

        return $this;
    }

    /**
     * Get ucAddTime.
     *
     * @return \DateTime
     */
    public function getUcAddTime()
    {
        return $this->ucAddTime;
    }

    /**
     * Get ucId.
     *
     * @return int
     */
    public function getUcId()
    {
        return $this->ucId;
    }
}
