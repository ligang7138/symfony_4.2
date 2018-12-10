<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsPartnerCoupon
 *
 * @ORM\Table(name="qy_partner_coupon")
 * @ORM\Entity
 */
class YsPartnerCoupon
{
    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商家id"})
     */
    private $partnerId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="pc_type", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="优惠券/红包类型【0满减，1抵现】"})
     */
    private $pcType = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="pc_amt", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="优惠券额度"})
     */
    private $pcAmt = '0.00';

    /**
     * @var int
     *
     * @ORM\Column(name="pc_nums", type="smallint", nullable=false, options={"comment"="优惠券数量"})
     */
    private $pcNums = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="pc_buy_up", type="smallint", nullable=false, options={"unsigned"=true,"comment"="购满金额"})
     */
    private $pcBuyUp = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="pc_buy_up_subtraction", type="smallint", nullable=false, options={"unsigned"=true,"comment"="购满减金额"})
     */
    private $pcBuyUpSubtraction = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="pc_use_amt", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="可使用优惠券的订单金额"})
     */
    private $pcUseAmt = '0.00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pc_end_time", type="datetime", nullable=false, options={"comment"="活动结束时间"})
     */
    private $pcEndTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pc_start_time", type="datetime", nullable=false, options={"comment"="活动开始时间"})
     */
    private $pcStartTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false, options={"comment"="创建时间"})
     */
    private $createTime;

    /**
     * @var int
     *
     * @ORM\Column(name="pc_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pcId;



    /**
     * Set partnerId.
     *
     * @param int $partnerId
     *
     * @return YsPartnerCoupon
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
     * Set pcType.
     *
     * @param string $pcType
     *
     * @return YsPartnerCoupon
     */
    public function setPcType($pcType)
    {
        $this->pcType = $pcType;

        return $this;
    }

    /**
     * Get pcType.
     *
     * @return string
     */
    public function getPcType()
    {
        return $this->pcType;
    }

    /**
     * Set pcAmt.
     *
     * @param string $pcAmt
     *
     * @return YsPartnerCoupon
     */
    public function setPcAmt($pcAmt)
    {
        $this->pcAmt = $pcAmt;

        return $this;
    }

    /**
     * Get pcAmt.
     *
     * @return string
     */
    public function getPcAmt()
    {
        return $this->pcAmt;
    }

    /**
     * Set pcNums.
     *
     * @param int $pcNums
     *
     * @return YsPartnerCoupon
     */
    public function setPcNums($pcNums)
    {
        $this->pcNums = $pcNums;

        return $this;
    }

    /**
     * Get pcNums.
     *
     * @return int
     */
    public function getPcNums()
    {
        return $this->pcNums;
    }

    /**
     * Set pcBuyUp.
     *
     * @param int $pcBuyUp
     *
     * @return YsPartnerCoupon
     */
    public function setPcBuyUp($pcBuyUp)
    {
        $this->pcBuyUp = $pcBuyUp;

        return $this;
    }

    /**
     * Get pcBuyUp.
     *
     * @return int
     */
    public function getPcBuyUp()
    {
        return $this->pcBuyUp;
    }

    /**
     * Set pcBuyUpSubtraction.
     *
     * @param int $pcBuyUpSubtraction
     *
     * @return YsPartnerCoupon
     */
    public function setPcBuyUpSubtraction($pcBuyUpSubtraction)
    {
        $this->pcBuyUpSubtraction = $pcBuyUpSubtraction;

        return $this;
    }

    /**
     * Get pcBuyUpSubtraction.
     *
     * @return int
     */
    public function getPcBuyUpSubtraction()
    {
        return $this->pcBuyUpSubtraction;
    }

    /**
     * Set pcUseAmt.
     *
     * @param string $pcUseAmt
     *
     * @return YsPartnerCoupon
     */
    public function setPcUseAmt($pcUseAmt)
    {
        $this->pcUseAmt = $pcUseAmt;

        return $this;
    }

    /**
     * Get pcUseAmt.
     *
     * @return string
     */
    public function getPcUseAmt()
    {
        return $this->pcUseAmt;
    }

    /**
     * Set pcEndTime.
     *
     * @param \DateTime $pcEndTime
     *
     * @return YsPartnerCoupon
     */
    public function setPcEndTime($pcEndTime)
    {
        $this->pcEndTime = $pcEndTime;

        return $this;
    }

    /**
     * Get pcEndTime.
     *
     * @return \DateTime
     */
    public function getPcEndTime()
    {
        return $this->pcEndTime;
    }

    /**
     * Set pcStartTime.
     *
     * @param \DateTime $pcStartTime
     *
     * @return YsPartnerCoupon
     */
    public function setPcStartTime($pcStartTime)
    {
        $this->pcStartTime = $pcStartTime;

        return $this;
    }

    /**
     * Get pcStartTime.
     *
     * @return \DateTime
     */
    public function getPcStartTime()
    {
        return $this->pcStartTime;
    }

    /**
     * Set createTime.
     *
     * @param \DateTime $createTime
     *
     * @return YsPartnerCoupon
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
     * Get pcId.
     *
     * @return int
     */
    public function getPcId()
    {
        return $this->pcId;
    }
}
