<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminLoginInfo
 *
 * @ORM\Table(name="admin_login_info", uniqueConstraints={@ORM\UniqueConstraint(name="a_key", columns={"a_token_id"}), @ORM\UniqueConstraint(name="a_check_sum", columns={"a_check_sum"})}, indexes={@ORM\Index(name="Index_a_admin_login_a_name", columns={"a_name"})})
 * @ORM\Entity
 */
class AdminLoginInfo
{
    /**
     * @var string
     *
     * @ORM\Column(name="a_name", type="string", length=45, nullable=false)
     */
    private $aName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="a_login_time", type="datetime", nullable=false)
     */
    private $aLoginTime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="a_login_ip", type="string", length=45, nullable=false)
     */
    private $aLoginIp;

    /**
     * @var string
     *
     * @ORM\Column(name="a_token_id", type="string", length=16, nullable=false)
     */
    private $aTokenId;

    /**
     * @var string
     *
     * @ORM\Column(name="a_check_sum", type="string", length=250, nullable=false)
     */
    private $aCheckSum;

    /**
     * @var string
     *
     * @ORM\Column(name="device_number", type="string", length=300, nullable=true)
     */
    private $deviceNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="old_a_token_id", type="string", length=40, nullable=true)
     */
    private $oldATokenId;

    /**
     * @var integer
     *
     * @ORM\Column(name="a_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aId;



    /**
     * Set aName
     *
     * @param string $aName
     *
     * @return AdminLoginInfo
     */
    public function setAName($aName)
    {
        $this->aName = $aName;

        return $this;
    }

    /**
     * Get aName
     *
     * @return string
     */
    public function getAName()
    {
        return $this->aName;
    }

    /**
     * Set aLoginTime
     *
     * @param \DateTime $aLoginTime
     *
     * @return AdminLoginInfo
     */
    public function setALoginTime($aLoginTime)
    {
        $this->aLoginTime = $aLoginTime;

        return $this;
    }

    /**
     * Get aLoginTime
     *
     * @return \DateTime
     */
    public function getALoginTime()
    {
        return $this->aLoginTime;
    }

    /**
     * Set aLoginIp
     *
     * @param string $aLoginIp
     *
     * @return AdminLoginInfo
     */
    public function setALoginIp($aLoginIp)
    {
        $this->aLoginIp = $aLoginIp;

        return $this;
    }

    /**
     * Get aLoginIp
     *
     * @return string
     */
    public function getALoginIp()
    {
        return $this->aLoginIp;
    }

    /**
     * Set aTokenId
     *
     * @param string $aTokenId
     *
     * @return AdminLoginInfo
     */
    public function setATokenId($aTokenId)
    {
        $this->aTokenId = $aTokenId;

        return $this;
    }

    /**
     * Get aTokenId
     *
     * @return string
     */
    public function getATokenId()
    {
        return $this->aTokenId;
    }

    /**
     * Set aCheckSum
     *
     * @param string $aCheckSum
     *
     * @return AdminLoginInfo
     */
    public function setACheckSum($aCheckSum)
    {
        $this->aCheckSum = $aCheckSum;

        return $this;
    }

    /**
     * Get aCheckSum
     *
     * @return string
     */
    public function getACheckSum()
    {
        return $this->aCheckSum;
    }

    /**
     * Set deviceNumber
     *
     * @param string $deviceNumber
     *
     * @return AdminLoginInfo
     */
    public function setDeviceNumber($deviceNumber)
    {
        $this->deviceNumber = $deviceNumber;

        return $this;
    }

    /**
     * Get deviceNumber
     *
     * @return string
     */
    public function getDeviceNumber()
    {
        return $this->deviceNumber;
    }

    /**
     * Set oldATokenId
     *
     * @param string $oldATokenId
     *
     * @return AdminLoginInfo
     */
    public function setOldATokenId($oldATokenId)
    {
        $this->oldATokenId = $oldATokenId;

        return $this;
    }

    /**
     * Get oldATokenId
     *
     * @return string
     */
    public function getOldATokenId()
    {
        return $this->oldATokenId;
    }

    /**
     * Get aId
     *
     * @return integer
     */
    public function getAId()
    {
        return $this->aId;
    }
}
