<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Custom\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\TextType;

class AvatarType extends TextType
{
    public function getBlockPrefix()
    {
        return 'avatar';
    }
}