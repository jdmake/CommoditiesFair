<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/29
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Booth;


use AdminBundle\Controller\Common\AbsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * 展位控制器
 * @Route("/booth")
 */
class BoothController extends AbsController
{
    /**
     * @Route("/index", name="admin_booth_index")
     */
    public function indexAction()
    {
        return $this->render('@Admin/booth/index.html.twig', [

        ]);
    }

    /**
     * @Route("/getBooths", name="admin_booth_getBooths")
     */
    public function getBoothsAction()
    {
        $boothService = $this->get('booth_service');
        $booths = $boothService->getBoothMatrix();
        $row = 50;
        $col = 50;

        return $this->jsonSuccess('获取全部展位', [
            'row' => $row,
            'col' => $col,
            'booths' => $booths
        ]);
    }

    /**
     * @Route("/edit", name="admin_booth_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $row = $this->request()->get('row');
        $col = $this->request()->get('col');

        $category = $this->getDoctrine()->getRepository('AppBundle:BoothCategory')
            ->getCategoryChoices();

        $boothService = $this->get('booth_service');
        $booth_entry = $boothService->getBoothByRowCol($row, $col);
        if($booth_entry && !empty($booth_entry->getPicture())) {
            $booth_entry->setPicture(explode(',', $booth_entry->getPicture()));
        }

        $form = $this->createFormBuilder($booth_entry)
            ->add('row', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', [
                'data' => $row
            ])
            ->add('col', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', [
                'data' => $col
            ])
            ->add("category", "Symfony\Component\Form\Extension\Core\Type\ChoiceType", [
                'label' => '类型', 'choices' => $category
            ])
            ->add("title", "Symfony\Component\Form\Extension\Core\Type\TextType",
                ['label' => '展位名称	'])
            ->add("number", "Symfony\Component\Form\Extension\Core\Type\IntegerType",
                ['label' => '展位编号'])
            ->add("size", "Symfony\Component\Form\Extension\Core\Type\IntegerType",
                ['label' => '展位面积/m²'])
            ->add("price", "Symfony\Component\Form\Extension\Core\Type\NumberType",
                ['label' => '价格'])
            ->add('content', 'AdminBundle\Custom\Form\Type\UeditorType', [
                'label' => '详情'
            ])
            ->add("picture", "AdminBundle\Custom\Form\Type\PictureUpload", [
                'label' => '图片集'
            ])
            ->add("starttime", "Symfony\Component\Form\Extension\Core\Type\DateType", [
                'label' => '开始时间'
            ])
            ->add("endtime", "Symfony\Component\Form\Extension\Core\Type\DateType", [
                'label' => '结束时间'
            ])
            ->add("submit", "Symfony\Component\Form\Extension\Core\Type\SubmitType",
                ['label' => '保存提交'])
            ->getForm();


        if ($this->request()->getMethod() == 'POST') {
            $form = $this->request()->get('form');

            $errors = $this->validator($form, [
                'title' => new NotBlank(['message' => '名称不能为空']),
                'number' => new NotBlank(['message' => '编号不能为空']),
                'size' => new NotBlank(['message' => '面积不能为空']),
                'price' => new NotBlank(['message' => '价格不能为空']),
            ]);

            if (count($errors) > 0) {
                return $this->error(join("<br/>", $errors), 5, "/admin/booth/edit?row={$row}&col={$col}", false);
            }


            // 判断当前矩阵是否存在展位信息
            if(!$booth_entry) {
                $booth_id = $boothService->createBooth($form);
                if($booth_id <= 0) {
                    return $this->error('创建展位失败', 5, "/admin/booth/edit?row={$row}&col={$col}", false);
                }
            }else {
                $res = $boothService->updateBooth($row, $col, $form);
                if(!$res) {
                    return $this->error('编辑展位失败', 5, "/admin/booth/edit?row={$row}&col={$col}", false);
                }
            }

            return $this->success('操作成功', 1);
        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * 删除展位
     * @Route("/remove", name="admin_booth_remove")
     */
    public function removeAction()
    {
        $row = $this->request()->get('row');
        $col = $this->request()->get('col');

        $boothService = $this->get('booth_service');
        $booth_entry = $boothService->getBoothByRowCol($row, $col);
        if(!$booth_entry) {
            return $this->jsonError(1, '数据不存在，或已被删除');
        }

        // 展位是否已售出
        if($boothService->isSoldoutBooth($booth_entry->getId())) {
            return $this->jsonError(1, '展位已售出不可删除');
        }

        $this->getDoctrine()->getManager()->remove($booth_entry);
        $this->getDoctrine()->getManager()->flush();

        return $this->jsonSuccess('删除展位');
    }
}