<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminModles
 *
 * @ORM\Table(name="admin_modles", uniqueConstraints={@ORM\UniqueConstraint(name="m_code_UNIQUE", columns={"m_code"}), @ORM\UniqueConstraint(name="m_name_UNIQUE", columns={"m_name"})})
 * @ORM\Entity
 */
class AdminModles
{
    /**
     * @var string
     *
     * @ORM\Column(name="m_code", type="string", length=60, nullable=false)
     */
    private $mCode;

    /**
     * @var string
     *
     * @ORM\Column(name="m_name", type="string", length=100, nullable=false)
     */
    private $mName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="m_add_time", type="datetime", nullable=true)
     */
    private $mAddTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="m_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mId;



    /**
     * Set mCode
     *
     * @param string $mCode
     *
     * @return AdminModles
     */
    public function setMCode($mCode)
    {
        $this->mCode = $mCode;

        return $this;
    }

    /**
     * Get mCode
     *
     * @return string
     */
    public function getMCode()
    {
        return $this->mCode;
    }

    /**
     * Set mName
     *
     * @param string $mName
     *
     * @return AdminModles
     */
    public function setMName($mName)
    {
        $this->mName = $mName;

        return $this;
    }

    /**
     * Get mName
     *
     * @return string
     */
    public function getMName()
    {
        return $this->mName;
    }

    /**
     * Set mAddTime
     *
     * @param \DateTime $mAddTime
     *
     * @return AdminModles
     */
    public function setMAddTime($mAddTime)
    {
        $this->mAddTime = $mAddTime;

        return $this;
    }

    /**
     * Get mAddTime
     *
     * @return \DateTime
     */
    public function getMAddTime()
    {
        return $this->mAddTime;
    }

    /**
     * Get mId
     *
     * @return integer
     */
    public function getMId()
    {
        return $this->mId;
    }
}
