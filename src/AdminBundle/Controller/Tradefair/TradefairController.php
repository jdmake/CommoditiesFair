<?php
/**
 * Created by PhpStorm.
 * User: pro3
 * Date: 2019/11/9
 * Time: 17:08
 */

namespace AdminBundle\Controller\Tradefair;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\Tradefair;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\SwitchButton;

/**
 * @Route("/tradefair")
 */
class TradefairController extends AbsController
{
    /**
     * @Route("/index", name="admin_tradefair_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('username', '');

        $pagination = $this->get('tradefair_service')->getPageList('AppBundle:Tradefair', $page, 15,
            function (QueryBuilder $query) use ($search) {
                if ($search != '') {
                    $query->where('a.title like :title')
                        ->setParameter('title', "%{$search}%");
                }
                $query->orderBy('a.id', 'desc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("展会列表")
            ->addTopSearch('title', '输入展会名称进行搜索', '/admin/tradefair/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加展会',
                'url' => '/admin/tradefair/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('title', '展会名称')
            ->add('starttime', '开始时间', [
                'type' => new DateTime('Y-m-d')
            ])
            ->add('endtime', '结束时间', [
                'type' => new DateTime('Y-m-d')
            ])
            ->add('isenable', '是否可用', [
                'type' => new SwitchButton('/admin/tradefair/changeEnable')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑展会',
                    'url' => '/admin/tradefair/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '编辑展位',
                    'url' => '/admin/booth/index?tid={%id%}',
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/tradefair/delete?id={%id%}',
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
     * @Route("/changeEnable", name="admin_tradefair_changeEnable")
     */
    public function changeEnableAction()
    {
        $id = $this->request()->get('id');

        $entry = $this->getDoctrine()->getRepository('AppBundle:Tradefair')
            ->find($id);

        $entry->setIsenable(!$entry->getIsenable());
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'error' => 0,
            'msg' => '改变可用状态'
        ]);
    }

    /**
     * @Route("/edit", name="admin_tradefair_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id');

        if ($id > 0) {
            $entry = $this->getDoctrine()
                ->getRepository('AppBundle:Tradefair')
                ->find($id);
        } else {
            $entry = new Tradefair();
        }

        $form = $this->createFormBuilder($entry)
            ->add("title", "Symfony\Component\Form\Extension\Core\Type\TextType", [
                'label' => '展会名称'])
            ->add("picture", "AdminBundle\Custom\Form\Type\AvatarType", [
                'label' => '展会图片'
            ])
            ->add("starttime", "Symfony\Component\Form\Extension\Core\Type\DateType", [
                'label' => '开始时间'
            ])
            ->add("endtime", "Symfony\Component\Form\Extension\Core\Type\DateType", [
                'label' => '结束时间'
            ])
            ->add("tradefairdesc", "AdminBundle\Custom\Form\Type\UeditorType", [
                'label' => '展会描述'
            ])
            ->add("isenable", "Symfony\Component\Form\Extension\Core\Type\CheckboxType", [
                'label' => '是否可用'
            ])
            ->add("submit", "Symfony\Component\Form\Extension\Core\Type\SubmitType",
                ['label' => '保存提交'])
            ->getForm();

        if ($this->request()->getMethod() == 'POST') {

            $form = $this->request()->get('form');

            $errors = $this->validator($form, [
                'title' => new NotBlank(['message' => '展会名称不能为空']),
                'picture' => new NotBlank(['message' => '展会图片不能为空']),
            ]);

            if (count($errors) > 0) {
                return $this->error(join("<br/>", $errors), 5, "/admin/tradefair/edit?id={$id}", false);
            }

            if ($id <= 0) {
                $entry = new Tradefair();
                $entry->setTitle($form['title']);
                $entry->setPicture($form['picture']);
                $entry->setStarttime(new \DateTime(sprintf('%s-%s-%s',
                    $form['starttime']['year'],
                    $form['starttime']['month'],
                    $form['starttime']['day']
                )));
                $entry->setEndtime(new \DateTime(sprintf('%s-%s-%s',
                    $form['endtime']['year'],
                    $form['endtime']['month'],
                    $form['endtime']['day']
                )));
                $entry->setTradefairdesc($form['tradefairdesc']);
                $entry->setIsenable($form['isenable']);
                $this->getDoctrine()->getManager()->persist($entry);
                $this->getDoctrine()->getManager()->flush();

                if (!$entry->getId()) {
                    return $this->error('编辑展会失败', 5, "/admin/tradefair/edit?id={$id}", false);
                }

            }else {
                $entry->setTitle($form['title']);
                $entry->setPicture($form['picture']);
                $entry->setStarttime(new \DateTime(sprintf('%s-%s-%s',
                    $form['starttime']['year'],
                    $form['starttime']['month'],
                    $form['starttime']['day']
                )));
                $entry->setEndtime(new \DateTime(sprintf('%s-%s-%s',
                    $form['endtime']['year'],
                    $form['endtime']['month'],
                    $form['endtime']['day']
                )));
                $entry->setTradefairdesc($form['tradefairdesc']);
                $entry->setIsenable($form['isenable']);
                $this->getDoctrine()->getManager()->flush();
            }

            return $this->success('操作成功', 1);
        }


        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_tradefair_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id');
        $entry = $this->getDoctrine()->getRepository('AppBundle:Tradefair')
            ->find($id);
        if(!$entry) {
            return $this->error('数据不存在或已被删除', 5, "/admin/tradefair/index", false);
        }

        $this->getDoctrine()->getManager()->remove($entry);
        $this->getDoctrine()->getManager()->flush();

        return $this->success('操作成功', 1, "/admin/tradefair/index", false);
    }
}