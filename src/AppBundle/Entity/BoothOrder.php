<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * BoothOrder
 */
class BoothOrder extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $uid;

    /**
     * @var string
     */
    private $orderNo;

    /**
     * @var float
     */
    private $total;

    /**
     * @var bool
     */
    private $isinvoice;

    /**
     * @var int
     */
    private $invoiceid;

    /**
     * @var string
     */
    private $payChannel;

    /**
     * @var \DateTime
     */
    private $payTime;

    /**
     * @var string
     */
    private $outTradeNo;

    /**
     * @var \DateTime
     */
    private $createAt;

    /**
     * @var string
     */
    private $remarks;

    /**
     * @var int
     */
    private $orderStatus;


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
     * Set uid.
     *
     * @param int $uid
     *
     * @return BoothOrder
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid.
     *
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set orderNo.
     *
     * @param string $orderNo
     *
     * @return BoothOrder
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    /**
     * Get orderNo.
     *
     * @return string
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * Set total.
     *
     * @param float $total
     *
     * @return BoothOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set isinvoice.
     *
     * @param bool $isinvoice
     *
     * @return BoothOrder
     */
    public function setIsinvoice($isinvoice)
    {
        $this->isinvoice = $isinvoice;

        return $this;
    }

    /**
     * Get isinvoice.
     *
     * @return bool
     */
    public function getIsinvoice()
    {
        return $this->isinvoice;
    }

    /**
     * Set invoiceid.
     *
     * @param int $invoiceid
     *
     * @return BoothOrder
     */
    public function setInvoiceid($invoiceid)
    {
        $this->invoiceid = $invoiceid;

        return $this;
    }

    /**
     * Get invoiceid.
     *
     * @return int
     */
    public function getInvoiceid()
    {
        return $this->invoiceid;
    }

    /**
     * Set payChannel.
     *
     * @param string $payChannel
     *
     * @return BoothOrder
     */
    public function setPayChannel($payChannel)
    {
        $this->payChannel = $payChannel;

        return $this;
    }

    /**
     * Get payChannel.
     *
     * @return string
     */
    public function getPayChannel()
    {
        return $this->payChannel;
    }

    /**
     * Set payTime.
     *
     * @param \DateTime $payTime
     *
     * @return BoothOrder
     */
    public function setPayTime($payTime)
    {
        $this->payTime = $payTime;

        return $this;
    }

    /**
     * Get payTime.
     *
     * @return \DateTime
     */
    public function getPayTime()
    {
        return $this->payTime;
    }

    /**
     * Set outTradeNo.
     *
     * @param string $outTradeNo
     *
     * @return BoothOrder
     */
    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;

        return $this;
    }

    /**
     * Get outTradeNo.
     *
     * @return string
     */
    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return BoothOrder
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt.
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set remarks.
     *
     * @param string $remarks
     *
     * @return BoothOrder
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks.
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set orderStatus.
     *
     * @param int $orderStatus
     *
     * @return BoothOrder
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus.
     *
     * @return int
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }
}
