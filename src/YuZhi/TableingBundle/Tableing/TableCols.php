<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing;


class TableCols
{
    private $children = [];
    private $pkValue;

    /**
     * TableCols constructor.
     * @param $pk
     */
    public function __construct($pkValue)
    {
        $this->pkValue = $pkValue;
    }


    public function add($cols) {
        $this->children = $cols;
        return $this;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getPkValue()
    {
        return $this->pkValue;
    }


}