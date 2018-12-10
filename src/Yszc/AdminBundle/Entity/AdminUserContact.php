<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUserContact
 *
 * @ORM\Table(name="admin_user_contact")
 * @ORM\Entity
 */
class AdminUserContact
{
    /**
     * @var int
     *
     * @ORM\Column(name="a_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商户用户主键id"})
     */
    private $aId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="contact_name", type="string", length=20, nullable=false, options={"comment"="联系人姓名"})
     */
    private $contactName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=11, nullable=false, options={"fixed"=true,"comment"="联系人手机号"})
     */
    private $phone = '';

    /**
     * @var string
     *
     * @ORM\Column(name="ident_no", type="string", length=20, nullable=false, options={"comment"="联系人身份证号"})
     */
    private $identNo = '';

    /**
     * @var string
     *
     * @ORM\Column(name="live_address", type="string", length=50, nullable=false, options={"comment"="联系人居住地址"})
     */
    private $liveAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=20, nullable=false, options={"comment"="职业"})
     */
    private $profession = '';

    /**
     * @var string
     *
     * @ORM\Column(name="is_del", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="是否删除 1：否 2：是"})
     */
    private $isDel = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="创建时间"})
     */
    private $createTime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=3, nullable=false, options={"fixed"=true})
     */
    private $sex = '';

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $age = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set aId.
     *
     * @param int $aId
     *
     * @return AdminUserContact
     */
    public function setAId($aId)
    {
        $this->aId = $aId;

        return $this;
    }

    /**
     * Get aId.
     *
     * @return int
     */
    public function getAId()
    {
        return $this->aId;
    }

    /**
     * Set contactName.
     *
     * @param string $contactName
     *
     * @return AdminUserContact
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName.
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return AdminUserContact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set identNo.
     *
     * @param string $identNo
     *
     * @return AdminUserContact
     */
    public function setIdentNo($identNo)
    {
        $this->identNo = $identNo;

        return $this;
    }

    /**
     * Get identNo.
     *
     * @return string
     */
    public function getIdentNo()
    {
        return $this->identNo;
    }

    /**
     * Set liveAddress.
     *
     * @param string $liveAddress
     *
     * @return AdminUserContact
     */
    public function setLiveAddress($liveAddress)
    {
        $this->liveAddress = $liveAddress;

        return $this;
    }

    /**
     * Get liveAddress.
     *
     * @return string
     */
    public function getLiveAddress()
    {
        return $this->liveAddress;
    }

    /**
     * Set profession.
     *
     * @param string $profession
     *
     * @return AdminUserContact
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession.
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set isDel.
     *
     * @param string $isDel
     *
     * @return AdminUserContact
     */
    public function setIsDel($isDel)
    {
        $this->isDel = $isDel;

        return $this;
    }

    /**
     * Get isDel.
     *
     * @return string
     */
    public function getIsDel()
    {
        return $this->isDel;
    }

    /**
     * Set createTime.
     *
     * @param \DateTime $createTime
     *
     * @return AdminUserContact
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime.
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set sex.
     *
     * @param string $sex
     *
     * @return AdminUserContact
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex.
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set age.
     *
     * @param int $age
     *
     * @return AdminUserContact
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age.
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
