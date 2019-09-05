<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/24
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


use ClassesWithParents\D;

class DateTime implements TableComponentInterface
{
    private $format = '';

    /**
     * DateTime constructor.
     * @param string $format
     */
    public function __construct($format)
    {
        $this->format = $format;
    }


    /**
     * @param $pk_value
     * @param \DateTime $value
     * @return mixed
     */
    public function render($pk_value, $value)
    {
        if(is_object($value)) {
            if($value->getTimestamp() < 0) {
                return '--:--';
            }
            return date($this->format, $value->getTimestamp());
        }else {
            return date($this->format, $value);
        }
    }
}