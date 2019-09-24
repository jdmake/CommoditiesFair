<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/19
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Booth;


use AdminBundle\Controller\Common\AbsController;
use AdminBundle\Custom\Form\Type\UeditorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/agreement")
 */
class AgreementController extends AbsController
{
    /**
     * @Route("/edit", name="admin_agreement_edit")
     */
    public function editAction()
    {
        // 获取协议
        $settingEntry = $this->getDoctrine()->getRepository('AppBundle:Settings')
            ->findOneBy([
                'configKey' => 'agreement'
            ]);

        $form = $this->createFormBuilder([
            'agreement' => $settingEntry->getConfigValue()
        ])
            ->add('agreement', UeditorType::class, [
                'label' => '协议内容',
                'constraints' => [
                    new NotBlank(['message' => '协议内容不能为空'])
                ]
            ])
            ->add("submit", SubmitType::class,
                ['label' => '保存提交'])
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            $settingEntry->setConfigValue($data['agreement']);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('保存成功', 1, '/admin/agreement/edit', false);
        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }
}