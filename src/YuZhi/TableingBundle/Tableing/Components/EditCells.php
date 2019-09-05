<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/2
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class EditCells implements TableComponentInterface
{
    private $path;

    private $options = [
        'filter' => []
    ];

    /**
     * SwitchButton constructor.
     * @param $path
     * @param array $options
     */
    public function __construct($path, array $options = [])
    {
        $this->path = $path;
        $this->options = $options;
    }

    public function render($pk_value, $value)
    {
        $old_value = $value;
        foreach ($this->options['filter'] as $filter) {
            $value = $filter->render($pk_value, $value);
        }


        return <<<EOT
<a onclick="(function(e) {
    var url = $(e).data('url');
    layer.prompt({title: '编辑', value: '$old_value', formType: 3}, function(value, index) {
        $.ajax({
            url: url,
            type: 'post',
            data: {id: $pk_value, value: value},
            success: function (res) {
                if(res.error > 0) {
                    alert(res.msg);
                }else {
                    layer.close(index);
                    window.location.reload();
                }
            }
        });
    });
})(this)" style="color: #315cb9;" class="linecons-pencil" href="#" data-url="{$this->path}?id={$pk_value}"><u>{$value}</u></a>
EOT;
    }
}