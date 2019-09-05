<?php

namespace AppBundle\Entity;

/**
 * BoothOrderDetail
 */
class BoothOrderDetail
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $orderNo;

    /**
     * @var string
     */
    private $boothTitle;

    /**
     * @var int
     */
    private $boothNumber;

    /**
     * @var int
     */
    private $boothPrice;

    /**
     * @var int
     */
    private $boothSize;

    /**
     * @var \DateTime
     */
    private $boothStarttime;

    /**
     * @var \DateTime
     */
    private $boothEndtime;


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
     * Set orderNo.
     *
     * @param string $orderNo
     *
     * @return BoothOrderDetail
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
     * Set boothTitle.
     *
     * @param string $boothTitle
     *
     * @return BoothOrderDetail
     */
    public function setBoothTitle($boothTitle)
    {
        $this->boothTitle = $boothTitle;

        return $this;
    }

    /**
     * Get boothTitle.
     *
     * @return string
     */
    public function getBoothTitle()
    {
        return $this->boothTitle;
    }

    /**
     * Set boothNumber.
     *
     * @param int $boothNumber
     *
     * @return BoothOrderDetail
     */
    public function setBoothNumber($boothNumber)
    {
        $this->boothNumber = $boothNumber;

        return $this;
    }

    /**
     * Get boothNumber.
     *
     * @return int
     */
    public function getBoothNumber()
    {
        return $this->boothNumber;
    }

    /**
     * Set boothPrice.
     *
     * @param int $boothPrice
     *
     * @return BoothOrderDetail
     */
    public function setBoothPrice($boothPrice)
    {
        $this->boothPrice = $boothPrice;

        return $this;
    }

    /**
     * Get boothPrice.
     *
     * @return int
     */
    public function getBoothPrice()
    {
        return $this->boothPrice;
    }

    /**
     * Set boothSize.
     *
     * @param int $boothSize
     *
     * @return BoothOrderDetail
     */
    public function setBoothSize($boothSize)
    {
        $this->boothSize = $boothSize;

        return $this;
    }

    /**
     * Get boothSize.
     *
     * @return int
     */
    public function getBoothSize()
    {
        return $this->boothSize;
    }

    /**
     * Set boothStarttime.
     *
     * @param \DateTime $boothStarttime
     *
     * @return BoothOrderDetail
     */
    public function setBoothStarttime($boothStarttime)
    {
        $this->boothStarttime = $boothStarttime;

        return $this;
    }

    /**
     * Get boothStarttime.
     *
     * @return \DateTime
     */
    public function getBoothStarttime()
    {
        return $this->boothStarttime;
    }

    /**
     * Set boothEndtime.
     *
     * @param \DateTime $boothEndtime
     *
     * @return BoothOrderDetail
     */
    public function setBoothEndtime($boothEndtime)
    {
        $this->boothEndtime = $boothEndtime;

        return $this;
    }

    /**
     * Get boothEndtime.
     *
     * @return \DateTime
     */
    public function getBoothEndtime()
    {
        return $this->boothEndtime;
    }
}
