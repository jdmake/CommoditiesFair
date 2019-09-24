<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/19
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/customer")
 */
class CustomerController extends AbsController
{
    /**
     * @Route("/edit", name="admin_customer_edit")
     */
    public function editAction()
    {
        // 获取协议
        $settingEntry = $this->getDoctrine()->getRepository('AppBundle:Settings')
            ->findOneBy([
                'configKey' => 'customer_phone'
            ]);

        $form = $this->createFormBuilder([
            'customer_phone' => $settingEntry->getConfigValue()
        ])
            ->add('customer_phone', TextType::class, [
                'label' => '客服电话',
                'constraints' => [
                    new NotBlank(['message' => '客服电话不能为空'])
                ]
            ])
            ->add("submit", SubmitType::class,
                ['label' => '保存提交'])
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            $settingEntry->setConfigValue($data['customer_phone']);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('保存成功', 1, '/admin/customer/edit', false);
        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }
}