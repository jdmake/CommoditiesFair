<?php

namespace AppBundle\Entity;

/**
 * SalesRecord
 */
class SalesRecord
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
     * @var int
     */
    private $boothId;

    /**
     * @var string
     */
    private $goodsName;

    /**
     * @var int
     */
    private $goodsCount;

    /**
     * @var float
     */
    private $goodsTotal;

    /**
     * @var \DateTime
     */
    private $reportTime;


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
     * @return SalesRecord
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
     * Set boothId.
     *
     * @param int $boothId
     *
     * @return SalesRecord
     */
    public function setBoothId($boothId)
    {
        $this->boothId = $boothId;

        return $this;
    }

    /**
     * Get boothId.
     *
     * @return int
     */
    public function getBoothId()
    {
        return $this->boothId;
    }

    /**
     * Set goodsName.
     *
     * @param string $goodsName
     *
     * @return SalesRecord
     */
    public function setGoodsName($goodsName)
    {
        $this->goodsName = $goodsName;

        return $this;
    }

    /**
     * Get goodsName.
     *
     * @return string
     */
    public function getGoodsName()
    {
        return $this->goodsName;
    }

    /**
     * Set goodsCount.
     *
     * @param int $goodsCount
     *
     * @return SalesRecord
     */
    public function setGoodsCount($goodsCount)
    {
        $this->goodsCount = $goodsCount;

        return $this;
    }

    /**
     * Get goodsCount.
     *
     * @return int
     */
    public function getGoodsCount()
    {
        return $this->goodsCount;
    }

    /**
     * Set goodsTotal.
     *
     * @param float $goodsTotal
     *
     * @return SalesRecord
     */
    public function setGoodsTotal($goodsTotal)
    {
        $this->goodsTotal = $goodsTotal;

        return $this;
    }

    /**
     * Get goodsTotal.
     *
     * @return float
     */
    public function getGoodsTotal()
    {
        return $this->goodsTotal;
    }

    /**
     * Set reportTime.
     *
     * @param \DateTime $reportTime
     *
     * @return SalesRecord
     */
    public function setReportTime($reportTime)
    {
        $this->reportTime = $reportTime;

        return $this;
    }

    /**
     * Get reportTime.
     *
     * @return \DateTime
     */
    public function getReportTime()
    {
        return $this->reportTime;
    }
}
