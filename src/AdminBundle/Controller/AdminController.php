<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\Admin;
use AppBundle\Util\EncryptUtil;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\SwitchButton;

/**
 * @Route("/manager")
 */
class AdminController extends AbsController
{
    /**
     * @Route("/index", name="admin_manager_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('username', '');

        $pagination = $this->get('admin_service')->getPageList('AppBundle:Admin', $page, 15,
            function (QueryBuilder $query) use ($search) {
                if ($search != '') {
                    $query->where('a.username like :username')
                        ->setParameter('username', "%{$search}%");
                    $query->orderBy('a.createTime', 'desc');
                }
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("管理员列表")
            ->addTopSearch('username', '输入管理员账号进行搜索', '/admin/manager/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加管理员',
                'url' => '/admin/manager/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('username', '账号')
            ->add('status', '状态', [
                'type' => new SwitchButton('/admin/manager/switchStatus')
            ])
            ->add('createTime', '时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/manager/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/manager/delete?id={%id%}',
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
     * @Route("/switchStatus", name="admin_manager_switchstatus")
     */
    public function switchStatus()
    {
        $id = $this->request()->get('id');

        $this->get('admin_service')->switchStatus($id);

        return $this->json([
            'error' => 0,
            'msg' => '改变状态',
        ]);
    }

    /**
     * @Route("/delete", name="admin_manager_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id');
        if (!$this->get('admin_service')->delete('AppBundle:Admin', $id)) {
            return $this->error('操作失败', 3);
        }
        return $this->success('操作成功', 1, '/admin/manager/index');
    }

    /**
     * @Route("/edit", name="admin_manager_edit")
     */
    public function editAction()
    {
        $adminService = $this->get('admin_service');

        $id = $this->request()->get('id', 0);
        $adminEntry = $adminService->findById('AppBundle:Admin', $id);

        if ($adminEntry) {
            $old = $adminEntry->getPsw();
        }

        $form = $this->createFormBuilder($adminEntry)
            ->add("username", "Symfony\Component\Form\Extension\Core\Type\TextType",
                ['label' => '账号', 'constraints' => [
                    new NotBlank([
                        'message' => '账号不能为空',
                    ]),
                ]])
            ->add("psw", "Symfony\Component\Form\Extension\Core\Type\TextType",
                ['label' => '密码', 'constraints' => [
                    new NotBlank([
                        'message' => '密码不能为空',
                    ]),
                ]])
            ->add("submit", "Symfony\Component\Form\Extension\Core\Type\SubmitType",
                ['label' => '保存提交'])
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {

            $data = $form->getData();
            if (empty($id)) {
                if ($adminService->isExistAdmin($data['username'])) {
                    return $this->error("账号已存在，不可重复添加");
                }
                $admin = new Admin();
                $admin->setUsername($data['username']);
                $admin->setPsw(EncryptUtil::enPassword($data['psw']));
                $admin->setCreateTime(new \DateTime());
                $admin->setRoleId(0);
                $admin->setStatus(true);
                $adminService->create($admin);
                if ($admin->getId()) {
                    return $this->success('添加成功');
                }
            } else {
                $adminEntry->setUsername($data['username']);
                if ($data['psw'] != $old) {
                    $adminEntry->setPsw(EncryptUtil::enPassword($data['psw']));
                }
                $this->getDoctrine()->getManager()->flush();
                return $this->success('编辑成功');
            }

        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }
}