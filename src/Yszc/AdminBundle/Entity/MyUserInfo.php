<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyUserInfo
 *
 * @ORM\Table(name="my_user_info", indexes={@ORM\Index(name="index_u_ident_no", columns={"ui_ident_no"})})
 * @ORM\Entity
 */
class MyUserInfo
{
    /**
     * @var string
     *
     * @ORM\Column(name="ui_true_name", type="string", length=30, nullable=false)
     */
    private $uiTrueName;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_ident_no", type="string", length=30, nullable=false)
     */
    private $uiIdentNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ui_ident_time", type="datetime", nullable=false)
     */
    private $uiIdentTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ui_marriage", type="boolean", nullable=false)
     */
    private $uiMarriage = '20';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ui_update_time", type="datetime", nullable=false)
     */
    private $uiUpdateTime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="ui_home_remark", type="string", length=600, nullable=true)
     */
    private $uiHomeRemark;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_phone", type="string", length=20, nullable=false)
     */
    private $uiPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_edu_degree", type="string", length=1, nullable=false)
     */
    private $uiEduDegree = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="ui_together_live_person", type="string", length=45, nullable=false)
     */
    private $uiTogetherLivePerson;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_house_type", type="string", length=1, nullable=false)
     */
    private $uiHouseType = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="ui_house_nums", type="boolean", nullable=false)
     */
    private $uiHouseNums = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ui_house_buy_time", type="datetime", nullable=true)
     */
    private $uiHouseBuyTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_email", type="string", length=65, nullable=false)
     */
    private $uiEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_fix_phone", type="string", length=15, nullable=true)
     */
    private $uiFixPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_qq_wechat", type="string", length=30, nullable=true)
     */
    private $uiQqWechat;

    /**
     * @var integer
     *
     * @ORM\Column(name="ui_son_daughter_num", type="integer", nullable=true)
     */
    private $uiSonDaughterNum;

    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=30)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uCode;



    /**
     * Set uiTrueName
     *
     * @param string $uiTrueName
     *
     * @return MyUserInfo
     */
    public function setUiTrueName($uiTrueName)
    {
        $this->uiTrueName = $uiTrueName;

        return $this;
    }

    /**
     * Get uiTrueName
     *
     * @return string
     */
    public function getUiTrueName()
    {
        return $this->uiTrueName;
    }

    /**
     * Set uiIdentNo
     *
     * @param string $uiIdentNo
     *
     * @return MyUserInfo
     */
    public function setUiIdentNo($uiIdentNo)
    {
        $this->uiIdentNo = $uiIdentNo;

        return $this;
    }

    /**
     * Get uiIdentNo
     *
     * @return string
     */
    public function getUiIdentNo()
    {
        return $this->uiIdentNo;
    }

    /**
     * Set uiIdentTime
     *
     * @param \DateTime $uiIdentTime
     *
     * @return MyUserInfo
     */
    public function setUiIdentTime($uiIdentTime)
    {
        $this->uiIdentTime = $uiIdentTime;

        return $this;
    }

    /**
     * Get uiIdentTime
     *
     * @return \DateTime
     */
    public function getUiIdentTime()
    {
        return $this->uiIdentTime;
    }

    /**
     * Set uiMarriage
     *
     * @param boolean $uiMarriage
     *
     * @return MyUserInfo
     */
    public function setUiMarriage($uiMarriage)
    {
        $this->uiMarriage = $uiMarriage;

        return $this;
    }

    /**
     * Get uiMarriage
     *
     * @return boolean
     */
    public function getUiMarriage()
    {
        return $this->uiMarriage;
    }

    /**
     * Set uiUpdateTime
     *
     * @param \DateTime $uiUpdateTime
     *
     * @return MyUserInfo
     */
    public function setUiUpdateTime($uiUpdateTime)
    {
        $this->uiUpdateTime = $uiUpdateTime;

        return $this;
    }

    /**
     * Get uiUpdateTime
     *
     * @return \DateTime
     */
    public function getUiUpdateTime()
    {
        return $this->uiUpdateTime;
    }

    /**
     * Set uiHomeRemark
     *
     * @param string $uiHomeRemark
     *
     * @return MyUserInfo
     */
    public function setUiHomeRemark($uiHomeRemark)
    {
        $this->uiHomeRemark = $uiHomeRemark;

        return $this;
    }

    /**
     * Get uiHomeRemark
     *
     * @return string
     */
    public function getUiHomeRemark()
    {
        return $this->uiHomeRemark;
    }

    /**
     * Set uiPhone
     *
     * @param string $uiPhone
     *
     * @return MyUserInfo
     */
    public function setUiPhone($uiPhone)
    {
        $this->uiPhone = $uiPhone;

        return $this;
    }

    /**
     * Get uiPhone
     *
     * @return string
     */
    public function getUiPhone()
    {
        return $this->uiPhone;
    }

    /**
     * Set uiEduDegree
     *
     * @param string $uiEduDegree
     *
     * @return MyUserInfo
     */
    public function setUiEduDegree($uiEduDegree)
    {
        $this->uiEduDegree = $uiEduDegree;

        return $this;
    }

    /**
     * Get uiEduDegree
     *
     * @return string
     */
    public function getUiEduDegree()
    {
        return $this->uiEduDegree;
    }

    /**
     * Set uiTogetherLivePerson
     *
     * @param string $uiTogetherLivePerson
     *
     * @return MyUserInfo
     */
    public function setUiTogetherLivePerson($uiTogetherLivePerson)
    {
        $this->uiTogetherLivePerson = $uiTogetherLivePerson;

        return $this;
    }

    /**
     * Get uiTogetherLivePerson
     *
     * @return string
     */
    public function getUiTogetherLivePerson()
    {
        return $this->uiTogetherLivePerson;
    }

    /**
     * Set uiHouseType
     *
     * @param string $uiHouseType
     *
     * @return MyUserInfo
     */
    public function setUiHouseType($uiHouseType)
    {
        $this->uiHouseType = $uiHouseType;

        return $this;
    }

    /**
     * Get uiHouseType
     *
     * @return string
     */
    public function getUiHouseType()
    {
        return $this->uiHouseType;
    }

    /**
     * Set uiHouseNums
     *
     * @param boolean $uiHouseNums
     *
     * @return MyUserInfo
     */
    public function setUiHouseNums($uiHouseNums)
    {
        $this->uiHouseNums = $uiHouseNums;

        return $this;
    }

    /**
     * Get uiHouseNums
     *
     * @return boolean
     */
    public function getUiHouseNums()
    {
        return $this->uiHouseNums;
    }

    /**
     * Set uiHouseBuyTime
     *
     * @param \DateTime $uiHouseBuyTime
     *
     * @return MyUserInfo
     */
    public function setUiHouseBuyTime($uiHouseBuyTime)
    {
        $this->uiHouseBuyTime = $uiHouseBuyTime;

        return $this;
    }

    /**
     * Get uiHouseBuyTime
     *
     * @return \DateTime
     */
    public function getUiHouseBuyTime()
    {
        return $this->uiHouseBuyTime;
    }

    /**
     * Set uiEmail
     *
     * @param string $uiEmail
     *
     * @return MyUserInfo
     */
    public function setUiEmail($uiEmail)
    {
        $this->uiEmail = $uiEmail;

        return $this;
    }

    /**
     * Get uiEmail
     *
     * @return string
     */
    public function getUiEmail()
    {
        return $this->uiEmail;
    }

    /**
     * Set uiFixPhone
     *
     * @param string $uiFixPhone
     *
     * @return MyUserInfo
     */
    public function setUiFixPhone($uiFixPhone)
    {
        $this->uiFixPhone = $uiFixPhone;

        return $this;
    }

    /**
     * Get uiFixPhone
     *
     * @return string
     */
    public function getUiFixPhone()
    {
        return $this->uiFixPhone;
    }

    /**
     * Set uiQqWechat
     *
     * @param string $uiQqWechat
     *
     * @return MyUserInfo
     */
    public function setUiQqWechat($uiQqWechat)
    {
        $this->uiQqWechat = $uiQqWechat;

        return $this;
    }

    /**
     * Get uiQqWechat
     *
     * @return string
     */
    public function getUiQqWechat()
    {
        return $this->uiQqWechat;
    }

    /**
     * Set uiSonDaughterNum
     *
     * @param integer $uiSonDaughterNum
     *
     * @return MyUserInfo
     */
    public function setUiSonDaughterNum($uiSonDaughterNum)
    {
        $this->uiSonDaughterNum = $uiSonDaughterNum;

        return $this;
    }

    /**
     * Get uiSonDaughterNum
     *
     * @return integer
     */
    public function getUiSonDaughterNum()
    {
        return $this->uiSonDaughterNum;
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
}
