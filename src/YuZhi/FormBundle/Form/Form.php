<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/31
// +----------------------------------------------------------------------


namespace YuZhi\FormBundle\Form;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactory;


class Form
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /** @var \Symfony\Component\Form\FormBuilderInterface */
    private $formBuilder;

    /** @var \Symfony\Component\Form\Form */
    private $form;

    /** @var callable */
    private $pre_set_data;

    /** @var callable */
    private $post_set_data;

    /** @var callable */
    private $pre_submit;

    /** @var callable */
    private $submit;

    /** @var callable */
    private $post_submit;

    /**
     * Form constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

        /** @var FormFactory $formFactory */
        $formFactory = $this->get('form.factory');
        $this->formBuilder = $formFactory->createBuilder(FormType::class);
    }

    /**
     * 添加文本框组件
     * @param $field
     * @param $title
     * @param string $placeholder
     * @param array $constraints
     * @param string $extra_css
     * @param string $style
     * @return $this
     */
    public function addText($field, $title, $placeholder = '', array $constraints = [], $extra_css = '', $style = '')
    {
        return $this->_add('Symfony\Component\Form\Extension\Core\Type\TextType',
            $field, $title, $placeholder, $constraints, $extra_css, $style);
    }

    /**
     * 添加数字输入框组件
     * @param $field
     * @param $title
     * @param string $placeholder
     * @param array $constraints
     * @param string $extra_css
     * @param string $style
     * @return Form
     */
    public function addInteger($field, $title, $placeholder = '', array $constraints = [], $extra_css = '', $style = '')
    {
        return $this->_add('Symfony\Component\Form\Extension\Core\Type\IntegerType',
            $field, $title, $placeholder, $constraints, $extra_css, $style);
    }

    /**
     * 添加提交按钮
     * @param $field
     * @param $title
     * @return Form
     */
    public function addSubmit($field, $title)
    {
        $this->formBuilder->add(
            $field,
            'Symfony\Component\Form\Extension\Core\Type\SubmitType',
            [
                'label' => $title,
            ]
        );
        return $this;
    }

    /**
     * 添加组件
     * @param $type
     * @param $field
     * @param $title
     * @param string $placeholder
     * @param array $constraints
     * @param string $extra_css
     * @param string $style
     * @return $this
     */
    private function _add($type, $field, $title, $placeholder = '', array $constraints = [], $extra_css = '', $style = '')
    {
        $this->formBuilder->add(
            $field,
            $type,
            [
                'label' => $title,
                'constraints' => $constraints,
                'attr' => [
                    'placeholder' => $placeholder,
                    'class' => $extra_css,
                    'style' => $style
                ],
            ]
        );
        return $this;
    }

    /**
     * @param callable $pre_set_data
     * @return Form
     */
    public function setPreSetData($pre_set_data)
    {
        $this->pre_set_data = $pre_set_data;
        return $this;
    }

    /**
     * @param callable $post_set_data
     * @return Form
     */
    public function setPostSetData($post_set_data)
    {
        $this->post_set_data = $post_set_data;
        return $this;
    }

    /**
     * @param callable $pre_submit
     * @return Form
     */
    public function setPreSubmit($pre_submit)
    {
        $this->pre_submit = $pre_submit;
        return $this;
    }

    /**
     * @param callable $submit
     * @return Form
     */
    public function setSubmit($submit)
    {
        $this->submit = $submit;
        return $this;
    }

    /**
     * @param callable $post_submit
     * @return Form
     */
    public function setPostSubmit($post_submit)
    {
        $this->post_submit = $post_submit;
        return $this;
    }

    /**
     * 获取表单提交的数据
     * @return mixed
     */
    public function getFormData()
    {
        return $this->form->getData();
    }

    /**
     * 创建视图
     * @return \Symfony\Component\Form\FormView
     */
    public function createView($data)
    {
        if ($this->pre_set_data) {
            $this->formBuilder->addEventListener(FormEvents::PRE_SET_DATA, $this->pre_set_data);
        }
        if ($this->post_set_data) {
            $this->formBuilder->addEventListener(FormEvents::POST_SET_DATA, $this->post_set_data);
        }
        if ($this->pre_submit) {
            $this->formBuilder->addEventListener(FormEvents::PRE_SUBMIT, $this->pre_submit);
        }
        if ($this->submit) {
            $this->formBuilder->addEventListener(FormEvents::SUBMIT, $this->submit);
        }
        if ($this->post_submit) {
            $this->formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->post_submit);
        }
        $this->form = $this->formBuilder->getForm();
        $this->form->setData($data);

        return $this->form->createView();
    }

    /**
     * @param $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function handleRequest($request)
    {
        return $this->formBuilder->getForm()->handleRequest($request);
    }


    /**
     * 获取服务
     * @param $id
     * @return mixed
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }
}