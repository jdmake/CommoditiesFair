<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/25
// +----------------------------------------------------------------------


namespace AppBundle\Service;

use AppBundle\Entity\Member;
use AppBundle\Entity\MemberProfile;

/**
 * 用户服务
 * Class MemberService
 * @package AppBundle\Service
 */
class MemberService extends AbsService
{

    /**
     * 创建唯一的用户, 不重复
     * @param $openid
     * @param array $profileArray
     * @return int
     * @throws \Exception
     */
    public function createUserOnly($openid, $profileArray = [])
    {
        if ($this->isUserExistByOpenId($openid)) {
            $res = $this->findByOpenid($openid);
            if ($res) {
                return $res->getUid();
            }
        }

        $entry = new Member();
        $entry->setOpenid($openid);
        $entry->setMobile('');
        $entry->setCredit(0);
        $entry->setLastloginip('');
        $entry->setLastlogintime(new \DateTime());
        $entry->setLevel(1);
        $entry->setParentid(0);
        $entry->setProfileid(0);
        $entry->setRegtime(new \DateTime());
        $entry->setFormid('');
        $entry->setEnable(true);
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();
        $uid = $entry->getUid();

        if ($uid > 0) {
            $res = $this->updateUserProfile($uid, $profileArray);
            if (!$res) {
                return 0;
            }
        }

        return $uid;
    }

    public function getMemberByUid($uid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Member')
            ->find($uid);
    }

    /**
     * 用户是否存在
     * @param $openid
     * @return bool
     */
    public function isUserExistByOpenId($openid)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:Member')
            ->findOneBy([
                'openid' => $openid
            ]);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通过OPENID获取用户
     * @param $openid
     * @return object|null
     */
    public function findByOpenid($openid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Member')
            ->findOneBy([
                'openid' => $openid
            ]);
    }

    /**
     * 获取用户资料
     * @param $uid
     * @return Member|object|null
     */
    public function findByUid($uid)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:Member')
            ->findMemberByUid($uid);
        if ($res) {
            $res['birthday'] = date('Y-m-d H:i:s', $res['birthday']->getTimestamp());
            $res['lastlogintime'] = date('Y-m-d H:i:s', $res['lastlogintime']->getTimestamp());
            $res['regtime'] = date('Y-m-d H:i:s', $res['regtime']->getTimestamp());
        }

        return $res;
    }

    /**
     * 更新用户profile
     * @param $uid
     * @param array $profileArray = { avatarUrl, city, country, gender, language, nickName, province }
     *
     * @return bool
     */
    public function updateUserProfile($uid, $profileArray = [])
    {
        $memberEntry = $this->getDoctrine()->getRepository('AppBundle:Member')
            ->find($uid);
        if (!$memberEntry) {
            return false;
        }

        if ($memberEntry->getProfileid() > 0) {
            return true;
        }

        $entry = new MemberProfile();
        $entry->setNickname($profileArray['nickName']);
        $entry->setAvatar($profileArray['avatarUrl']);
        $entry->setGender($profileArray['gender']);
        $entry->setBirthday(new \DateTime());
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();

        $profileId = $entry->getId();
        if ($profileId > 0) {
            $memberEntry->setProfileid($profileId);
            $this->getDoctrine()->getManager()->flush();
            return true;
        }

        return false;
    }
}