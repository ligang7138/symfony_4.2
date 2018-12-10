<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminSaleInvite
 *
 * @ORM\Table(name="admin_sale_invite", uniqueConstraints={@ORM\UniqueConstraint(name="ai_code", columns={"ai_code"}), @ORM\UniqueConstraint(name="a_id", columns={"a_id"})})
 * @ORM\Entity
 */
class AdminSaleInvite
{
    /**
     * @var int
     *
     * @ORM\Column(name="a_id", type="integer", nullable=false, options={"comment"="受邀人ID"})
     */
    private $aId;

    /**
     * @var int
     *
     * @ORM\Column(name="ai_parent", type="integer", nullable=false, options={"comment"="父id"})
     */
    private $aiParent;

    /**
     * @var int
     *
     * @ORM\Column(name="ai_type", type="smallint", nullable=false, options={"default"="6","comment"="类型【0商户，5加盟商，6个人】"})
     */
    private $aiType = '6';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ai_add_time", type="datetime", nullable=false, options={"comment"="邀请时间"})
     */
    private $aiAddTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ai_add_name", type="string", length=45, nullable=false, options={"comment"="邀请人帐号"})
     */
    private $aiAddName;

    /**
     * @var string
     *
     * @ORM\Column(name="ai_code", type="string", length=15, nullable=false, options={"comment"="邀请码"})
     */
    private $aiCode = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="parent_invite_code", type="string", length=15, nullable=true, options={"comment"="父邀请码"})
     */
    private $parentInviteCode;

    /**
     * @var int
     *
     * @ORM\Column(name="ai_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aiId;



    /**
     * Set aId.
     *
     * @param int $aId
     *
     * @return AdminSaleInvite
     */
    public function setAId($aId)
    {
        $this->aId = $aId;

        return $this;
    }

    /**
     * Get aId.
     *
     * @return int
     */
    public function getAId()
    {
        return $this->aId;
    }

    /**
     * Set aiParent.
     *
     * @param int $aiParent
     *
     * @return AdminSaleInvite
     */
    public function setAiParent($aiParent)
    {
        $this->aiParent = $aiParent;

        return $this;
    }

    /**
     * Get aiParent.
     *
     * @return int
     */
    public function getAiParent()
    {
        return $this->aiParent;
    }

    /**
     * Set aiType.
     *
     * @param int $aiType
     *
     * @return AdminSaleInvite
     */
    public function setAiType($aiType)
    {
        $this->aiType = $aiType;

        return $this;
    }

    /**
     * Get aiType.
     *
     * @return int
     */
    public function getAiType()
    {
        return $this->aiType;
    }

    /**
     * Set aiAddTime.
     *
     * @param \DateTime $aiAddTime
     *
     * @return AdminSaleInvite
     */
    public function setAiAddTime($aiAddTime)
    {
        $this->aiAddTime = $aiAddTime;

        return $this;
    }

    /**
     * Get aiAddTime.
     *
     * @return \DateTime
     */
    public function getAiAddTime()
    {
        return $this->aiAddTime;
    }

    /**
     * Set aiAddName.
     *
     * @param string $aiAddName
     *
     * @return AdminSaleInvite
     */
    public function setAiAddName($aiAddName)
    {
        $this->aiAddName = $aiAddName;

        return $this;
    }

    /**
     * Get aiAddName.
     *
     * @return string
     */
    public function getAiAddName()
    {
        return $this->aiAddName;
    }

    /**
     * Set aiCode.
     *
     * @param string $aiCode
     *
     * @return AdminSaleInvite
     */
    public function setAiCode($aiCode)
    {
        $this->aiCode = $aiCode;

        return $this;
    }

    /**
     * Get aiCode.
     *
     * @return string
     */
    public function getAiCode()
    {
        return $this->aiCode;
    }

    /**
     * Set parentInviteCode.
     *
     * @param string|null $parentInviteCode
     *
     * @return AdminSaleInvite
     */
    public function setParentInviteCode($parentInviteCode = null)
    {
        $this->parentInviteCode = $parentInviteCode;

        return $this;
    }

    /**
     * Get parentInviteCode.
     *
     * @return string|null
     */
    public function getParentInviteCode()
    {
        return $this->parentInviteCode;
    }

    /**
     * Get aiId.
     *
     * @return int
     */
    public function getAiId()
    {
        return $this->aiId;
    }
}
