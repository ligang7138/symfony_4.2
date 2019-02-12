<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsGoodsSpecPrice
 *
 * @ORM\Table(name="qy_goods_spec_price")
 * @ORM\Entity
 */
class YsGoodsSpecPrice
{
    /**
     * @var int
     *
     * @ORM\Column(name="gn_spec_type", type="integer", nullable=false, options={"default"="1","comment"="规格类别【1重量 ，2容量】"})
     */
    private $gnSpecType = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="gn_spec_num", type="string", length=50, nullable=false, options={"comment"="规格值-单位"})
     */
    private $gnSpecNum;

    /**
     * @var string
     *
     * @ORM\Column(name="gn_price", type="decimal", precision=13, scale=2, nullable=false, options={"default"="0.00","comment"="单价"})
     */
    private $gnPrice = '0.00';

    /**
     * @var int
     *
     * @ORM\Column(name="gn_stock", type="smallint", nullable=false, options={"comment"="剩余库存值"})
     */
    private $gnStock = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="gn_total_stock", type="smallint", nullable=false, options={"comment"="总库存"})
     */
    private $gnTotalStock = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="gn_stock_remind", type="integer", nullable=false, options={"comment"="库存提醒值"})
     */
    private $gnStockRemind = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gn_add_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="添加时间"})
     */
    private $gnAddTime = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gn_update_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="修改时间"})
     */
    private $gnUpdateTime = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="gn_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $gnId;



    /**
     * Set gnSpecType.
     *
     * @param int $gnSpecType
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnSpecType($gnSpecType)
    {
        $this->gnSpecType = $gnSpecType;

        return $this;
    }

    /**
     * Get gnSpecType.
     *
     * @return int
     */
    public function getGnSpecType()
    {
        return $this->gnSpecType;
    }

    /**
     * Set gnSpecNum.
     *
     * @param string $gnSpecNum
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnSpecNum($gnSpecNum)
    {
        $this->gnSpecNum = $gnSpecNum;

        return $this;
    }

    /**
     * Get gnSpecNum.
     *
     * @return string
     */
    public function getGnSpecNum()
    {
        return $this->gnSpecNum;
    }

    /**
     * Set gnPrice.
     *
     * @param string $gnPrice
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnPrice($gnPrice)
    {
        $this->gnPrice = $gnPrice;

        return $this;
    }

    /**
     * Get gnPrice.
     *
     * @return string
     */
    public function getGnPrice()
    {
        return $this->gnPrice;
    }

    /**
     * Set gnStock.
     *
     * @param int $gnStock
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnStock($gnStock)
    {
        $this->gnStock = $gnStock;

        return $this;
    }

    /**
     * Get gnStock.
     *
     * @return int
     */
    public function getGnStock()
    {
        return $this->gnStock;
    }

    /**
     * Set gnTotalStock.
     *
     * @param int $gnTotalStock
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnTotalStock($gnTotalStock)
    {
        $this->gnTotalStock = $gnTotalStock;

        return $this;
    }

    /**
     * Get gnTotalStock.
     *
     * @return int
     */
    public function getGnTotalStock()
    {
        return $this->gnTotalStock;
    }

    /**
     * Set gnStockRemind.
     *
     * @param int $gnStockRemind
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnStockRemind($gnStockRemind)
    {
        $this->gnStockRemind = $gnStockRemind;

        return $this;
    }

    /**
     * Get gnStockRemind.
     *
     * @return int
     */
    public function getGnStockRemind()
    {
        return $this->gnStockRemind;
    }

    /**
     * Set gnAddTime.
     *
     * @param \DateTime $gnAddTime
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnAddTime($gnAddTime)
    {
        $this->gnAddTime = $gnAddTime;

        return $this;
    }

    /**
     * Get gnAddTime.
     *
     * @return \DateTime
     */
    public function getGnAddTime()
    {
        return $this->gnAddTime;
    }

    /**
     * Set gnUpdateTime.
     *
     * @param \DateTime $gnUpdateTime
     *
     * @return YsGoodsSpecPrice
     */
    public function setGnUpdateTime($gnUpdateTime)
    {
        $this->gnUpdateTime = $gnUpdateTime;

        return $this;
    }

    /**
     * Get gnUpdateTime.
     *
     * @return \DateTime
     */
    public function getGnUpdateTime()
    {
        return $this->gnUpdateTime;
    }

    /**
     * Get gnId.
     *
     * @return int
     */
    public function getGnId()
    {
        return $this->gnId;
    }
}
