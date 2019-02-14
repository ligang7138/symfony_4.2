<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsGoodsCate
 *
 * @ORM\Table(name="qy_goods_cate", uniqueConstraints={@ORM\UniqueConstraint(name="index_gc_name", columns={"gc_name"})})
 * @ORM\Entity
 */
class YsGoodsCate
{
    /**
     * @var int
     *
     * @ORM\Column(name="gc_node", type="integer", nullable=false, options={"comment"="根节点"})
     */
    private $gcNode = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false, options={"comment"="管理员id"})
     */
    private $adminId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="p_id", type="integer", nullable=true, options={"comment"="产品id【对应信贷系统产品表，一级分类存在】"})
     */
    private $pId;

    /**
     * @var string
     *
     * @ORM\Column(name="gc_name", type="string", length=150, nullable=false, options={"comment"="分类名称"})
     */
    private $gcName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gc_add_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="添加时间"})
     */
    private $gcAddTime = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gc_update_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="更新时间"})
     */
    private $gcUpdateTime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="gc_attribute", type="string", length=200, nullable=false, options={"comment"="分类属性【多属性用逗号隔开】"})
     */
    private $gcAttribute = '';

    /**
     * @var string
     *
     * @ORM\Column(name="gc_remark", type="string", length=500, nullable=false, options={"comment"="备注信息"})
     */
    private $gcRemark = '';

    /**
     * @var string
     *
     * @ORM\Column(name="gc_status", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="分类状态【1启用  2停用】"})
     */
    private $gcStatus = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="gc_order", type="integer", nullable=false, options={"default"="1","comment"="分类级别"})
     */
    private $gcOrder = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="gc_sort", type="integer", nullable=false, options={"comment"="分类排序【数值越大越靠前】"})
     */
    private $gcSort = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="gc_img", type="string", length=200, nullable=false, options={"comment"="分类图片"})
     */
    private $gcImg = '';

    /**
     * @var int
     *
     * @ORM\Column(name="gc_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $gcId;



    /**
     * Set gcNode.
     *
     * @param int $gcNode
     *
     * @return YsGoodsCate
     */
    public function setGcNode($gcNode)
    {
        $this->gcNode = $gcNode;

        return $this;
    }

    /**
     * Get gcNode.
     *
     * @return int
     */
    public function getGcNode()
    {
        return $this->gcNode;
    }

    /**
     * Set adminId.
     *
     * @param int $adminId
     *
     * @return YsGoodsCate
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
     * Set pId.
     *
     * @param int|null $pId
     *
     * @return YsGoodsCate
     */
    public function setPId($pId = null)
    {
        $this->pId = $pId;

        return $this;
    }

    /**
     * Get pId.
     *
     * @return int|null
     */
    public function getPId()
    {
        return $this->pId;
    }

    /**
     * Set gcName.
     *
     * @param string $gcName
     *
     * @return YsGoodsCate
     */
    public function setGcName($gcName)
    {
        $this->gcName = $gcName;

        return $this;
    }

    /**
     * Get gcName.
     *
     * @return string
     */
    public function getGcName()
    {
        return $this->gcName;
    }

    /**
     * Set gcAddTime.
     *
     * @param \DateTime $gcAddTime
     *
     * @return YsGoodsCate
     */
    public function setGcAddTime($gcAddTime)
    {
        $this->gcAddTime = $gcAddTime;

        return $this;
    }

    /**
     * Get gcAddTime.
     *
     * @return \DateTime
     */
    public function getGcAddTime()
    {
        return $this->gcAddTime;
    }

    /**
     * Set gcUpdateTime.
     *
     * @param \DateTime $gcUpdateTime
     *
     * @return YsGoodsCate
     */
    public function setGcUpdateTime($gcUpdateTime)
    {
        $this->gcUpdateTime = $gcUpdateTime;

        return $this;
    }

    /**
     * Get gcUpdateTime.
     *
     * @return \DateTime
     */
    public function getGcUpdateTime()
    {
        return $this->gcUpdateTime;
    }

    /**
     * Set gcAttribute.
     *
     * @param string $gcAttribute
     *
     * @return YsGoodsCate
     */
    public function setGcAttribute($gcAttribute)
    {
        $this->gcAttribute = $gcAttribute;

        return $this;
    }

    /**
     * Get gcAttribute.
     *
     * @return string
     */
    public function getGcAttribute()
    {
        return $this->gcAttribute;
    }

    /**
     * Set gcRemark.
     *
     * @param string $gcRemark
     *
     * @return YsGoodsCate
     */
    public function setGcRemark($gcRemark)
    {
        $this->gcRemark = $gcRemark;

        return $this;
    }

    /**
     * Get gcRemark.
     *
     * @return string
     */
    public function getGcRemark()
    {
        return $this->gcRemark;
    }

    /**
     * Set gcStatus.
     *
     * @param string $gcStatus
     *
     * @return YsGoodsCate
     */
    public function setGcStatus($gcStatus)
    {
        $this->gcStatus = $gcStatus;

        return $this;
    }

    /**
     * Get gcStatus.
     *
     * @return string
     */
    public function getGcStatus()
    {
        return $this->gcStatus;
    }

    /**
     * Set gcOrder.
     *
     * @param int $gcOrder
     *
     * @return YsGoodsCate
     */
    public function setGcOrder($gcOrder)
    {
        $this->gcOrder = $gcOrder;

        return $this;
    }

    /**
     * Get gcOrder.
     *
     * @return int
     */
    public function getGcOrder()
    {
        return $this->gcOrder;
    }

    /**
     * Set gcSort.
     *
     * @param int $gcSort
     *
     * @return YsGoodsCate
     */
    public function setGcSort($gcSort)
    {
        $this->gcSort = $gcSort;

        return $this;
    }

    /**
     * Get gcSort.
     *
     * @return int
     */
    public function getGcSort()
    {
        return $this->gcSort;
    }

    /**
     * Set gcImg.
     *
     * @param string $gcImg
     *
     * @return YsGoodsCate
     */
    public function setGcImg($gcImg)
    {
        $this->gcImg = $gcImg;

        return $this;
    }

    /**
     * Get gcImg.
     *
     * @return string
     */
    public function getGcImg()
    {
        return $this->gcImg;
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

}
