<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/17
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class ConfirmAlert implements AbsButton, \ArrayAccess
{
    private $options = [
        'title' => '',
        'icon' => '',
        'url' => '',
        'class' => '',
        'confirm' => '',
        'target' => '',
    ];

    public function getName()
    {
        return 'ConfirmAlert';
    }

    /**
     * LinkButton constructor.
     * @param $title
     * @param $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }


    public function offsetExists($offset)
    {
        return isset($this->options[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->options[$offset];
    }


    public function offsetSet($offset, $value)
    {
    }


    public function offsetUnset($offset)
    {
    }
}