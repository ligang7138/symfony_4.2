<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsBanners
 *
 * @ORM\Table(name="qy_banners")
 * @ORM\Entity
 */
class YsBanners
{
    /**
     * @var string
     *
     * @ORM\Column(name="b_title", type="string", length=50, nullable=false, options={"comment"="标题"})
     */
    private $bTitle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="b_type", type="string", length=1, nullable=true, options={"comment"="广告位分类 1.首页banner"})
	 */
	private $bType='1';

	/**
     * @var string
     *
     * @ORM\Column(name="b_url", type="string", length=150, nullable=false, options={"comment"="链接地址"})
     */
    private $bUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="b_img", type="string", length=300, nullable=false, options={"comment"="图片路径"})
     */
    private $bImg = '';

    /**
     * @var string
     *
     * @ORM\Column(name="b_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="状态【0无效，1有效】"})
     */
    private $bStatus = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="b_order", type="integer", nullable=false, options={"comment"="排序"})
     */
    private $bOrder = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="b_update_time", type="datetime", nullable=false, options={"comment"="添加时间"})
     */
    private $bUpdateTime;

    /**
     * @var int
     *
     * @ORM\Column(name="b_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bId;
    /**
     * Set bTitle.
     *
     * @param string $bTitle
     *
     * @return YsBanners
     */
    public function setBTitle($bTitle)
    {
        $this->bTitle = $bTitle;

        return $this;
    }

    /**
     * Get bTitle.
     *
     * @return string
     */
    public function getBTitle()
    {
        return $this->bTitle;
    }

	/**
	 * Set bType.
	 *
	 * @param string $bType
	 *
	 * @return YsBanners
	 */
	public function setBType($bType)
	{
		$this->bType = $bType;

		return $this;
	}

	/**
	 * Get BType.
	 *
	 * @return string
	 */
	public function getBType()
	{
		return $this->bType;
	}
    /**
     * Set bUrl.
     *
     * @param string $bUrl
     *
     * @return YsBanners
     */
    public function setBUrl($bUrl)
    {
        $this->bUrl = $bUrl;

        return $this;
    }

    /**
     * Get bUrl.
     *
     * @return string
     */
    public function getBUrl()
    {
        return $this->bUrl;
    }

    /**
     * Set bImg.
     *
     * @param string $bImg
     *
     * @return YsBanners
     */
    public function setBImg($bImg)
    {
        $this->bImg = $bImg;

        return $this;
    }

    /**
     * Get bImg.
     *
     * @return string
     */
    public function getBImg()
    {
        return $this->bImg;
    }

    /**
     * Set bStatus.
     *
     * @param string $bStatus
     *
     * @return YsBanners
     */
    public function setBStatus($bStatus)
    {
        $this->bStatus = $bStatus;

        return $this;
    }

    /**
     * Get bStatus.
     *
     * @return string
     */
    public function getBStatus()
    {
        return $this->bStatus;
    }

    /**
     * Set bOrder.
     *
     * @param int $bOrder
     *
     * @return YsBanners
     */
    public function setBOrder($bOrder)
    {
        $this->bOrder = $bOrder;

        return $this;
    }

    /**
     * Get bOrder.
     *
     * @return int
     */
    public function getBOrder()
    {
        return $this->bOrder;
    }

    /**
     * Set bUpdateTime.
     *
     * @param \DateTime $bUpdateTime
     *
     * @return YsBanners
     */
    public function setBUpdateTime($bUpdateTime)
    {
        $this->bUpdateTime = $bUpdateTime;

        return $this;
    }

    /**
     * Get bUpdateTime.
     *
     * @return \DateTime
     */
    public function getBUpdateTime()
    {
        return $this->bUpdateTime;
    }

    /**
     * Get bId.
     *
     * @return int
     */
    public function getBId()
    {
        return $this->bId;
    }
}
