<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace AdminBundle\Custom\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\TextType;


class PictureUpload extends TextType
{

    public function getBlockPrefix()
    {
        return 'picture_upload';
    }
}