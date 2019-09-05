<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/22
// +----------------------------------------------------------------------


namespace AdminBundle\Custom\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectMemberType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'select_member';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'url' => '',
        ]);
        $resolver->setAllowedTypes('url', 'string');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url'] = $options['url'];
    }
}