<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * Tradefair
 */
class Tradefair extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $tradefairdesc;

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
     * @var bool
     */
    private $isenable;


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
     * Set title.
     *
     * @param string $title
     *
     * @return Tradefair
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
     * Set tradefairdesc.
     *
     * @param string $tradefairdesc
     *
     * @return Tradefair
     */
    public function setTradefairdesc($tradefairdesc)
    {
        $this->tradefairdesc = $tradefairdesc;

        return $this;
    }

    /**
     * Get tradefairdesc.
     *
     * @return string
     */
    public function getTradefairdesc()
    {
        return $this->tradefairdesc;
    }

    /**
     * Set picture.
     *
     * @param string $picture
     *
     * @return Tradefair
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
     * @return Tradefair
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
     * @return Tradefair
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
     * Set isenable.
     *
     * @param bool $isenable
     *
     * @return Tradefair
     */
    public function setIsenable($isenable)
    {
        $this->isenable = $isenable;

        return $this;
    }

    /**
     * Get isenable.
     *
     * @return bool
     */
    public function getIsenable()
    {
        return $this->isenable;
    }
}
