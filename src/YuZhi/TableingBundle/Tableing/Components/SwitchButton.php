<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing\Components;


class SwitchButton implements TableComponentInterface
{
    private $path;

    /**
     * SwitchButton constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }


    public function render($pk_value, $value)
    {
        $checked = '';
        if($value) {
            $checked = 'checked';
        }
        return <<<EOT
<div class="form-block">
    <input onchange="(function() {
      $.ajax({
                url: '{$this->path}?id=' + {$pk_value},
                type: 'get',
                success: function (res) {
                    if (res.error > 1) {
                        alert(res.msg);
                    }
                }
            })
    })()" {$checked} type="checkbox" class="iswitch iswitch-secondary">
</div>
EOT;
    }
}