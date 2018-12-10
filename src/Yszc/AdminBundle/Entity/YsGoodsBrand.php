<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsGoodsBrand
 *
 * @ORM\Table(name="qy_goods_brand", uniqueConstraints={@ORM\UniqueConstraint(name="index_gb_name", columns={"gb_name"})})
 * @ORM\Entity
 */
class YsGoodsBrand
{
    /**
     * @var string
     *
     * @ORM\Column(name="gb_code", type="string", length=50, nullable=false, options={"comment"="品牌编号"})
     */
    private $gbCode;

    /**
     * @var string
     *
     * @ORM\Column(name="gb_name", type="string", length=100, nullable=false, options={"comment"="品牌名称"})
     */
    private $gbName;

    /**
     * @var string
     *
     * @ORM\Column(name="gb_maker", type="string", length=200, nullable=false, options={"comment"="制造商"})
     */
    private $gbMaker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gb_add_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="品牌添加时间"})
     */
    private $gbAddTime = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false, options={"comment"="商户ID"})
     */
    private $adminId;

    /**
     * @var string
     *
     * @ORM\Column(name="gb_status", type="string", length=1,  nullable=false, options={"default"="1","comment"="品牌状态【1启用  2停用】"})
     */
    private $gbStatus = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="gb_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $gbId;



    /**
     * Set gbCode.
     *
     * @param string $gbCode
     *
     * @return YsGoodsBrand
     */
    public function setGbCode($gbCode)
    {
        $this->gbCode = $gbCode;

        return $this;
    }

    /**
     * Get gbCode.
     *
     * @return string
     */
    public function getGbCode()
    {
        return $this->gbCode;
    }

    /**
     * Set gbName.
     *
     * @param string $gbName
     *
     * @return YsGoodsBrand
     */
    public function setGbName($gbName)
    {
        $this->gbName = $gbName;

        return $this;
    }

    /**
     * Get gbName.
     *
     * @return string
     */
    public function getGbName()
    {
        return $this->gbName;
    }

    /**
     * Set gbMaker.
     *
     * @param string $gbMaker
     *
     * @return YsGoodsBrand
     */
    public function setGbMaker($gbMaker)
    {
        $this->gbMaker = $gbMaker;

        return $this;
    }

    /**
     * Get gbMaker.
     *
     * @return string
     */
    public function getGbMaker()
    {
        return $this->gbMaker;
    }

    /**
     * Set gbAddTime.
     *
     * @param \DateTime $gbAddTime
     *
     * @return YsGoodsBrand
     */
    public function setGbAddTime($gbAddTime)
    {
        $this->gbAddTime = $gbAddTime;

        return $this;
    }

    /**
     * Get gbAddTime.
     *
     * @return \DateTime
     */
    public function getGbAddTime()
    {
        return $this->gbAddTime;
    }

    /**
     * Set adminId.
     *
     * @param int $adminId
     *
     * @return YsGoodsBrand
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
     * Set gbStatus.
     *
     * @param string $gbStatus
     *
     * @return YsGoodsBrand
     */
    public function setGbStatus($gbStatus)
    {
        $this->gbStatus = $gbStatus;

        return $this;
    }

    /**
     * Get gbStatus.
     *
     * @return string
     */
    public function getGbStatus()
    {
        return $this->gbStatus;
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
}
