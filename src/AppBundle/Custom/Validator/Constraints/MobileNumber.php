<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/5
// +----------------------------------------------------------------------


namespace AppBundle\Custom\Validator\Constraints;

/**
 * @Annotation
 */
use Symfony\Component\Validator\Constraint;

class MobileNumber extends Constraint
{
    public $message = '请输入正确的手机号码';
}