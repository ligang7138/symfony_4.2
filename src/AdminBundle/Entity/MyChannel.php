<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyChannel
 *
 * @ORM\Table(name="my_channel", uniqueConstraints={@ORM\UniqueConstraint(name="my_channel_service_code_pk", columns={"service_code"})})
 * @ORM\Entity
 */
class MyChannel
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="leader_id", type="integer", nullable=true)
	 */
	private $leaderId = '1';

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="channel_type", type="integer", nullable=true)
	 */
	private $channelType = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="truename", type="string", length=45, nullable=false)
	 */
	private $truename;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ident_no", type="string", length=18, nullable=true)
	 */
	private $identNo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="service_code", type="string", length=15, nullable=false)
	 */
	private $serviceCode;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="service_type", type="string", length=255, nullable=true)
	 */
	private $serviceType;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="a_date", type="datetime", nullable=true)
	 */
	private $aDate;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="u_date", type="datetime", nullable=true)
	 */
	private $uDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mobile", type="string", length=11, nullable=true)
	 */
	private $mobile;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="status", type="integer")
	 */
	private $status = 1;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @return int
	 */
	public function getChannelType()
	{
		return $this->channelType;
	}

	/**
	 * @param int $channelType
	 */
	public function setChannelType(int $channelType)
	{
		$this->channelType = $channelType;
	}

	/**
	 * @return string
	 */
	public function getIdentNo()
	{
		return $this->identNo;
	}

	/**
	 * @param string $identNo
	 */
	public function setIdentNo(string $identNo)
	{
		$this->identNo = $identNo;
	}



	/**
	 * Set leaderId
	 *
	 * @param integer $leaderId
	 *
	 * @return MyChannel
	 */
	public function setLeaderId($leaderId)
	{
		$this->leaderId = $leaderId;

		return $this;
	}

	/**
	 * Get leaderId
	 *
	 * @return integer
	 */
	public function getLeaderId()
	{
		return $this->leaderId;
	}

	/**
	 * Set truename
	 *
	 * @param string $truename
	 *
	 * @return MyChannel
	 */
	public function setTruename($truename)
	{
		$this->truename = $truename;

		return $this;
	}

	/**
	 * Get truename
	 *
	 * @return string
	 */
	public function getTruename()
	{
		return $this->truename;
	}

	/**
	 * Set serviceCode
	 *
	 * @param string $serviceCode
	 *
	 * @return MyChannel
	 */
	public function setServiceCode($serviceCode)
	{
		$this->serviceCode = $serviceCode;

		return $this;
	}

	/**
	 * Get serviceCode
	 *
	 * @return string
	 */
	public function getServiceCode()
	{
		return $this->serviceCode;
	}

	/**
	 * Set serviceType
	 *
	 * @param string $serviceType
	 *
	 * @return MyChannel
	 */
	public function setServiceType($serviceType)
	{
		$this->serviceType = $serviceType;

		return $this;
	}

	/**
	 * Get serviceType
	 *
	 * @return string
	 */
	public function getServiceType()
	{
		return $this->serviceType;
	}

	/**
	 * Set aDate
	 *
	 * @param \DateTime $aDate
	 *
	 * @return MyChannel
	 */
	public function setADate($aDate)
	{
		$this->aDate = $aDate;

		return $this;
	}

	/**
	 * Get aDate
	 *
	 * @return \DateTime
	 */
	public function getADate()
	{
		return $this->aDate;
	}

	/**
	 * Set uDate
	 *
	 * @param \DateTime $uDate
	 *
	 * @return MyChannel
	 */
	public function setUDate($uDate)
	{
		$this->uDate = $uDate;

		return $this;
	}

	/**
	 * Get uDate
	 *
	 * @return \DateTime
	 */
	public function getUDate()
	{
		return $this->uDate;
	}

	/**
	 * Set mobile
	 *
	 * @param string $mobile
	 *
	 * @return MyChannel
	 */
	public function setMobile($mobile)
	{
		$this->mobile = $mobile;

		return $this;
	}

	/**
	 * Get mobile
	 *
	 * @return string
	 */
	public function getMobile()
	{
		return $this->mobile;
	}

	/**
	 * Set status
	 *
	 * @param boolean $status
	 *
	 * @return MyChannel
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get status
	 *
	 * @return boolean
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}
}
