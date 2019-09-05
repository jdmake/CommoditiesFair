<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * Booth
 */
class Booth extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var integer
     */
    private $category;

    /**
     * @var int
     */
    private $sort;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $size;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $picture;

    /**
     * @var \DateTime
     */
    private $starttime;

    /**
     * @var \DateTime
     */
    private $endtime;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $visits;

    /**
     * @var bool
     */
    private $enable;


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
     * Set category.
     *
     * @param string $category
     *
     * @return Booth
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set sort.
     *
     * @param int $sort
     *
     * @return Booth
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort.
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Booth
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set number.
     *
     * @param int $number
     *
     * @return Booth
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return Booth
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set price.
     *
     * @param float $price
     *
     * @return Booth
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set picture.
     *
     * @param string $picture
     *
     * @return Booth
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture.
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set starttime.
     *
     * @param \DateTime $starttime
     *
     * @return Booth
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * Get starttime.
     *
     * @return \DateTime
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set endtime.
     *
     * @param \DateTime $endtime
     *
     * @return Booth
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime.
     *
     * @return \DateTime
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Booth
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set visits.
     *
     * @param int $visits
     *
     * @return Booth
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Get visits.
     *
     * @return int
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set enable.
     *
     * @param bool $enable
     *
     * @return Booth
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable.
     *
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }
    /**
     * @var int
     */
    private $row;

    /**
     * @var int
     */
    private $col;


    /**
     * Set row.
     *
     * @param int $row
     *
     * @return Booth
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get row.
     *
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Set col.
     *
     * @param int $col
     *
     * @return Booth
     */
    public function setCol($col)
    {
        $this->col = $col;

        return $this;
    }

    /**
     * Get col.
     *
     * @return int
     */
    public function getCol()
    {
        return $this->col;
    }
}
