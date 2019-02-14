<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsOrderInfo
 *
 * @ORM\Table(name="qy_order_info")
 * @ORM\Entity
 */
class YsOrderInfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="g_id", type="integer", nullable=false, options={"comment"="商品id"})
     */
    private $gId;

    /**
     * @var int
     *
     * @ORM\Column(name="gn_id", type="integer", nullable=false, options={"comment"="商品规格"})
     */
    private $gnId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_goods_nums", type="smallint", nullable=false, options={"default"="1","comment"="产品数量"})
     */
    private $orderGoodsNums = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="goods_unit_price", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00","comment"="商品单价"})
     */
    private $goodsUnitPrice = '0.00';

    /**
     * @var int
     *
     * @ORM\Column(name="oi_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $oiId;



    /**
     * Set orderId.
     *
     * @param int $orderId
     *
     * @return YsOrderInfo
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
     * Set gId.
     *
     * @param int $gId
     *
     * @return YsOrderInfo
     */
    public function setGId($gId)
    {
        $this->gId = $gId;

        return $this;
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

    /**
     * Set gnId.
     *
     * @param int $gnId
     *
     * @return YsOrderInfo
     */
    public function setGnId($gnId)
    {
        $this->gnId = $gnId;

        return $this;
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

    /**
     * Set orderGoodsNums.
     *
     * @param int $orderGoodsNums
     *
     * @return YsOrderInfo
     */
    public function setOrderGoodsNums($orderGoodsNums)
    {
        $this->orderGoodsNums = $orderGoodsNums;

        return $this;
    }

    /**
     * Get orderGoodsNums.
     *
     * @return int
     */
    public function getOrderGoodsNums()
    {
        return $this->orderGoodsNums;
    }

    /**
     * Set goodsUnitPrice.
     *
     * @param string $goodsUnitPrice
     *
     * @return YsOrderInfo
     */
    public function setGoodsUnitPrice($goodsUnitPrice)
    {
        $this->goodsUnitPrice = $goodsUnitPrice;

        return $this;
    }

    /**
     * Get goodsUnitPrice.
     *
     * @return string
     */
    public function getGoodsUnitPrice()
    {
        return $this->goodsUnitPrice;
    }

    /**
     * Get oiId.
     *
     * @return int
     */
    public function getOiId()
    {
        return $this->oiId;
    }
}
