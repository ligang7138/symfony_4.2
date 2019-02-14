<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsPartnerDaturm
 *
 * @ORM\Table(name="qy_partner_daturm")
 * @ORM\Entity
 */
class YsPartnerDaturm
{
    /**
     * @var int
     *
     * @ORM\Column(name="partner_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $partnerId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="pd_type", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="类型 a : 证件资料,b : 店铺头像,c : 店铺图片,d : 商品图片,g : 其它"})
     */
    private $pdType;

    /**
     * @var string
     *
     * @ORM\Column(name="pd_url", type="string", length=150, nullable=false, options={"comment"="资料url"})
     */
    private $pdUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pd_add_time", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP","comment"="添加时间"})
     */
    private $pdAddTime;

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false, options={"comment"="管理员id"})
     */
    private $adminId;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set partnerId.
     *
     * @param int $partnerId
     *
     * @return YsPartnerDaturm
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
     * Set pdType.
     *
     * @param string $pdType
     *
     * @return YsPartnerDaturm
     */
    public function setPdType($pdType)
    {
        $this->pdType = $pdType;

        return $this;
    }

    /**
     * Get pdType.
     *
     * @return string
     */
    public function getPdType()
    {
        return $this->pdType;
    }

    /**
     * Set pdUrl.
     *
     * @param string $pdUrl
     *
     * @return YsPartnerDaturm
     */
    public function setPdUrl($pdUrl)
    {
        $this->pdUrl = $pdUrl;

        return $this;
    }

    /**
     * Get pdUrl.
     *
     * @return string
     */
    public function getPdUrl()
    {
        return $this->pdUrl;
    }

    /**
     * Set pdAddTime.
     *
     * @param \DateTime $pdAddTime
     *
     * @return YsPartnerDaturm
     */
    public function setPdAddTime($pdAddTime)
    {
        $this->pdAddTime = $pdAddTime;

        return $this;
    }

    /**
     * Get pdAddTime.
     *
     * @return \DateTime
     */
    public function getPdAddTime()
    {
        return $this->pdAddTime;
    }

    /**
     * Set adminId.
     *
     * @param int $adminId
     *
     * @return YsPartnerDaturm
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
