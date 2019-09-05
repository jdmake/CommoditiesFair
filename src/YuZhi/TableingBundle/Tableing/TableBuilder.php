<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/22
// +----------------------------------------------------------------------

namespace YuZhi\TableingBundle\Tableing;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TableBuilder
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 创建表格对象实例
     * @return Table
     */
    public function createTable($dataMaping = []) {

        if($dataMaping instanceof SlidingPagination) {
            $items = [];
            foreach ($dataMaping as $item) {
                $items[] = $item;
            }
            return new Table($items);
        }
        return new Table($dataMaping);
    }

}