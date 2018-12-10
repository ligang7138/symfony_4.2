<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsGoods
 *
 * @ORM\Table(name="qy_goods")
 * @ORM\Entity
 */
class YsGoods
{
    /**
     * @var string
     *
     * @ORM\Column(name="g_code", type="string", length=50, nullable=false, options={"comment"="商品编号"})
     */
    private $gCode;

    /**
     * @var int
     *
     * @ORM\Column(name="gc_id", type="integer", nullable=false, options={"comment"="商品类别id"})
     */
    private $gcId;

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false, options={"comment"="管理员id"})
     */
    private $adminId;

    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"comment"="商户ID"})
     */
    private $partnerId;

    /**
     * @var int
     *
     * @ORM\Column(name="gb_id", type="integer", nullable=false, options={"comment"="品牌id"})
     */
    private $gbId;

    /**
     * @var string
     *
     * @ORM\Column(name="g_name", type="string", length=150, nullable=false, options={"comment"="商品名称"})
     */
    private $gName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="g_imgs", type="string", length=1000, nullable=true, options={"comment"="商品图片url【多张以逗号相隔】"})
     */
    private $gImgs;

    /**
     * @var string
     *
     * @ORM\Column(name="g_desc", type="string", length=600, nullable=false, options={"comment"="商品描述"})
     */
    private $gDesc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="g_standard", type="string", length=150, nullable=true, options={"comment"="商品规格[多规格用逗号隔开]"})
     */
    private $gStandard;

    /**
     * @var string
     *
     * @ORM\Column(name="g_status", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="商品状态【1待审核，2待发布，3下架，4发布】"})
     */
    private $gStatus = '1';


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="g_update_time", type="datetime", nullable=false, options={"comment"="商品修改时间"})
     */
    private $gUpdateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="g_add_time", type="datetime", nullable=false, options={"comment"="创建时间"})
     */
    private $gAddTime;

    /**
     * @var string
     *
     * @ORM\Column(name="is_join_activity", type="string", nullable=false, options={"default"="2","comment"="是否参加活动【1参加   2不参加】"})
     */
    private $isJoinActivity = '2';

    /**
     * @var string
     *
     * @ORM\Column(name="g_attribute", type="string", length=300, nullable=false, options={"comment"="商品属性【多属性以逗号相隔】"})
     */
    private $gAttribute = '';

    /**
     * @var int
     *
     * @ORM\Column(name="g_order_num", type="integer", nullable=false, options={"comment"="单笔订单购买数量"})
     */
    private $gOrderNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="gc_top_id", type="integer", nullable=false, options={"comment"="商品分类一级id"})
     */
    private $gcTopId = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="g_check_status", type="integer", nullable=true, options={"comment"="商品审核状态"})
     */
    private $gCheckStatus;

    /**
     * @var int
     *
     * @ORM\Column(name="g_sort", type="integer", nullable=false, options={"comment"="商品排序"})
     */
    private $gSort='0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="g_check_remark", type="string", length=200, nullable=true, options={"comment"="商品审核意见"})
     */
    private $gCheckRemark;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="g_check_time", type="datetime", nullable=true, options={"comment"="商品审核时间"})
     */
    private $gCheckTime;

    /**
     * @var int
     *
     * @ORM\Column(name="g_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $gId;



    /**
     * Set gCode.
     *
     * @param string $gCode
     *
     * @return YsGoods
     */
    public function setGCode($gCode)
    {
        $this->gCode = $gCode;

        return $this;
    }

    /**
     * Get gCode.
     *
     * @return string
     */
    public function getGCode()
    {
        return $this->gCode;
    }

    /**
     * Set gcId.
     *
     * @param int $gcId
     *
     * @return YsGoods
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
     * Set adminId.
     *
     * @param int $adminId
     *
     * @return YsGoods
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
     * Set partnerId.
     *
     * @param int $partnerId
     *
     * @return YsGoods
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
     * Set gbId.
     *
     * @param int $gbId
     *
     * @return YsGoods
     */
    public function setGbId($gbId)
    {
        $this->gbId = $gbId;

        return $this;
    }

    /**
     * Get gbId.
     *
     * @return int
     */
    public function getGbId()
    {
        return $this->gbId;
    }

    /**
     * Set gName.
     *
     * @param string $gName
     *
     * @return YsGoods
     */
    public function setGName($gName)
    {
        $this->gName = $gName;

        return $this;
    }

    /**
     * Get gName.
     *
     * @return string
     */
    public function getGName()
    {
        return $this->gName;
    }

    /**
     * Set gImgs.
     *
     * @param string|null $gImgs
     *
     * @return YsGoods
     */
    public function setGImgs($gImgs = null)
    {
        $this->gImgs = $gImgs;

        return $this;
    }

    /**
     * Get gImgs.
     *
     * @return string|null
     */
    public function getGImgs()
    {
        return $this->gImgs;
    }

    /**
     * Set gDesc.
     *
     * @param string $gDesc
     *
     * @return YsGoods
     */
    public function setGDesc($gDesc)
    {
        $this->gDesc = $gDesc;

        return $this;
    }

    /**
     * Get gDesc.
     *
     * @return string
     */
    public function getGDesc()
    {
        return $this->gDesc;
    }

    /**
     * Set gStandard.
     *
     * @param string|null $gStandard
     *
     * @return YsGoods
     */
    public function setGStandard($gStandard = null)
    {
        $this->gStandard = $gStandard;

        return $this;
    }

    /**
     * Get gStandard.
     *
     * @return string|null
     */
    public function getGStandard()
    {
        return $this->gStandard;
    }

    /**
     * Set gStatus.
     *
     * @param string $gStatus
     *
     * @return YsGoods
     */
    public function setGStatus($gStatus)
    {
        $this->gStatus = $gStatus;

        return $this;
    }

    /**
     * Get gStatus.
     *
     * @return string
     */
    public function getGStatus()
    {
        return $this->gStatus;
    }

    /**
     * Set gOrderNum.
     *
     * @param int $gOrderNum
     *
     * @return YsGoods
     */
    public function setGOrderNum($gOrderNum)
    {
        $this->gOrderNum = $gOrderNum;

        return $this;
    }

    /**
     * Get gOrderNum.
     *
     * @return int
     */
    public function getGOrderNum()
    {
        return $this->gOrderNum;
    }

    /**
     * Set gcTopId.
     *
     * @param int $gcTopId
     *
     * @return YsGoods
     */
    public function setGcTopId($gcTopId)
    {
        $this->gcTopId = $gcTopId;

        return $this;
    }

    /**
     * Get gcTopId.
     *
     * @return int
     */
    public function getGcTopId()
    {
        return $this->gcTopId;
    }

    /**
     * Set gCheckStatus.
     *
     * @param int $gCheckStatus
     *
     * @return YsGoods
     */
    public function setGCheckStatus($gCheckStatus)
    {
        $this->gCheckStatus = $gCheckStatus;

        return $this;
    }

    /**
     * Get gCheckStatus.
     *
     * @return int
     */
    public function getGCheckStatus()
    {
        return $this->gCheckStatus;
    }

    /**
     * Set gSort.
     *
     * @param int $gSort
     *
     * @return YsGoods
     */
    public function setGSort($gSort)
    {
        $this->gSort = $gSort;

        return $this;
    }

    /**
     * Get gSort.
     *
     * @return int
     */
    public function getGSort()
    {
        return $this->gSort;
    }

    /**
     * Set gUpdateTime.
     *
     * @param \DateTime $gUpdateTime
     *
     * @return YsGoods
     */
    public function setGUpdateTime($gUpdateTime)
    {
        $this->gUpdateTime = $gUpdateTime;

        return $this;
    }

    /**
     * Get gUpdateTime.
     *
     * @return \DateTime
     */
    public function getGUpdateTime()
    {
        return $this->gUpdateTime;
    }

    /**
     * Set gAddTime.
     *
     * @param \DateTime $gAddTime
     *
     * @return YsGoods
     */
    public function setGAddTime($gAddTime)
    {
        $this->gAddTime = $gAddTime;

        return $this;
    }

    /**
     * Get gAddTime.
     *
     * @return \DateTime
     */
    public function getGAddTime()
    {
        return $this->gAddTime;
    }

    /**
     * Set gCheckTime.
     *
     * @param \DateTime $gCheckTime
     *
     * @return YsGoods
     */
    public function setGCheckTime($gCheckTime)
    {
        $this->gCheckTime = $gCheckTime;

        return $this;
    }

    /**
     * Get gCheckTime.
     *
     * @return \DateTime
     */
    public function getGCheckTime()
    {
        return $this->gCheckTime;
    }

    /**
     * Set isJoinActivity.
     *
     * @param string $isJoinActivity
     *
     * @return YsGoods
     */
    public function setIsJoinActivity($isJoinActivity)
    {
        $this->isJoinActivity = $isJoinActivity;

        return $this;
    }

    /**
     * Get isJoinActivity.
     *
     * @return string
     */
    public function getIsJoinActivity()
    {
        return $this->isJoinActivity;
    }

    /**
     * Set gAttribute.
     *
     * @param string $gAttribute
     *
     * @return YsGoods
     */
    public function setGAttribute($gAttribute)
    {
        $this->gAttribute = $gAttribute;

        return $this;
    }

    /**
     * Get gAttribute.
     *
     * @return string
     */
    public function getGAttribute()
    {
        return $this->gAttribute;
    }

    /**
     * Set gCheckRemark.
     *
     * @param string $gCheckRemark
     *
     * @return YsGoods
     */
    public function setGCheckRemark($gCheckRemark)
    {
        $this->gCheckRemark = $gCheckRemark;

        return $this;
    }

    /**
     * Get gCheckRemark.
     *
     * @return string
     */
    public function getGCheckRemark()
    {
        return $this->gCheckRemark;
    }

    /**
     * Get gId.
     *
     * @return int
     */
    public function getGId()
    {
        return $this->gId;
    }
}
