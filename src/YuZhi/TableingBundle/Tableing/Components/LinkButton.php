<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;

/**
 * 链接
 * Class LinkButton
 * @package YuZhi\TableingBundle\Tableing\Components
 */
class LinkButton implements AbsButton, \ArrayAccess
{
    private $options = [
        'title' => '',
        'icon' => '',
        'url' => '',
        'class' => '',
        'confirm' => '',
        'target' => '',
    ];

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