<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/26
// +----------------------------------------------------------------------


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BoothOrderRepository extends EntityRepository
{

    /**
     * 获取订单分页数据
     * @param $uid
     * @param $status
     * @param $page
     * @param $size
     * @return mixed
     */
    public function findPageListBy($uid, $status, $page, $size)
    {

        $query = $this->createQueryBuilder('a');
        $query->select('a.id,a.uid,a.orderNo,a.total,a.remarks,a.orderStatus,b.boothTitle,b.boothNumber,b.boothPrice,b.boothSize,b.boothStarttime,b.boothEndtime,a.createAt')
            ->innerJoin('AppBundle:BoothOrderDetail', 'b', 'WITH', 'a.orderNo=b.orderNo')
            ->where('a.uid=:uid')->setParameter('uid', $uid)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        $query->andWhere('a.orderStatus=:status')
            ->setParameter('status', $status);

        $list = $query->getQuery()->getResult();

        $total = $this->getTotal($query);

        return [
            'list' => $list,
            'total' => $total,
            'page_size' => intval(ceil($total / $size))
        ];
    }


    protected function getTotal($query)
    {
        $query->setFirstResult(null)->setMaxResults(null);

        if ($query instanceof QueryBuilder) {
            $res = $query->getQuery()->getResult();
        } else {
            $res = $query->getResult();
        }

        return count($res);
    }

}