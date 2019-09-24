<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/25
// +----------------------------------------------------------------------


namespace AppBundle\Service;

use AppBundle\Entity\Booth;
use AppBundle\Entity\BoothOrder;
use AppBundle\Entity\BoothOrderDetail;
use Knp\Component\Pager\Paginator;

/**
 * 订单服务
 * Class OrderService
 * @package AppBundle\Service
 */
class OrderService extends AbsService
{
    /**
     * 获取展位订单分页列表
     * @param $uid
     * @param $status
     * @param $page
     * @param $size
     * @return mixed
     */
    public function getBoothOrderPageList($uid, $status, $page, $size)
    {
        $pageListArray = $this->getDoctrine()->getRepository('AppBundle:BoothOrder')
            ->findPageListBy($uid, $status, $page, $size);

        foreach ($pageListArray['list'] as &$value) {
            list($contacts, $mobile) = explode('|', $value['remarks']);
            $value['contacts'] = $contacts;
            $value['mobile'] = $mobile;
            unset($value['remarks']);

            $value['boothStarttime'] = date('Y-m-d', $value['boothStarttime']->getTimestamp());
            $value['boothEndtime'] = date('Y-m-d', $value['boothEndtime']->getTimestamp());
            $value['createAt'] = date('Y-m-d H:i:s', $value['createAt']->getTimestamp());
        }

        return $pageListArray;
    }

    /**
     * 获取展位订单分页列表
     * @param $search
     * @param $status
     * @param $page
     * @param $size
     * @return mixed
     */
    public function findBoothOrderPageList($search, $status, $page, $size)
    {
        $query = $this->getEm()->createQueryBuilder();
        $query->select('a.id,a.uid,c.mobile,d.nickname,d.avatar,a.orderNo,a.total,a.remarks,a.orderStatus,b.boothTitle,b.boothNumber,b.boothPrice,b.boothSize,b.boothStarttime,b.boothEndtime,a.createAt')
            ->from('AppBundle:BoothOrder', 'a')
            ->innerJoin('AppBundle:BoothOrderDetail', 'b', 'WITH', 'a.orderNo=b.orderNo')
            ->innerJoin('AppBundle:Member', 'c', 'WITH', 'c.uid=a.uid')
            ->innerJoin('AppBundle:MemberProfile', 'd', 'WITH', 'c.profileid=d.id')
            ->andWhere('a.orderStatus=:status')
            ->setParameter('status', $status)
            ->orderBy('a.createAt', 'desc');

        if ($search != '') {
            $query->andWhere('a.orderNo like :orderNo')->setParameter('orderNo', "%{$search}%");
        }

        /** @var Paginator $knp_paginator */
        $knp_paginator = $this->get('knp_paginator');
        $pagination = $knp_paginator->paginate($query, $page, $size);

        return $pagination;
    }

    /**
     * 获取订单
     * @param $order_no
     * @return BoothOrder|object|null
     */
    public function findByOrderNo($order_no)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:BoothOrder')
            ->findOneBy([
                'orderNo' => $order_no,
            ]);
        return $res;
    }

    /**
     * 获取订单详情
     * @param $order_no
     * @return BoothOrderDetail|object|null
     */
    public function getBoothOrderDetail($order_no)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:BoothOrderDetail')
            ->findOneBy([
                'orderNo' => $order_no,
            ]);

        return $res;
    }

    public function createOrder($uid, $bid, $contacts, $mobile)
    {
        // 获取展位
        $booth_service = $this->get('booth_service');
        /** @var Booth $boothEntry */
        $boothEntry = $booth_service->findDetailById($bid);
        if (!$boothEntry) {
            $this->error = '展位不存在，或已被删除';
            return false;
        }


        // 创建订单
        $order_no = 'BH' . date('YmdHis') . $uid;
        if ($this->findByOrderNo($order_no)) {
            $this->error = '操作过于频繁';
            return false;
        }

        $this->getEm()->beginTransaction();

        $orderEntry = new BoothOrder();
        $orderEntry->setUid($uid);
        $orderEntry->setOrderNo($order_no);
        $orderEntry->setTotal($boothEntry->getPrice());
        $orderEntry->setIsinvoice(false);
        $orderEntry->setInvoiceid(0);
        $orderEntry->setPayChannel('微信支付');
        $orderEntry->setPayTime(new \DateTime('0000-00-00 00:00:00'));
        $orderEntry->setOutTradeNo('');
        $orderEntry->setCreateAt(new \DateTime());
        $orderEntry->setRemarks(sprintf("%s|%s", $contacts, $mobile));
        $orderEntry->setOrderStatus(0);
        $this->getEm()->persist($orderEntry);
        $this->getEm()->flush();

        if (!$orderEntry->getId()) {
            $this->getEm()->rollback();
            $this->error = '创建订单失败';
            return false;
        }

        // 创建订单详情
        $orderDetailEntry = new BoothOrderDetail();
        $orderDetailEntry->setOrderNo($order_no);
        $orderDetailEntry->setBoothId($boothEntry->getId());
        $orderDetailEntry->setBoothTitle($boothEntry->getTitle());
        $orderDetailEntry->setBoothNumber($boothEntry->getNumber());
        $orderDetailEntry->setBoothPrice($boothEntry->getPrice());
        $orderDetailEntry->setBoothSize($boothEntry->getSize());
        $orderDetailEntry->setBoothStarttime($boothEntry->getStarttime());
        $orderDetailEntry->setBoothEndtime($boothEntry->getEndtime());
        $this->getEm()->persist($orderDetailEntry);
        $this->getEm()->flush();

        if (!$orderDetailEntry->getId()) {
            $this->getEm()->rollback();
            $this->error = '创建订单详情失败';
            return false;
        }

        $this->getEm()->commit();

        return $order_no;
    }

    /**
     * 设置展位订单状态
     * @param $uid
     * @param $orderNo
     * @param $status
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setBoothStatus($uid, $orderNo, $status)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothOrder')
            ->findOneBy([
                'orderNo' => $orderNo,
                'uid' => $uid,
            ]);
        if (!$entry) {
            $this->error = '订单不存在，或已被删除';
            return false;
        }

        $entry->setOrderStatus($status);
        $this->getEm()->flush($entry);

        return true;
    }

    /**
     * 获取代付款的展位订单数量
     * @param $uid
     * @return int
     */
    public function getWaitPayBoothOrderCount($uid)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('count(a) as total')
            ->from('AppBundle:BoothOrder', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->andWhere('a.orderStatus=:status')
            ->setParameter('status', 0);
        $res = $query->getQuery()->getResult();
        return intval(@$res[0]['total']);
    }
}