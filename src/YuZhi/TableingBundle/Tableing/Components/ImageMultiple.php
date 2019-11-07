<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/29
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class ImageMultiple implements TableComponentInterface
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
        $arrs = explode(',', $value);
        $output = '';
        foreach ($arrs as $arr) {
            $output .= "<a style='padding-right: 5px' href='{$arr}' target='_blank'><img style=\"width: {$this->options['width']};height: {$this->options['height']};\" src=\"{$arr}\"  alt=\"\" /></a>";
        }

        return $output;
    }
}