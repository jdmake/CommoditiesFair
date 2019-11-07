<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Service;


use AdminBundle\Traits\EntryPaginatorTrait;

class MemberService extends AbsService
{
    use EntryPaginatorTrait;

    public function getMember($uid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Member')
            ->find($uid);
    }

    /**
     * 获取用户详情
     */
    public function getUserDetail($uid)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a.mobile,a.level,a.credit,a.enable,b.avatar,b.gender,b.nickname')
            ->from('AppBundle:Member', 'a')
            ->innerJoin('AppBundle:MemberProfile', 'b', 'WITH', 'a.profileid=b.id')
            ->where('a.uid=:uid')->setParameter('uid', $uid)
            ->setFirstResult(0)
            ->setMaxResults(1);
        $entry = $query->getQuery()->getResult();
        if($entry) {
            return $entry[0];
        }
        return [];
    }

    /**
     * 获取今日新增会员数量
     */
    public function getNewMembersToday() {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();

        $start = date('Y-m-d', (new \DateTime())->getTimestamp());

        $query->select('count (a) as total')
            ->from('AppBundle:Member', 'a')
            ->where('a.regtime >= :regtime')
            ->setParameter('regtime', $start);

        $res = $query->getQuery()->getResult()[0]['total'];
        return (int) $res;
    }

    /**
     * 获取会员总数
     */
    public function getMemberCount() {
        $em = $this->getEm();
        $query = $em->createQuery("
        SELECT count(a) as total from AppBundle:Member a
        "
        );
        $res = $query->getResult()[0]['total'];
        return (int) $res;
    }

    /**
     * 改变状态
     * @param $uid
     * @return bool
     */
    public function switchStatus($uid)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:Member')
            ->find($uid);
        if(!$entry) {
            return false;
        }

        $entry->setEnable(!$entry->getEnable());
        $this->getEm()->flush($entry);
        return true;
    }
}