<?php

namespace AppBundle\Entity;

use AppBundle\Custom\Entry\AbsEntry;

/**
 * Member
 */
class Member extends AbsEntry
{
    /**
     * @var int
     */
    private $uid;

    /**
     * @var string
     */
    private $openid;

    /**
     * @var string
     */
    private $formid;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var int
     */
    private $level;

    /**
     * @var int
     */
    private $parentid;

    /**
     * @var int
     */
    private $credit;

    /**
     * @var string
     */
    private $lastloginip;

    /**
     * @var \DateTime
     */
    private $lastlogintime;

    /**
     * @var int
     */
    private $profileid;

    /**
     * @var \DateTime
     */
    private $regtime;

    /**
     * @var bool
     */
    private $enable;


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
     * Set openid.
     *
     * @param string $openid
     *
     * @return Member
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * Get openid.
     *
     * @return string
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * @return string
     */
    public function getFormid()
    {
        return $this->formid;
    }

    /**
     * @param string $formid
     */
    public function setFormid($formid)
    {
        $this->formid = $formid;
    }

    /**
     * Set mobile.
     *
     * @param string $mobile
     *
     * @return Member
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile.
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return Member
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set parentid.
     *
     * @param int $parentid
     *
     * @return Member
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid.
     *
     * @return int
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set credit.
     *
     * @param int $credit
     *
     * @return Member
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit.
     *
     * @return int
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set lastloginip.
     *
     * @param string $lastloginip
     *
     * @return Member
     */
    public function setLastloginip($lastloginip)
    {
        $this->lastloginip = $lastloginip;

        return $this;
    }

    /**
     * Get lastloginip.
     *
     * @return string
     */
    public function getLastloginip()
    {
        return $this->lastloginip;
    }

    /**
     * Set lastlogintime.
     *
     * @param \DateTime $lastlogintime
     *
     * @return Member
     */
    public function setLastlogintime($lastlogintime)
    {
        $this->lastlogintime = $lastlogintime;

        return $this;
    }

    /**
     * Get lastlogintime.
     *
     * @return \DateTime
     */
    public function getLastlogintime()
    {
        return $this->lastlogintime;
    }

    /**
     * Set profileid.
     *
     * @param int $profileid
     *
     * @return Member
     */
    public function setProfileid($profileid)
    {
        $this->profileid = $profileid;

        return $this;
    }

    /**
     * Get profileid.
     *
     * @return int
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * Set regtime.
     *
     * @param \DateTime $regtime
     *
     * @return Member
     */
    public function setRegtime($regtime)
    {
        $this->regtime = $regtime;

        return $this;
    }

    /**
     * Get regtime.
     *
     * @return \DateTime
     */
    public function getRegtime()
    {
        return $this->regtime;
    }

    /**
     * Set enable.
     *
     * @param bool $enable
     *
     * @return Member
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
}
