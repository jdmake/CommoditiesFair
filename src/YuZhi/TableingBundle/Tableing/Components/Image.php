<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class Image implements TableComponentInterface
{
    private $options = [
        'width' => '32px',
        'height' => '32px',
    ];

    /**
     * 组件构造器
     * Image constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if ($options) {
            $this->options = $options;
        }
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($pk_value, $value)
    {
        if(!is_array($value)) {
            $value = explode(',', $value)[0];
        }else {
            $value = $value[0];
        }
        return "<a href='{$value}' target='_blank'><img style=\"width: {$this->options['width']};height: {$this->options['height']};\" src=\"{$value}\"  alt=\"\" /></a>";
    }
}