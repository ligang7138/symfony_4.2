<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventActions
 *
 * @ORM\Table(name="event_actions", uniqueConstraints={@ORM\UniqueConstraint(name="e_name_UNIQUE", columns={"e_name", "e_code", "m_id"})})
 * @ORM\Entity
 */
class EventActions
{
    /**
     * @var string
     *
     * @ORM\Column(name="m_id", type="string", length=45, nullable=false)
     */
    private $mId;

    /**
     * @var string
     *
     * @ORM\Column(name="e_code", type="string", length=45, nullable=false)
     */
    private $eCode;

    /**
     * @var string
     *
     * @ORM\Column(name="e_name", type="string", length=45, nullable=false)
     */
    private $eName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="e_add_time", type="datetime", nullable=true)
     */
    private $eAddTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer", nullable=false)
     */
    private $menuId;

    /**
     * @var integer
     *
     * @ORM\Column(name="e_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eId;



    /**
     * Set mId
     *
     * @param string $mId
     *
     * @return EventActions
     */
    public function setMId($mId)
    {
        $this->mId = $mId;

        return $this;
    }

    /**
     * Get mId
     *
     * @return string
     */
    public function getMId()
    {
        return $this->mId;
    }

    /**
     * Set eCode
     *
     * @param string $eCode
     *
     * @return EventActions
     */
    public function setECode($eCode)
    {
        $this->eCode = $eCode;

        return $this;
    }

    /**
     * Get eCode
     *
     * @return string
     */
    public function getECode()
    {
        return $this->eCode;
    }

    /**
     * Set eName
     *
     * @param string $eName
     *
     * @return EventActions
     */
    public function setEName($eName)
    {
        $this->eName = $eName;

        return $this;
    }

    /**
     * Get eName
     *
     * @return string
     */
    public function getEName()
    {
        return $this->eName;
    }

    /**
     * Set eAddTime
     *
     * @param \DateTime $eAddTime
     *
     * @return EventActions
     */
    public function setEAddTime($eAddTime)
    {
        $this->eAddTime = $eAddTime;

        return $this;
    }

    /**
     * Get eAddTime
     *
     * @return \DateTime
     */
    public function getEAddTime()
    {
        return $this->eAddTime;
    }

    /**
     * Set menuId
     *
     * @param integer $menuId
     *
     * @return EventActions
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this;
    }

    /**
     * Get menuId
     *
     * @return integer
     */
    public function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * Get eId
     *
     * @return integer
     */
    public function getEId()
    {
        return $this->eId;
    }
}
