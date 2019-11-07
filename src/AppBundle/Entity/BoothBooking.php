<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * BoothBooking
 */
class BoothBooking extends AbsEntry
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
    private $formid;

    /**
     * @var string
     */
    private $businessLicense;

    /**
     * @var string
     */
    private $sfzLicense;

    /**
     * @var string
     */
    private $sb;

    /**
     * @var string
     */
    private $lsspLicense;

    /**
     * @var string
     */
    private $wghzsLicense;

    /**
     * @var string
     */
    private $scxkzLicense;

    /**
     * @var string
     */
    private $xgzl;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $reviewMessage;

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
     * @return BoothBooking
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
     * @return BoothBooking
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
     * Set formid.
     *
     * @param string $formid
     *
     * @return BoothBooking
     */
    public function setFormid($formid)
    {
        $this->formid = $formid;

        return $this;
    }

    /**
     * Get formid.
     *
     * @return string
     */
    public function getFormid()
    {
        return $this->formid;
    }

    /**
     * Set businessLicense.
     *
     * @param string $businessLicense
     *
     * @return BoothBooking
     */
    public function setBusinessLicense($businessLicense)
    {
        $this->businessLicense = $businessLicense;

        return $this;
    }

    /**
     * Get businessLicense.
     *
     * @return string
     */
    public function getBusinessLicense()
    {
        return $this->businessLicense;
    }

    /**
     * Set sfzLicense.
     *
     * @param string $sfzLicense
     *
     * @return BoothBooking
     */
    public function setSfzLicense($sfzLicense)
    {
        $this->sfzLicense = $sfzLicense;

        return $this;
    }

    /**
     * Get sfzLicense.
     *
     * @return string
     */
    public function getSfzLicense()
    {
        return $this->sfzLicense;
    }

    /**
     * Set sb.
     *
     * @param string $sb
     *
     * @return BoothBooking
     */
    public function setSb($sb)
    {
        $this->sb = $sb;

        return $this;
    }

    /**
     * Get sb.
     *
     * @return string
     */
    public function getSb()
    {
        return $this->sb;
    }

    /**
     * Set lsspLicense.
     *
     * @param string $lsspLicense
     *
     * @return BoothBooking
     */
    public function setLsspLicense($lsspLicense)
    {
        $this->lsspLicense = $lsspLicense;

        return $this;
    }

    /**
     * Get lsspLicense.
     *
     * @return string
     */
    public function getLsspLicense()
    {
        return $this->lsspLicense;
    }

    /**
     * Set wghzsLicense.
     *
     * @param string $wghzsLicense
     *
     * @return BoothBooking
     */
    public function setWghzsLicense($wghzsLicense)
    {
        $this->wghzsLicense = $wghzsLicense;

        return $this;
    }

    /**
     * Get wghzsLicense.
     *
     * @return string
     */
    public function getWghzsLicense()
    {
        return $this->wghzsLicense;
    }

    /**
     * Set scxkzLicense.
     *
     * @param string $scxkzLicense
     *
     * @return BoothBooking
     */
    public function setScxkzLicense($scxkzLicense)
    {
        $this->scxkzLicense = $scxkzLicense;

        return $this;
    }

    /**
     * Get scxkzLicense.
     *
     * @return string
     */
    public function getScxkzLicense()
    {
        return $this->scxkzLicense;
    }

    /**
     * Set xgzl.
     *
     * @param string $xgzl
     *
     * @return BoothBooking
     */
    public function setXgzl($xgzl)
    {
        $this->xgzl = $xgzl;

        return $this;
    }

    /**
     * Get xgzl.
     *
     * @return string
     */
    public function getXgzl()
    {
        return $this->xgzl;
    }

    /**
     * Set status.
     *
     * @param integer $status
     *
     * @return BoothBooking
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set reviewMessage.
     *
     * @param string $reviewMessage
     *
     * @return BoothBooking
     */
    public function setReviewMessage($reviewMessage)
    {
        $this->reviewMessage = $reviewMessage;

        return $this;
    }

    /**
     * Get reviewMessage.
     *
     * @return string
     */
    public function getReviewMessage()
    {
        return $this->reviewMessage;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return BoothBooking
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
