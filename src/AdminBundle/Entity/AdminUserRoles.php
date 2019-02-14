<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUserRoles
 *
 * @ORM\Table(name="admin_user_roles")
 * @ORM\Entity
 */
class AdminUserRoles
{
    /**
     * @var string
     *
     * @ORM\Column(name="aur_name", type="string", length=30, nullable=false, options={"comment"="角色名称"})
     */
    private $aurName;

    /**
     * @var string
     *
     * @ORM\Column(name="aur_role_list", type="string", length=1000, nullable=false, options={"comment"="权限集合"})
     */
    private $aurRoleList;

    /**
     * @var string
     *
     * @ORM\Column(name="aur_home_url", type="string", length=150, nullable=false, options={"comment"="角色首页地址"})
     */
    private $aurHomeUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="aur_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aurId;



    /**
     * Set aurName.
     *
     * @param string $aurName
     *
     * @return AdminUserRoles
     */
    public function setAurName($aurName)
    {
        $this->aurName = $aurName;

        return $this;
    }

    /**
     * Get aurName.
     *
     * @return string
     */
    public function getAurName()
    {
        return $this->aurName;
    }

    /**
     * Set aurRoleList.
     *
     * @param string $aurRoleList
     *
     * @return AdminUserRoles
     */
    public function setAurRoleList($aurRoleList)
    {
        $this->aurRoleList = $aurRoleList;

        return $this;
    }

    /**
     * Get aurRoleList.
     *
     * @return string
     */
    public function getAurRoleList()
    {
        return $this->aurRoleList;
    }

    /**
     * Set aurHomeUrl.
     *
     * @param string $aurHomeUrl
     *
     * @return AdminUserRoles
     */
    public function setAurHomeUrl($aurHomeUrl)
    {
        $this->aurHomeUrl = $aurHomeUrl;

        return $this;
    }

    /**
     * Get aurHomeUrl.
     *
     * @return string
     */
    public function getAurHomeUrl()
    {
        return $this->aurHomeUrl;
    }

    /**
     * Get aurId.
     *
     * @return int
     */
    public function getAurId()
    {
        return $this->aurId;
    }
}
