<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * BoothVerificationUser
 */
class BoothVerificationUser extends AbsEntry
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
     * @var \DateTime
     */
    private $createAt;


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
     * @return BoothVerificationUser
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
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return BoothVerificationUser
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
}
