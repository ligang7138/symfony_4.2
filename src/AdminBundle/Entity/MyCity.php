<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyCity
 *
 * @ORM\Table(name="my_city")
 * @ORM\Entity
 */
class MyCity
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="city_name", type="string", length=128, nullable=true)
     */
    private $cityName;

    /**
     * @var string
     *
     * @ORM\Column(name="city_key", type="string", length=64, nullable=false)
     */
    private $cityKey;

    /**
     * @var string
     *
     * @ORM\Column(name="city_path", type="string", length=255, nullable=false)
     */
    private $cityPath;

    /**
     * @var string
     *
     * @ORM\Column(name="city_pid", type="string", length=64, nullable=false)
     */
    private $cityPid;

    /**
     * @var string
     *
     * @ORM\Column(name="city_id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cityId;



    /**
     * Set cityName.
     *
     * @param string|null $cityName
     *
     * @return MyCity
     */
    public function setCityName($cityName = null)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName.
     *
     * @return string|null
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set cityKey.
     *
     * @param string $cityKey
     *
     * @return MyCity
     */
    public function setCityKey($cityKey)
    {
        $this->cityKey = $cityKey;

        return $this;
    }

    /**
     * Get cityKey.
     *
     * @return string
     */
    public function getCityKey()
    {
        return $this->cityKey;
    }

    /**
     * Set cityPath.
     *
     * @param string $cityPath
     *
     * @return MyCity
     */
    public function setCityPath($cityPath)
    {
        $this->cityPath = $cityPath;

        return $this;
    }

    /**
     * Get cityPath.
     *
     * @return string
     */
    public function getCityPath()
    {
        return $this->cityPath;
    }

    /**
     * Set cityPid.
     *
     * @param string $cityPid
     *
     * @return MyCity
     */
    public function setCityPid($cityPid)
    {
        $this->cityPid = $cityPid;

        return $this;
    }

    /**
     * Get cityPid.
     *
     * @return string
     */
    public function getCityPid()
    {
        return $this->cityPid;
    }

    /**
     * Get cityId.
     *
     * @return string
     */
    public function getCityId()
    {
        return $this->cityId;
    }
}
