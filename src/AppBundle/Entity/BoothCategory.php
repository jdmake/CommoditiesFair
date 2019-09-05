<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * BoothCategory
 */
class BoothCategory extends AbsEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $sort;


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
     * Set pid.
     *
     * @param int $pid
     *
     * @return BoothCategory
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid.
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return BoothCategory
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
     * Set sort.
     *
     * @param int $sort
     *
     * @return BoothCategory
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
}
