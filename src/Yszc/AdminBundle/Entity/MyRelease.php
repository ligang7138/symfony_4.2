<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyRelease
 *
 * @ORM\Table(name="my_release")
 * @ORM\Entity
 */
class MyRelease
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="app_url", type="string", length=100, nullable=false)
     */
    private $appUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_force", type="boolean", nullable=false)
     */
    private $isForce = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="string", length=600, nullable=true)
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="version_no", type="string", length=10, nullable=false)
     */
    private $versionNo;

    /**
     * @var string
     *
     * @ORM\Column(name="filesize", type="string", length=15, nullable=false)
     */
    private $filesize;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=1, nullable=true,options={"comment":"系统类型 1:android,2:ios"})
     */
    private $type;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="app_type", type="string",length=1, nullable=true,options={"comment":"app类型，1=沃投农，2=未来内农资网"})
	 */
	private $appType;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set title
     *
     * @param string $title
     *
     * @return MyRelease
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set updateTime
     *
     * @param \DateTime $updateTime
     *
     * @return MyRelease
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * Set appUrl
     *
     * @param string $appUrl
     *
     * @return MyRelease
     */
    public function setAppUrl($appUrl)
    {
        $this->appUrl = $appUrl;

        return $this;
    }

    /**
     * Get appUrl
     *
     * @return string
     */
    public function getAppUrl()
    {
        return $this->appUrl;
    }

    /**
     * Set isForce
     *
     * @param boolean $isForce
     *
     * @return MyRelease
     */
    public function setIsForce($isForce)
    {
        $this->isForce = $isForce;

        return $this;
    }

    /**
     * Get isForce
     *
     * @return boolean
     */
    public function getIsForce()
    {
        return $this->isForce;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return MyRelease
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set versionNo
     *
     * @param string $versionNo
     *
     * @return MyRelease
     */
    public function setVersionNo($versionNo)
    {
        $this->versionNo = $versionNo;

        return $this;
    }

    /**
     * Get versionNo
     *
     * @return string
     */
    public function getVersionNo()
    {
        return $this->versionNo;
    }

    /**
     * Set filesize
     *
     * @param string $filesize
     *
     * @return MyRelease
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return string
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MyRelease
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

	/**
	 * Set appType
	 *
	 * @param string appType
	 *
	 * @return MyRelease
	 */
	public function setAppType($appType)
	{
		$this->appType = $appType;

		return $this;
	}

	/**
	 * Get appType
	 *
	 * @return string
	 */
	public function getAppType()
	{
		return $this->appType;
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
