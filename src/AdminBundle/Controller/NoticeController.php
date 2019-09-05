<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkNotice;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\Remark;

/**
 * @Route("/notice")
 */
class NoticeController extends AbsController
{
    /**
     * @Route("/index", name="admin_notice_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('admin_member_service')->getPageList('AppBundle:PioneerparkNotice', $page, 15,
            function (QueryBuilder $query) use ($search) {
                if ($search != '') {
                    $query->where('a.title like :title')
                        ->setParameter('title', "%{$search}%");
                    $query->orderBy('a.createAt', 'desc');
                }
                return $query;
            });


        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("公告列表")
            ->addTopSearch('search', '输入标题进行搜索', '/admin/notice/index')
            ->add('id', '编号')
            ->add('title', '标题')
            ->add('content', '内容', [
                'type' => new Remark(50)
            ])
            ->add('createAt', '创建时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addTopButton(new LinkButton([
                'title' => ' + 添加公告',
                'url' => '/admin/notice/edit',
                'popup' => true,
            ]))
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/notice/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/notice/delete?id={%id%}',
                    'confirm' => '真的要删除吗？',
                    'class' => 'btn btn-pink'
                ]),
            ])
            ->buildView();

        return $this->render('@Admin/Table/base.html.twig', [
            'tableView' => $tableView,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/edit", name="admin_notice_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkNotice $entry */
        $entry = $this->get('admin_notice_service')->findById('AppBundle:PioneerparkNotice', $id);
        if($id > 0) {
            $entry->setPicture(explode(',', $entry->getPicture()));
        }

        $form = $this->createFormBuilder($entry)
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '标题', 'constraints' => [
                    new NotBlank(['message' => '标题不能为空'])
                ]
            ])
            ->add('content', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'label' => '内容', 'attr'=> [
                    'rows' => 15
                ], 'constraints' => [
                    new NotBlank(['message' => '内容不能为空'])
                ]
            ])
            ->add('picture', 'AdminBundle\Custom\Form\Type\PictureUpload', [
                'label' => '上传图片'
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();


        if($form->handleRequest($this->request())->isSubmitted()) {
            $data = $this->request()->get('form');
            if($id > 0) {
                $entry->setTitle($data['title']);
                $entry->setContent($data['content']);
                $entry->setPicture($data['picture'] ? join(',', $data['picture']) : '');
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            }else {
                $entry = new PioneerparkNotice();
                $entry->setTitle($data['title']);
                $entry->setContent($data['content']);
                $entry->setPicture($data['picture'] ? join(',', $data['picture']) : '');
                $entry->setCreateAt(new \DateTime());
                $this->getDoctrine()->getManager()->persist($entry);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('添加成功', 1);
            }
        }

        return $this->render('@Admin/form/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_notice_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkNotice $entry */
        $entry = $this->get('admin_notice_service')->findById('AppBundle:PioneerparkNotice', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/notice/index');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/notice/index');
        }
    }
}