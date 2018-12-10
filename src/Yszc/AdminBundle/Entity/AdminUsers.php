<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUsers
 *
 * @ORM\Table(name="admin_users", uniqueConstraints={@ORM\UniqueConstraint(name="a_name_UNIQUE", columns={"a_name"})})
 * @ORM\Entity
 */
class AdminUsers implements \Symfony\Component\Security\Core\User\UserInterface, \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="a_name", type="string", length=45, nullable=false)
     */
    private $aName;

    /**
     * @var string
     *
     * @ORM\Column(name="a_pwd", type="string", length=800, nullable=false)
     */
    private $aPwd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="a_login_time", type="datetime", nullable=false)
     */
    private $aLoginTime;

    /**
     * @var string
     *
     * @ORM\Column(name="a_status", type="string", length=1, nullable=false)
     */
    private $aStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="a_roles", type="string", length=6000, nullable=false)
     */
    private $aRoles;

    /**
     * @var string
     *
     * @ORM\Column(name="a_type", type="string", length=1, nullable=false,options={"comment"="0:商户 1：管理员 2：业务员"})
     */
    private $aType = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="a_partner_type", type="string", length=1, nullable=false,options={"comment"="商户类型 1：个人 2：企业"})
	 */
	private $aPartnerType = '1';
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="a_add_time", type="datetime", nullable=false)
     */
    private $aAddTime;

    /**
     * @var string
     *
     * @ORM\Column(name="op_name", type="string", length=1, nullable=true)
     */
    private $opName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="op_time", type="datetime", nullable=true)
     */
    private $opTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="a_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aId;

	/**
     * @var int|null
     */
    private $partnerId;



    /**
     * Set aName
     *
     * @param string $aName
     *
     * @return AdminUsers
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
     * Set aPwd
     *
     * @param string $aPwd
     *
     * @return AdminUsers
     */
    public function setAPwd($aPwd)
    {
        $this->aPwd = $aPwd;

        return $this;
    }

    /**
     * Get aPwd
     *
     * @return string
     */
    public function getAPwd()
    {
        return $this->aPwd;
    }

    /**
     * Set aLoginTime
     *
     * @param \DateTime $aLoginTime
     *
     * @return AdminUsers
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
     * Set aStatus
     *
     * @param string $aStatus
     *
     * @return AdminUsers
     */
    public function setAStatus($aStatus)
    {
        $this->aStatus = $aStatus;

        return $this;
    }

    /**
     * Get aStatus
     *
     * @return string
     */
    public function getAStatus()
    {
        return $this->aStatus;
    }

    /**
     * Set aRoles
     *
     * @param string $aRoles
     *
     * @return AdminUsers
     */
    public function setARoles($aRoles)
    {
        $this->aRoles = $aRoles;

        return $this;
    }

    /**
     * Get aRoles
     *
     * @return string
     */
    public function getARoles()
    {
        return $this->aRoles;
    }

    /**
     * Set aType
     *
     * @param string $aType
     *
     * @return AdminUsers
     */
    public function setAType($aType)
    {
        $this->aType = $aType;

        return $this;
    }

    /**
     * Get aType
     *
     * @return string
     */
    public function getAType()
    {
        return $this->aType;
    }
    /**
     * Set aPartnerType
     *
     * @param string $aPartnerType
     *
     * @return AdminUsers
     */
    public function setAPartnerType($aPartnerType)
    {
        $this->aPartnerType = $aPartnerType;

        return $this;
    }

    /**
     * Get aPartnerType
     *
     * @return string
     */
    public function getAPartnerType()
    {
        return $this->aPartnerType;
    }
    
    /**
     * Set aAddTime
     *
     * @param \DateTime $aAddTime
     *
     * @return AdminUsers
     */
    public function setAAddTime($aAddTime)
    {
        $this->aAddTime = $aAddTime;

        return $this;
    }

    /**
     * Get aAddTime
     *
     * @return \DateTime
     */
    public function getAAddTime()
    {
        return $this->aAddTime;
    }

    /**
     * Set opName
     *
     * @param \DateTime $opName
     *
     * @return AdminUsers
     */
    public function setOpName($opName)
    {
        $this->opName = $opName;

        return $this;
    }

    /**
     * Get opName
     *
     * @return \DateTime
     */
    public function getOpName()
    {
        return $this->opName;
    }

    /**
     * Set opTime
     *
     * @param \DateTime $opName
     *
     * @return AdminUsers
     */
    public function setOpTime($opTime)
    {
        $this->opTime = $opTime;

        return $this;
    }

    /**
     * Get opTime
     *
     * @return \DateTime
     */
    public function getOpTime()
    {
        return $this->opTime;
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

    /**
     * Get partnerId.
     *
     * @return int|null
     */
    public function getPartnerId()
    {
	    $this->partnerId = $_SESSION['partner_id'];
        return $this->partnerId;
    }

    public function eraseCredentials() {
        
    }

    public function getPassword(){
        return $this->aPwd;
    }

    public function getRoles() {
        return ['ROLE_ADMIN'];
    }

    public function getSalt() {
        return '';
    }

    public function getUsername(){
        return $this->aName;
    }

    public function serialize(){
        return serialize([$this->aId,$this->aName,$this->aType]);
    }

    public function unserialize($serialized){
        list($this->aId,$this->aName,$this->aType) = unserialize($serialized);
    }

}
