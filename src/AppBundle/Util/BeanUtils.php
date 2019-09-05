<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/8
// +----------------------------------------------------------------------


namespace AppBundle\Util;


use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

class BeanUtils
{
    /**
     * 拷贝对象属性到目标对象
     * @param $source
     * @param $target
     */
    static public function copyProperties($source, &$target)
    {
        if ($source instanceof SlidingPagination) {
            foreach ($source->getItems() as $item) {
                $ref = new \ReflectionClass($item);
                $properties = [];
                foreach ($ref->getProperties() as $property) {
                    $methodName = 'get' . ucfirst($property->getName());
                    $properties[str_replace('get', '', $methodName)] = $item->$methodName();
                }
                $target[] = $properties;
            }
        }
    }
}