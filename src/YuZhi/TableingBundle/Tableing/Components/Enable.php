<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class Enable implements TableComponentInterface
{
    private $options = [];

    /**
     * Enable constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }


    public function render($pk_value, $value)
    {
        return '<span class="' . ($this->options[$value]['class'] ?: 'badge badge-default') . '">' . $this->options[$value]['title'] . '</span>';
    }
}