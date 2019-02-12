<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminRoles
 *
 * @ORM\Table(name="admin_roles", uniqueConstraints={@ORM\UniqueConstraint(name="r_name_UNIQUE", columns={"r_name"})})
 * @ORM\Entity
 */
class AdminRoles
{
    /**
     * @var string
     *
     * @ORM\Column(name="r_name", type="string", length=60, nullable=false)
     */
    private $rName;

    /**
     * @var string
     *
     * @ORM\Column(name="e_list", type="string", length=6000, nullable=true)
     */
    private $eList;

    /**
     * @var string
     *
     * @ORM\Column(name="menu_ids", type="string", length=2000, nullable=true)
     */
    private $menuIds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="r_add_time", type="datetime", nullable=true)
     */
    private $rAddTime = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="r_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rId;



    /**
     * Set rName
     *
     * @param string $rName
     *
     * @return AdminRoles
     */
    public function setRName($rName)
    {
        $this->rName = $rName;

        return $this;
    }

    /**
     * Get rName
     *
     * @return string
     */
    public function getRName()
    {
        return $this->rName;
    }

    /**
     * Set eList
     *
     * @param string $eList
     *
     * @return AdminRoles
     */
    public function setEList($eList)
    {
        $this->eList = $eList;

        return $this;
    }

    /**
     * Get eList
     *
     * @return string
     */
    public function getEList()
    {
        return $this->eList;
    }

    /**
     * Set menuIds
     *
     * @param string $menuIds
     *
     * @return AdminRoles
     */
    public function setMenuIds($menuIds)
    {
        $this->menuIds = $menuIds;

        return $this;
    }

    /**
     * Get menuIds
     *
     * @return string
     */
    public function getMenuIds()
    {
        return $this->menuIds;
    }

    /**
     * Set rAddTime
     *
     * @param \DateTime $rAddTime
     *
     * @return AdminRoles
     */
    public function setRAddTime($rAddTime)
    {
        $this->rAddTime = $rAddTime;

        return $this;
    }

    /**
     * Get rAddTime
     *
     * @return \DateTime
     */
    public function getRAddTime()
    {
        return $this->rAddTime;
    }

    /**
     * Get rId
     *
     * @return integer
     */
    public function getRId()
    {
        return $this->rId;
    }
}
