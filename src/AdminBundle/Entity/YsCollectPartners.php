<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsCollectPartners
 *
 * @ORM\Table(name="qy_collect_partners", uniqueConstraints={@ORM\UniqueConstraint(name="u_ucode_partner_id", columns={"u_code", "partner_id"})})
 * @ORM\Entity
 */
class YsCollectPartners
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
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"comment"="商户id"})
     */
    private $partnerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="collect_time", type="datetime", nullable=false, options={"comment"="收藏时间"})
     */
    private $collectTime;

    /**
     * @var int
     *
     * @ORM\Column(name="cp_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cpId;



    /**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsCollectPartners
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
     * @return YsCollectPartners
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
     * Set collectTime.
     *
     * @param \DateTime $collectTime
     *
     * @return YsCollectPartners
     */
    public function setCollectTime($collectTime)
    {
        $this->collectTime = $collectTime;

        return $this;
    }

    /**
     * Get collectTime.
     *
     * @return \DateTime
     */
    public function getCollectTime()
    {
        return $this->collectTime;
    }

    /**
     * Get cpId.
     *
     * @return int
     */
    public function getCpId()
    {
        return $this->cpId;
    }
}
