<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/24
// +----------------------------------------------------------------------


namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * 展位仓库类
 * Class BoothRepository
 * @package AppBundle\Repository
 */
class BoothRepository extends EntityRepository
{
    /**
     * 获取展位
     * @param $row
     * @param $col
     * @param $enable
     * @return array
     */
    public function getBooths($tradefair_id, $row, $col, $enable)
    {
        $query = $this->createQueryBuilder('a');
        $query->select('a.id, a.category, b.title as categoryName, a.row, a.col, a.title, a.number, a.size, a.price');
        $query->innerJoin('AppBundle:BoothCategory', 'b', 'WITH', 'a.category=b.id');
        $query->orderBy('a.id', 'desc');

        $query->andWhere('a.tradefair_id=:tradefair_id')->setParameter('tradefair_id', $tradefair_id);
        $query->andWhere('a.row=:row')->setParameter('row', $row);
        $query->andWhere('a.col=:col')->setParameter('col', $col);
        $query->andWhere('a.enable=:enable')->setParameter('enable', $enable);
        $query->setFirstResult(0);
        $query->setMaxResults(1);

        $res = $query->getQuery()->getArrayResult();
        return $res ? $res[0] : null;
    }

    /**
     * 获取最大ROW
     */
    public function getMaxRow($tradefair_id)
    {

        $query = $this->createQueryBuilder('a');
        $query->select('MAX(a.row) as _max')
            ->where('a.tradefair_id=:tradefair_id')
            ->setParameter('tradefair_id', $tradefair_id);
        $res = $query->getQuery()->getResult();
        return $res ? $res[0]['_max'] : 0;
    }

    /**
     * 获取最大Col
     */
    public function getMaxCol($tradefair_id)
    {
        $query = $this->createQueryBuilder('a');
        $query->select('MAX(a.col) as _max')
            ->where('a.tradefair_id=:tradefair_id')
            ->setParameter('tradefair_id', $tradefair_id);
        $res = $query->getQuery()->getResult();
        return $res ? $res[0]['_max'] : 0;
    }
}