<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/6
// +----------------------------------------------------------------------


namespace AppBundle\Custom\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class ArraySize extends Constraint
{
    public $message = "{{ name }}不能为空";
}