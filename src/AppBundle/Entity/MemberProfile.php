<?php

namespace AppBundle\Entity;

/**
 * MemberProfile
 */
class MemberProfile
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var int
     */
    private $gender;

    /**
     * @var string
     */
    private $nickname;


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
     * Set avatar.
     *
     * @param string $avatar
     *
     * @return MemberProfile
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set birthday.
     *
     * @param \DateTime $birthday
     *
     * @return MemberProfile
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday.
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender.
     *
     * @param int $gender
     *
     * @return MemberProfile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set nickname.
     *
     * @param string $nickname
     *
     * @return MemberProfile
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }
}
