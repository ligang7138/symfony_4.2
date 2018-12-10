<?php

namespace App\Yszc\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
/**
 * YsMessage
 *
 * @ORM\Table(name="qy_message")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 */
class YsMessage
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=true, options={"comment"="管理员id"})
     */
    private $adminId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="u_code", type="string", length=25, nullable=true, options={"comment"="用户code"})
     */
    private $uCode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="msg_title", type="string", length=60, nullable=false, options={"comment"="消息标题"})
     */
    private $msgTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="msg_content", type="string", length=1500, nullable=false, options={"comment"="消息内容"})
     */
    private $msgContent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="msg_add_time", type="datetime", nullable=false, options={"comment"="添加时间"})
     */
    private $msgAddTime;

    /**
     * @var string
     *
     * @ORM\Column(name="msg_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="消息状态【0未读，1已读】"})
     */
    private $msgStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="msg_send_status", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="消息发布状态 1:未发布 2:已发布"})
     */
    private $msgSendStatus = '2';

    /**
     * @var string
     *
     * @ORM\Column(name="msg_send_type", type="string", length=1, nullable=false, options={"default"="1","fixed"=true,"comment"="消息来源 1:系统自动生成 2:后台手动生成"})
     */
    private $msgSendType = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="msg_type", type="string", length=1, nullable=false, options={"default"="1","comment"="消息类别【1系统消息，2订单消息，3运营消息】"})
     */
    private $msgType = '1';


	/**
	 * @var string
	 *
	 * @ORM\Column(name="is_bounce", type="string", length=1, nullable=false, options={"default"="1","comment"="是否弹框 1.不弹 2.弹"})
	 */
	private $isBounce = '1';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="msg_sys_type", type="string", length=1, nullable=false, options={"default"="1","comment"="消息类别【1:所有,2:App,3:商户后台】"})
	 */
	private $msgSysType = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="msg_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $msgId;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="publisher_id", type="integer", nullable=false, options={"comment"="发布人id"})
	 */
	private $publisherId = 0;


    /**
     * Set adminId.
     *
     * @param int|null $adminId
     *
     * @return YsMessage
     */
    public function setAdminId($adminId = null)
    {
        $this->adminId = $adminId;

        return $this;
    }

    /**
     * Get adminId.
     *
     * @return int|null
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set uCode.
     *
     * @param string|null $uCode
     *
     * @return YsMessage
     */
    public function setUCode($uCode = null)
    {
        $this->uCode = $uCode;

        return $this;
    }

    /**
     * Get uCode.
     *
     * @return string|null
     */
    public function getUCode()
    {
        return $this->uCode;
    }

    /**
     * Set msgTitle.
     *
     * @param string $msgTitle
     *
     * @return YsMessage
     */
    public function setMsgTitle($msgTitle)
    {
        $this->msgTitle = $msgTitle;

        return $this;
    }

    /**
     * Get msgTitle.
     *
     * @return string
     */
    public function getMsgTitle()
    {
        return $this->msgTitle;
    }

    /**
     * Set msgContent.
     *
     * @param string $msgContent
     *
     * @return YsMessage
     */
    public function setMsgContent($msgContent)
    {
        $this->msgContent = $msgContent;

        return $this;
    }

    /**
     * Get msgContent.
     *
     * @return string
     */
    public function getMsgContent()
    {
        return $this->msgContent;
    }

    /**
     * Set msgAddTime.
     *
     * @param \DateTime $msgAddTime
     *
     * @return YsMessage
     */
    public function setMsgAddTime($msgAddTime)
    {
        $this->msgAddTime = $msgAddTime;

        return $this;
    }

    /**
     * Get msgAddTime.
     *
     * @return \DateTime
     */
    public function getMsgAddTime()
    {
        return $this->msgAddTime;
    }

    /**
     * Set msgStatus.
     *
     * @param string $msgStatus
     *
     * @return YsMessage
     */
    public function setMsgStatus($msgStatus)
    {
        $this->msgStatus = $msgStatus;

        return $this;
    }

    /**
     * Get msgStatus.
     *
     * @return string
     */
    public function getMsgStatus()
    {
        return $this->msgStatus;
    }
    /**
     * Set msgSendType.
     *
     * @param string $msgSendType
     *
     * @return YsMessage
     */
    public function setMsgSendType($msgSendType)
    {
        $this->msgSendType = $msgSendType;

        return $this;
    }

    /**
     * Get msgSendType.
     *
     * @return string
     */
    public function getMsgSendType()
    {
        return $this->msgSendType;
    }

    /**
     * Set msgSendStatus.
     *
     * @param string $msgSendStatus
     * @return YsMessage
     */
    public function setMsgSendStatus($msgSendStatus)
    {
    	if($msgSendStatus == 2){
		    $this->setMsgAddTime(new \DateTime(date('Y-m-d H:i:s')));
	    }
        $this->msgSendStatus = $msgSendStatus;

        return $this;
    }

    /**
     * Get msgSendStatus.
     *
     * @return string
     */
    public function getMsgSendStatus()
    {
        return $this->msgSendStatus;
    }

    /**
     * Set msgType.
     *
     * @param string $msgType
     *
     * @return YsMessage
     */
    public function setMsgType($msgType)
    {
        $this->msgType = $msgType;

        return $this;
    }

    /**
     * Get msgType.
     *
     * @return string
     */
    public function getMsgType()
    {
        return $this->msgType;
    }

	/**
	 * Set isBounce.
	 *
	 * @param string $isBounce
	 *
	 * @return YsMessage
	 */
	public function setIsBounce($isBounce)
	{
		$this->isBounce = $isBounce;

		return $this;
	}

	/**
	 * Get isBounce.
	 *
	 * @return string
	 */
	public function getIsBounce()
	{
		return $this->isBounce;
	}
	/**
	 * Set msgSysType.
	 *
	 * @param string $msgSysType
	 *
	 * @return YsMessage
	 */
	public function setMsgSysType($msgSysType)
	{
		$this->msgSysType = $msgSysType;

		return $this;
	}

	/**
	 * Get msgSysType.
	 *
	 * @return string
	 */
	public function getMsgSysType()
	{
		return $this->msgSysType;
	}

    /**
     * Get msgId.
     *
     * @return int
     */
    public function getMsgId()
    {
        return $this->msgId;
    }

	/**
	 * Set publisherId.
	 *
	 * @param int $publisherId
	 *
	 * @return YsMessage
	 */
	public function setPublisherId($publisherId)
	{
		$this->publisherId = $publisherId;

		return $this;
	}

	/**
	 * Get publisherId.
	 *
	 * @return int
	 */
	public function getPublisherId()
	{
		return $this->publisherId;
	}
}
