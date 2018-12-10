<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminMenus
 *
 * @ORM\Table(name="admin_menus", uniqueConstraints={@ORM\UniqueConstraint(name="menu_name_UNIQUE", columns={"menu_name"})})
 * @ORM\Entity
 */
class AdminMenus
{
    /**
     * @var string
     *
     * @ORM\Column(name="menu_name", type="string", length=60, nullable=false)
     */
    private $menuName;

    /**
     * @var string
     *
     * @ORM\Column(name="menu_route_name", type="string", length=200, nullable=false)
     */
    private $menuRouteName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="menu_add_time", type="datetime", nullable=true)
     */
    private $menuAddTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_child_id", type="integer", nullable=false)
     */
    private $menuChildId;

    /**
     * @var string
     *
     * @ORM\Column(name="menu_status", type="string", length=1, nullable=false)
     */
    private $menuStatus = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="menu_icon", type="string", length=45, nullable=true)
     */
    private $menuIcon;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $menuId;



    /**
     * Set menuName
     *
     * @param string $menuName
     *
     * @return AdminMenus
     */
    public function setMenuName($menuName)
    {
        $this->menuName = $menuName;

        return $this;
    }

    /**
     * Get menuName
     *
     * @return string
     */
    public function getMenuName()
    {
        return $this->menuName;
    }

    /**
     * Set menuRouteName
     *
     * @param string $menuRouteName
     *
     * @return AdminMenus
     */
    public function setMenuRouteName($menuRouteName)
    {
        $this->menuRouteName = $menuRouteName;

        return $this;
    }

    /**
     * Get menuRouteName
     *
     * @return string
     */
    public function getMenuRouteName()
    {
        return $this->menuRouteName;
    }

    /**
     * Set menuAddTime
     *
     * @param \DateTime $menuAddTime
     *
     * @return AdminMenus
     */
    public function setMenuAddTime($menuAddTime)
    {
        $this->menuAddTime = $menuAddTime;

        return $this;
    }

    /**
     * Get menuAddTime
     *
     * @return \DateTime
     */
    public function getMenuAddTime()
    {
        return $this->menuAddTime;
    }

    /**
     * Set menuChildId
     *
     * @param integer $menuChildId
     *
     * @return AdminMenus
     */
    public function setMenuChildId($menuChildId)
    {
        $this->menuChildId = $menuChildId;

        return $this;
    }

    /**
     * Get menuChildId
     *
     * @return integer
     */
    public function getMenuChildId()
    {
        return $this->menuChildId;
    }

    /**
     * Set menuStatus
     *
     * @param string $menuStatus
     *
     * @return AdminMenus
     */
    public function setMenuStatus($menuStatus)
    {
        $this->menuStatus = $menuStatus;

        return $this;
    }

    /**
     * Get menuStatus
     *
     * @return string
     */
    public function getMenuStatus()
    {
        return $this->menuStatus;
    }

    /**
     * Set menuIcon
     *
     * @param string $menuIcon
     *
     * @return AdminMenus
     */
    public function setMenuIcon($menuIcon)
    {
        $this->menuIcon = $menuIcon;

        return $this;
    }

    /**
     * Get menuIcon
     *
     * @return string
     */
    public function getMenuIcon()
    {
        return $this->menuIcon;
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
}
