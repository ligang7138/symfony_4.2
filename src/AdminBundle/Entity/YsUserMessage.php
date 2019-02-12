<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;

/**
 * YsUserMessage
 *
 * @ORM\Table(name="qy_user_message")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 */
class YsUserMessage
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="u_code", type="string", length=45, nullable=false, options={"comment"="用户code"})
     */
    private $uCode;

    /**
     * @var int
     *
     * @ORM\Column(name="msg_id", type="integer", nullable=false, options={"comment"="消息ID"})
     */
    private $msgId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="read_status", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="是否已读【0未读，1已读】"})
	 */
	private $readStatus = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="is_del", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="是否删除 1.没删除，2.删除"})
	 */
	private $isDel = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

	/**
	 * Get id.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
     * Set uCode.
     *
     * @param string $uCode
     *
     * @return YsUserMessage
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
     * Set msgId.
     *
     * @param int $msgId
     *
     * @return YsUserMessage
     */
    public function setMsgId($msgId)
    {
        $this->msgId = $msgId;

        return $this;
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
	 * Set readStatus.
	 *
	 * @param int $readStatus
	 *
	 * @return YsUserMessage
	 */
	public function setReadStatus($readStatus)
	{
		$this->readStatus = $readStatus;

		return $this;
	}

	/**
	 * Get readStatus.
	 *
	 * @return int
	 */
	public function getReadStatus()
	{
		return $this->readStatus;
	}

	/**
	 * Set isDel.
	 *
	 * @param int $isDel
	 *
	 * @return YsUserMessage
	 */
	public function setIsDel($isDel)
	{
		$this->isDel = $isDel;

		return $this;
	}

	/**
	 * Get isDel.
	 *
	 * @return int
	 */
	public function getIsDel()
	{
		return $this->isDel;
	}

	/**
	 * Set createTime.
	 *
	 * @param \DateTime $createTime
	 * @return YsUserMessage
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
	 * @PrePersist
	 */
	public function PrePersist(){
		if(is_null($this->getCreateTime())){
			$this->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
		}
	}

}
