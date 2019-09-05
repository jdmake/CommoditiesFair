<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/25
// +----------------------------------------------------------------------


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * 用户仓库类
 * Class MemberRepository
 * @package AppBundle\Repository
 */
class MemberRepository extends EntityRepository
{
    public function findMemberByUid($uid)
    {
        $query = $this->createQueryBuilder('a');
        $res = $query->select('a.uid,a.openid,a.mobile,a.level,a.parentid,a.credit,a.lastloginip, a.lastlogintime,a.regtime,a.enable,b.avatar,b.birthday,b.gender,b.nickname')
            ->innerJoin('AppBundle:MemberProfile', 'b', 'WITH', 'a.profileid=b.id')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()->getResult();
        if($res) {
            $res = $res[0];
        }
        return $res;
    }
}