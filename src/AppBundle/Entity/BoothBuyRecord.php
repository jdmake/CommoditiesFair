<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * BoothBuyRecord
 */
class BoothBuyRecord extends AbsEntry
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
     * @var float
     */
    private $total;

    /**
     * @var string
     */
    private $verificationCode;

    /**
     * @var \DateTime
     */
    private $createAt;

    /**
     * @var boolean
     */
    private $isuse;




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
     * @return BoothBuyRecord
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
     * @return BoothBuyRecord
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
     * Set total.
     *
     * @param float $total
     *
     * @return BoothBuyRecord
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
     * Set verificationCode.
     *
     * @param string $verificationCode
     *
     * @return BoothBuyRecord
     */
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    /**
     * Get verificationCode.
     *
     * @return string
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return BoothBuyRecord
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
     * @return bool
     */
    public function isIsuse()
    {
        return $this->isuse;
    }

    /**
     * @param bool $isuse
     */
    public function setIsuse($isuse)
    {
        $this->isuse = $isuse;
    }


    /**
     * Get isuse.
     *
     * @return bool
     */
    public function getIsuse()
    {
        return $this->isuse;
    }
}
