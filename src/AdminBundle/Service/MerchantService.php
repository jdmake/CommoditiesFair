<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/17
// +----------------------------------------------------------------------


namespace AdminBundle\Service;


use AdminBundle\Traits\EntryPaginatorTrait;
use Knp\Component\Pager\Paginator;

class MerchantService extends AbsService
{
    use EntryPaginatorTrait;

    /**
     * 获取预约记录，分页列表
     */
    public function getRecordPageList($status, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a.id,a.uid,a.date,a.time,a.status,a.createAt,b.title,b.price,b.number,c.mobile,d.avatar,d.nickname,e.name,e.contacts,e.tel')
            ->from('AppBundle:PioneerparkAppointmentRecord', 'a')
            ->innerJoin('AppBundle:PioneerparkMeetingroom', 'b', 'WITH', 'a.rid=b.id')
            ->innerJoin('AppBundle:PioneerparkMember', 'c', 'WITH', 'c.uid=a.uid')
            ->innerJoin('AppBundle:PioneerparkMemberProfile', 'd', 'WITH', 'c.profileid=d.id')
            ->innerJoin('AppBundle:PioneerparkMerchant', 'e', 'WITH', 'a.uid=e.uid')
            ->orderBy('a.date', 'asc');

        if ($status != '') {
            $query->andWhere('a.status=:status')->setParameter('status', $status);
        }

        /** @var Paginator $knp_paginator */
        $knp_paginator = $this->get('knp_paginator');
        $paginate = $knp_paginator->paginate($query, $page, $size);

        return $paginate;
    }

    /**
     * 获取绑定商户的用户UID
     * @param $mid
     * @return int
     */
    public function getUidByMid($mid)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMerchant')
            ->find($mid);
        if(!$entry) {
            return 0;
        }
        return $entry->getUid();
    }

    /**
     * 删除预约会议记录
     * @param $id
     * @return bool
     */
    public function removeMeetingAppointmentRecord($id)
    {
        // 删除订单
        $order = $this->get('order_service')->findMeetingByRid($id);
        $this->getEm()->remove($order);
        $this->getEm()->flush($order);
        // 删除预约记录
        $record = $this->getDoctrine()->getRepository('AppBundle:PioneerparkAppointmentRecord')
            ->find($id);
        if (!$record) {
            return false;
        }
        $this->getEm()->remove($record);
        $this->getEm()->flush($record);
        return true;
    }

    /**
     * 获取商户选择数据
     */
    public function getMerchantChoices()
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMerchant')
            ->findAll();
        $choices = [];
        foreach ($res as $re) {
            $choices[$re->getName()] = $re->getId();
        }

        return $choices;
    }
}