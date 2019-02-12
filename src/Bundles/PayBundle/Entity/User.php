<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-6-15
 * Time: 上午11:05
 */

namespace Bundles\PayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyUsers
 *
 * @ORM\Table(name="my_users", uniqueConstraints={@ORM\UniqueConstraint(name="index_u_name", columns={"u_name"}), @ORM\UniqueConstraint(name="u_code", columns={"u_code"})})
 * @ORM\Entity(repositoryClass="App\PayBundle\Repository\UserRepository")
 */
class User
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_code", type="string", length=30, nullable=false)
	 */
	private $uCode;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_name", type="string", length=15, nullable=false)
	 */
	private $uName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_pwd", type="string", length=200, nullable=false)
	 */
	private $uPwd;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_pay_pwd", type="string", length=100, nullable=false)
	 */
	private $uPayPwd;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_login_ip", type="string", length=20, nullable=false)
	 */
	private $uLoginIp;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="u_reg_time", type="datetime", nullable=false)
	 */
	private $uRegTime;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="u_login_time", type="datetime", nullable=false)
	 */
	private $uLoginTime = 'CURRENT_TIMESTAMP';

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="u_update_time", type="datetime", nullable=false)
	 */
	private $uUpdateTime = 'CURRENT_TIMESTAMP';

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="u_status", type="boolean", nullable=false)
	 */
	private $uStatus = '1';

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="admin_id", type="integer", nullable=false)
	 */
	private $adminId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_type", type="string", length=1, nullable=false)
	 */
	private $uType = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="open_id", type="string", length=60, nullable=false)
	 */
	private $openId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_push_id", type="string", length=45, nullable=true)
	 */
	private $uPushId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="u_source", type="string", length=1, nullable=true)
	 */
	private $uSource;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="u_id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $uId;



	/**
	 * Set uCode
	 *
	 * @param string $uCode
	 *
	 * @return MyUsers
	 */
	public function setUCode($uCode)
	{
		$this->uCode = $uCode;

		return $this;
	}

	/**
	 * Get uCode
	 *
	 * @return string
	 */
	public function getUCode()
	{
		return $this->uCode;
	}

	/**
	 * Set uName
	 *
	 * @param string $uName
	 *
	 * @return MyUsers
	 */
	public function setUName($uName)
	{
		$this->uName = $uName;

		return $this;
	}

	/**
	 * Get uName
	 *
	 * @return string
	 */
	public function getUName()
	{
		return $this->uName;
	}

	/**
	 * Set uPwd
	 *
	 * @param string $uPwd
	 *
	 * @return MyUsers
	 */
	public function setUPwd($uPwd)
	{
		$this->uPwd = $uPwd;

		return $this;
	}

	/**
	 * Get uPwd
	 *
	 * @return string
	 */
	public function getUPwd()
	{
		return $this->uPwd;
	}

	/**
	 * Set uPayPwd
	 *
	 * @param string $uPayPwd
	 *
	 * @return MyUsers
	 */
	public function setUPayPwd($uPayPwd)
	{
		$this->uPayPwd = $uPayPwd;

		return $this;
	}

	/**
	 * Get uPayPwd
	 *
	 * @return string
	 */
	public function getUPayPwd()
	{
		return $this->uPayPwd;
	}

	/**
	 * Set uLoginIp
	 *
	 * @param string $uLoginIp
	 *
	 * @return MyUsers
	 */
	public function setULoginIp($uLoginIp)
	{
		$this->uLoginIp = $uLoginIp;

		return $this;
	}

	/**
	 * Get uLoginIp
	 *
	 * @return string
	 */
	public function getULoginIp()
	{
		return $this->uLoginIp;
	}

	/**
	 * Set uRegTime
	 *
	 * @param \DateTime $uRegTime
	 *
	 * @return MyUsers
	 */
	public function setURegTime($uRegTime)
	{
		$this->uRegTime = $uRegTime;

		return $this;
	}

	/**
	 * Get uRegTime
	 *
	 * @return \DateTime
	 */
	public function getURegTime()
	{
		return $this->uRegTime;
	}

	/**
	 * Set uLoginTime
	 *
	 * @param \DateTime $uLoginTime
	 *
	 * @return MyUsers
	 */
	public function setULoginTime($uLoginTime)
	{
		$this->uLoginTime = $uLoginTime;

		return $this;
	}

	/**
	 * Get uLoginTime
	 *
	 * @return \DateTime
	 */
	public function getULoginTime()
	{
		return $this->uLoginTime;
	}

	/**
	 * Set uUpdateTime
	 *
	 * @param \DateTime $uUpdateTime
	 *
	 * @return MyUsers
	 */
	public function setUUpdateTime($uUpdateTime)
	{
		$this->uUpdateTime = $uUpdateTime;

		return $this;
	}

	/**
	 * Get uUpdateTime
	 *
	 * @return \DateTime
	 */
	public function getUUpdateTime()
	{
		return $this->uUpdateTime;
	}

	/**
	 * Set uStatus
	 *
	 * @param boolean $uStatus
	 *
	 * @return MyUsers
	 */
	public function setUStatus($uStatus)
	{
		$this->uStatus = $uStatus;

		return $this;
	}

	/**
	 * Get uStatus
	 *
	 * @return boolean
	 */
	public function getUStatus()
	{
		return $this->uStatus;
	}

	/**
	 * Set adminId
	 *
	 * @param integer $adminId
	 *
	 * @return MyUsers
	 */
	public function setAdminId($adminId)
	{
		$this->adminId = $adminId;

		return $this;
	}

	/**
	 * Get adminId
	 *
	 * @return integer
	 */
	public function getAdminId()
	{
		return $this->adminId;
	}

	/**
	 * Set uType
	 *
	 * @param string $uType
	 *
	 * @return MyUsers
	 */
	public function setUType($uType)
	{
		$this->uType = $uType;

		return $this;
	}

	/**
	 * Get uType
	 *
	 * @return string
	 */
	public function getUType()
	{
		return $this->uType;
	}

	/**
	 * Set openId
	 *
	 * @param string $openId
	 *
	 * @return MyUsers
	 */
	public function setOpenId($openId)
	{
		$this->openId = $openId;

		return $this;
	}

	/**
	 * Get openId
	 *
	 * @return string
	 */
	public function getOpenId()
	{
		return $this->openId;
	}

	/**
	 * Set uPushId
	 *
	 * @param string $uPushId
	 *
	 * @return MyUsers
	 */
	public function setUPushId($uPushId)
	{
		$this->uPushId = $uPushId;

		return $this;
	}

	/**
	 * Get uPushId
	 *
	 * @return string
	 */
	public function getUPushId()
	{
		return $this->uPushId;
	}

	/**
	 * Set uSource
	 *
	 * @param string $uSource
	 *
	 * @return MyUsers
	 */
	public function setUSource($uSource)
	{
		$this->uSource = $uSource;

		return $this;
	}

	/**
	 * Get uSource
	 *
	 * @return string
	 */
	public function getUSource()
	{
		return $this->uSource;
	}

	/**
	 * Get uId
	 *
	 * @return integer
	 */
	public function getUId()
	{
		return $this->uId;
	}
}