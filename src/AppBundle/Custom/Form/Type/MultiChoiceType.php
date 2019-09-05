<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/5
// +----------------------------------------------------------------------


namespace AppBundle\Custom\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MultiChoiceType extends ChoiceType
{
    public function getBlockPrefix()
    {
        return 'multi_choice';
    }
}