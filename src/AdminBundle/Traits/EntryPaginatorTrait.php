<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Traits;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;

trait EntryPaginatorTrait
{
    /**
     * 获取对象分页列表
     * @param $from
     * @param $page
     * @param $size
     * @param callable $queryWhere
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPageList($from, $page, $size, callable $queryWhere = null)
    {
        /** @var EntityManager $em */
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from($from, 'a');

        if($queryWhere) {
            $query = $queryWhere($query);
        }

        /** @var Paginator $knp_paginator */
        $knp_paginator = $this->get('knp_paginator');
        $paginate = $knp_paginator->paginate($query, $page, $size);

        return $paginate;
    }
}