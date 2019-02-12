<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyDept
 *
 * @ORM\Table(name="my_dept")
 * @ORM\Entity
 */
class MyDept
{
    /**
     * @var string
     *
     * @ORM\Column(name="dt_name", type="string", length=60, nullable=false)
     */
    private $dtName;

    /**
     * @var integer
     *
     * @ORM\Column(name="dt_parent", type="integer", nullable=false)
     */
    private $dtParent = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="dt_roles", type="string", length=300, nullable=true)
     */
    private $dtRoles;

    /**
     * @var boolean
     *
     * @ORM\Column(name="dt_order", type="boolean", nullable=false)
     */
    private $dtOrder = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="dt_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dtId;



    /**
     * Set dtName
     *
     * @param string $dtName
     *
     * @return MyDept
     */
    public function setDtName($dtName)
    {
        $this->dtName = $dtName;

        return $this;
    }

    /**
     * Get dtName
     *
     * @return string
     */
    public function getDtName()
    {
        return $this->dtName;
    }

    /**
     * Set dtParent
     *
     * @param integer $dtParent
     *
     * @return MyDept
     */
    public function setDtParent($dtParent)
    {
        $this->dtParent = $dtParent;

        return $this;
    }

    /**
     * Get dtParent
     *
     * @return integer
     */
    public function getDtParent()
    {
        return $this->dtParent;
    }

    /**
     * Set dtRoles
     *
     * @param string $dtRoles
     *
     * @return MyDept
     */
    public function setDtRoles($dtRoles)
    {
        $this->dtRoles = $dtRoles;

        return $this;
    }

    /**
     * Get dtRoles
     *
     * @return string
     */
    public function getDtRoles()
    {
        return $this->dtRoles;
    }

    /**
     * Set dtOrder
     *
     * @param boolean $dtOrder
     *
     * @return MyDept
     */
    public function setDtOrder($dtOrder)
    {
        $this->dtOrder = $dtOrder;

        return $this;
    }

    /**
     * Get dtOrder
     *
     * @return boolean
     */
    public function getDtOrder()
    {
        return $this->dtOrder;
    }

    /**
     * Get dtId
     *
     * @return integer
     */
    public function getDtId()
    {
        return $this->dtId;
    }
}
