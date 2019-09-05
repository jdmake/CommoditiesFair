<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/15
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class Remark implements TableComponentInterface
{
    protected $size;

    /**
     * Remark constructor.
     * @param $size
     */
    public function __construct($size)
    {
        $this->size = $size;
    }


    public function render($pk_value, $value)
    {
        return mb_substr($value, 0, $this->size, 'utf-8'). '...';
    }
}