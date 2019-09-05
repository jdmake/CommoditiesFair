<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class FormatNumber implements TableComponentInterface
{
    protected $len = 2;
    protected $suffix = '元';

    /**
     * FormatNumber constructor.
     * @param string $suffix
     */
    public function __construct($len = 2, $suffix = '')
    {
        if(!empty($suffix)) {
            $this->suffix = $suffix;
        }
        $this->len = $len;
    }


    public function render($pk_value, $value)
    {
        return number_format($value, $this->len) . $this->suffix;
    }
}