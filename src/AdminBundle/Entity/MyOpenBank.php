<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyOpenBank
 *
 * @ORM\Table(name="my_open_bank")
 * @ORM\Entity
 */
class MyOpenBank
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_able", type="boolean", nullable=false)
     */
    private $isAble = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="single_limit", type="float", precision=11, scale=1, nullable=false)
     */
    private $singleLimit = '0.0';

    /**
     * @var float
     *
     * @ORM\Column(name="day_limit", type="float", precision=11, scale=1, nullable=false)
     */
    private $dayLimit = '0.0';

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $code;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return MyOpenBank
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isAble
     *
     * @param boolean $isAble
     *
     * @return MyOpenBank
     */
    public function setIsAble($isAble)
    {
        $this->isAble = $isAble;

        return $this;
    }

    /**
     * Get isAble
     *
     * @return boolean
     */
    public function getIsAble()
    {
        return $this->isAble;
    }

    /**
     * Set singleLimit
     *
     * @param float $singleLimit
     *
     * @return MyOpenBank
     */
    public function setSingleLimit($singleLimit)
    {
        $this->singleLimit = $singleLimit;

        return $this;
    }

    /**
     * Get singleLimit
     *
     * @return float
     */
    public function getSingleLimit()
    {
        return $this->singleLimit;
    }

    /**
     * Set dayLimit
     *
     * @param float $dayLimit
     *
     * @return MyOpenBank
     */
    public function setDayLimit($dayLimit)
    {
        $this->dayLimit = $dayLimit;

        return $this;
    }

    /**
     * Get dayLimit
     *
     * @return float
     */
    public function getDayLimit()
    {
        return $this->dayLimit;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }
}
