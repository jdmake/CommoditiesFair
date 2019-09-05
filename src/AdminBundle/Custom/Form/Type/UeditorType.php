<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/30
// +----------------------------------------------------------------------


namespace AdminBundle\Custom\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UeditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function getBlockPrefix()
    {
        return 'ueditor';
    }
}