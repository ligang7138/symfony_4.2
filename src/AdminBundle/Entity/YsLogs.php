<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsLogs
 *
 * @ORM\Table(name="qy_logs")
 * @ORM\Entity
 */
class YsLogs
{
    /**
     * @var string
     *
     * @ORM\Column(name="log_admin_name", type="string", length=45, nullable=false, options={"comment"="操作人帐号"})
     */
    private $logAdminName;

    /**
     * @var string
     *
     * @ORM\Column(name="curr_status", type="string", length=2, nullable=false, options={"fixed"=true,"comment"="当前状态【操作后状态】"})
     */
    private $currStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="up_status", type="string", length=2, nullable=false, options={"fixed"=true,"comment"="上一步状态"})
     */
    private $upStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="log_remark", type="string", length=300, nullable=false, options={"comment"="备注"})
     */
    private $logRemark;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="log_add_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $logAddTime = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false, options={"comment"="订单id"})
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="log_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $logId;



    /**
     * Set logAdminName.
     *
     * @param string $logAdminName
     *
     * @return YsLogs
     */
    public function setLogAdminName($logAdminName)
    {
        $this->logAdminName = $logAdminName;

        return $this;
    }

    /**
     * Get logAdminName.
     *
     * @return string
     */
    public function getLogAdminName()
    {
        return $this->logAdminName;
    }

    /**
     * Set currStatus.
     *
     * @param string $currStatus
     *
     * @return YsLogs
     */
    public function setCurrStatus($currStatus)
    {
        $this->currStatus = $currStatus;

        return $this;
    }

    /**
     * Get currStatus.
     *
     * @return string
     */
    public function getCurrStatus()
    {
        return $this->currStatus;
    }

    /**
     * Set upStatus.
     *
     * @param string $upStatus
     *
     * @return YsLogs
     */
    public function setUpStatus($upStatus)
    {
        $this->upStatus = $upStatus;

        return $this;
    }

    /**
     * Get upStatus.
     *
     * @return string
     */
    public function getUpStatus()
    {
        return $this->upStatus;
    }

    /**
     * Set logRemark.
     *
     * @param string $logRemark
     *
     * @return YsLogs
     */
    public function setLogRemark($logRemark)
    {
        $this->logRemark = $logRemark;

        return $this;
    }

    /**
     * Get logRemark.
     *
     * @return string
     */
    public function getLogRemark()
    {
        return $this->logRemark;
    }

    /**
     * Set logAddTime.
     *
     * @param \DateTime $logAddTime
     *
     * @return YsLogs
     */
    public function setLogAddTime($logAddTime)
    {
        $this->logAddTime = $logAddTime;

        return $this;
    }

    /**
     * Get logAddTime.
     *
     * @return \DateTime
     */
    public function getLogAddTime()
    {
        return $this->logAddTime;
    }

    /**
     * Set orderId.
     *
     * @param int $orderId
     *
     * @return YsLogs
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId.
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Get logId.
     *
     * @return int
     */
    public function getLogId()
    {
        return $this->logId;
    }
}
