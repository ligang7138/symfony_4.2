<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsUserAddress
 *
 * @ORM\Table(name="qy_user_address", indexes={@ORM\Index(name="user_id", columns={"u_code"})})
 * @ORM\Entity
 */
class YsUserAddress
{
    /**
     * @var string
     *
     * @ORM\Column(name="ua_remark", type="string", length=50, nullable=false, options={"default"="默认","comment"="短名称"})
     */
    private $uaRemark = '默认';

    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=45, nullable=false, options={"comment"="用户code"})
     */
    private $uCode;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_consignee", type="string", length=60, nullable=false, options={"comment"="收货人"})
     */
    private $uaConsignee;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_province", type="string", length=45, nullable=false, options={"comment"="省份"})
     */
    private $uaProvince;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_city", type="string", length=45, nullable=false, options={"comment"="城市"})
     */
    private $uaCity;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_area", type="string", length=45, nullable=false, options={"comment"="区县"})
     */
    private $uaArea;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_detail_address", type="string", length=150, nullable=false, options={"comment"="详细地址"})
     */
    private $uaDetailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_lat", type="decimal", precision=11, scale=8, nullable=false, options={"comment"="纬度"})
     */
    private $uaLat;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_lng", type="decimal", precision=11, scale=8, nullable=false, options={"comment"="经度"})
     */
    private $uaLng;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_mobile", type="string", length=11, nullable=false, options={"fixed"=true,"comment"="联系电话"})
     */
    private $uaMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="ua_is_default", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="是否默认【0否，1是】"})
     */
    private $uaIsDefault = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="ua_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uaId;



    /**
     * Set uaRemark.
     *
     * @param string $uaRemark
     *
     * @return YsUserAddress
     */
    public function setUaRemark($uaRemark)
    {
        $this->uaRemark = $uaRemark;

        return $this;
    }

    /**
     * Get uaRemark.
     *
     * @return string
     */
    public function getUaRemark()
    {
        return $this->uaRemark;
    }

    /**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsUserAddress
     */
    public function setUCode($uCode)
    {
        $this->uCode = $uCode;

        return $this;
    }

    /**
     * Get uCode.
     *
     * @return string
     */
    public function getUCode()
    {
        return $this->uCode;
    }

    /**
     * Set uaConsignee.
     *
     * @param string $uaConsignee
     *
     * @return YsUserAddress
     */
    public function setUaConsignee($uaConsignee)
    {
        $this->uaConsignee = $uaConsignee;

        return $this;
    }

    /**
     * Get uaConsignee.
     *
     * @return string
     */
    public function getUaConsignee()
    {
        return $this->uaConsignee;
    }

    /**
     * Set uaProvince.
     *
     * @param string $uaProvince
     *
     * @return YsUserAddress
     */
    public function setUaProvince($uaProvince)
    {
        $this->uaProvince = $uaProvince;

        return $this;
    }

    /**
     * Get uaProvince.
     *
     * @return string
     */
    public function getUaProvince()
    {
        return $this->uaProvince;
    }

    /**
     * Set uaCity.
     *
     * @param string $uaCity
     *
     * @return YsUserAddress
     */
    public function setUaCity($uaCity)
    {
        $this->uaCity = $uaCity;

        return $this;
    }

    /**
     * Get uaCity.
     *
     * @return string
     */
    public function getUaCity()
    {
        return $this->uaCity;
    }

    /**
     * Set uaArea.
     *
     * @param string $uaArea
     *
     * @return YsUserAddress
     */
    public function setUaArea($uaArea)
    {
        $this->uaArea = $uaArea;

        return $this;
    }

    /**
     * Get uaArea.
     *
     * @return string
     */
    public function getUaArea()
    {
        return $this->uaArea;
    }

    /**
     * Set uaDetailAddress.
     *
     * @param string $uaDetailAddress
     *
     * @return YsUserAddress
     */
    public function setUaDetailAddress($uaDetailAddress)
    {
        $this->uaDetailAddress = $uaDetailAddress;

        return $this;
    }

    /**
     * Get uaDetailAddress.
     *
     * @return string
     */
    public function getUaDetailAddress()
    {
        return $this->uaDetailAddress;
    }

    /**
     * Set uaLat.
     *
     * @param string $uaLat
     *
     * @return YsUserAddress
     */
    public function setUaLat($uaLat)
    {
        $this->uaLat = $uaLat;

        return $this;
    }

    /**
     * Get uaLat.
     *
     * @return string
     */
    public function getUaLat()
    {
        return $this->uaLat;
    }

    /**
     * Set uaLng.
     *
     * @param string $uaLng
     *
     * @return YsUserAddress
     */
    public function setUaLng($uaLng)
    {
        $this->uaLng = $uaLng;

        return $this;
    }

    /**
     * Get uaLng.
     *
     * @return string
     */
    public function getUaLng()
    {
        return $this->uaLng;
    }

    /**
     * Set uaMobile.
     *
     * @param string $uaMobile
     *
     * @return YsUserAddress
     */
    public function setUaMobile($uaMobile)
    {
        $this->uaMobile = $uaMobile;

        return $this;
    }

    /**
     * Get uaMobile.
     *
     * @return string
     */
    public function getUaMobile()
    {
        return $this->uaMobile;
    }

    /**
     * Set uaIsDefault.
     *
     * @param string $uaIsDefault
     *
     * @return YsUserAddress
     */
    public function setUaIsDefault($uaIsDefault)
    {
        $this->uaIsDefault = $uaIsDefault;

        return $this;
    }

    /**
     * Get uaIsDefault.
     *
     * @return string
     */
    public function getUaIsDefault()
    {
        return $this->uaIsDefault;
    }

    /**
     * Get uaId.
     *
     * @return int
     */
    public function getUaId()
    {
        return $this->uaId;
    }
}
