<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/2
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Booth;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\BoothCategory;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/booth/category")
 */
class BoothCategoryController extends AbsController
{
    /**
     * @Route("/", name="admin_booth_category")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('booth_service')->getPageList('AppBundle:BoothCategory', $page, 15,
            function (QueryBuilder $query) use ($search) {
                if ($search != '') {
                    $query->where('a.title like :title')
                        ->setParameter('title', "%{$search}%");
                    $query->orderBy('a.sort', 'asc');
                }
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("分类管理")
            ->addTopSearch('search', '输入分类名称进行搜索', '/admin/booth/category/')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加分类',
                'url' => '/admin/booth/category/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('title', '分类名称')
            ->add('sort', '排序')
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/booth/category/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/booth/category/delete?id={%id%}',
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
     * @Route("/edit", name="admin_booth_category_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        $booth_service = $this->get('booth_service');
        $boothCateEntry = $booth_service->findCategoryByCid($id);

        $form = $this->createFormBuilder($boothCateEntry)
            ->add('title', TextType::class, [
                'label' => '分类名称',
                'attr' => [
                    'placeholder' => '请输入分类名称',
                ],
                'constraints' => [
                    new NotBlank(['message' => '分类名称不能为空'])
                ]
            ])
            ->add('sort', TextType::class, [
                'label' => '排序',
                'attr' => [
                    'placeholder' => '请输入分类排序号码',
                ],
                'constraints' => [
                    new NotBlank(['message' => '分类排序不能为空'])
                ]
            ])
            ->add("submit", SubmitType::class,
                ['label' => '保存提交'])
            ->getForm();


        if ($form->handleRequest($this->request())->isValid()) {

            $data = $form->getData();

            if ($boothCateEntry) {
                $boothCateEntry->setTitle($data['title']);
                $boothCateEntry->setSort($data['sort']);
                $boothCateEntry->setPid(0);
                $this->getManager()->flush($boothCateEntry);
            } else {
                $boothCateEntry = new BoothCategory();
                $boothCateEntry->setTitle($data['title']);
                $boothCateEntry->setSort($data['sort']);
                $boothCateEntry->setPid(0);
                $this->getManager()->persist($boothCateEntry);
                $this->getManager()->flush($boothCateEntry);

                if (!$boothCateEntry->getId()) {
                    return $this->error('添加失败', 5, '/admin/booth/category/');
                }
            }

            return $this->success('操作成功', 1);
        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_booth_category_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);

        $booth_service = $this->get('booth_service');
        $boothCateEntry = $booth_service->findCategoryByCid($id);

        if (!$boothCateEntry) {
            return $this->error('数据不存在或已被删除', 5, '/admin/booth/category/');
        }

        $this->getManager()->remove($boothCateEntry);
        $this->getManager()->flush($boothCateEntry);

        return $this->success('操作成功', 1, '/admin/booth/category/', false);
    }
}