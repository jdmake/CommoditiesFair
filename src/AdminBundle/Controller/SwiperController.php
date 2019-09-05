<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkSwiper;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/swiper")
 */
class SwiperController extends AbsController
{
    /**
     * @Route("/index", name="admin_swiper_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('admin_swiper_service')->getPageList('AppBundle:PioneerparkSwiper', $page, 15,
            function (QueryBuilder $query) use ($search) {
                $query->orderBy('a.swiperSort', 'asc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setDefaultPk('swiperId')
            ->setPageTitle("轮播列表")
            ->addTopButton(new LinkButton([
                'title' => ' + 添加轮播图',
                'url' => '/admin/swiper/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('swiperType', '类型', [
                'type' => new Enable([
                    1 => ['title' => '首页顶部轮播位']
                ])
            ])
            ->add('swiperSort', '排序')
            ->add('swiperPicture', '图片', [
                'type' => new Image()
            ])
            ->add('swiperPath', '小程序path')
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/swiper/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/swiper/delete?id={%id%}',
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
     * @Route("/edit", name="admin_swiper_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkSwiper $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkSwiper', $id);

        $form = $this->createFormBuilder($entry)
            ->add('swiperType', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => '类型', 'choices'=> [
                    '首页顶部轮播图' => 1
                ], 'constraints' => [
                    new NotBlank(['message' => '类型不能为空'])
                ]
            ])
            ->add('swiperPath', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '小程序path'
            ])
            ->add('swiperSort', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '排序', 'constraints' => [
                    new NotBlank(['message' => '排序不能为空'])
                ]
            ])
            ->add('swiperPicture', 'AdminBundle\Custom\Form\Type\AvatarType', [
                'label' => '轮播图上传', 'constraints' => [
                    new NotBlank(['message' => '轮播图不能为空'])
                ]
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            if($id > 0) {
                $entry->setSwiperPicture($data['swiperPicture']);
                $entry->setSwiperType($data['swiperType']);
                $entry->setSwiperPath($data['swiperPath']);
                $entry->setSwiperSort($data['swiperSort']);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            }else {
                $entry = new PioneerparkSwiper();
                $entry->setSwiperPicture($data['swiperPicture']);
                $entry->setSwiperType($data['swiperType']);
                $entry->setSwiperPath($data['swiperPath']);
                $entry->setSwiperSort($data['swiperSort']);
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
     * @Route("/delete", name="admin_swiper_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkSwiper $entry */
        $entry = $this->get('admin_swiper_service')->findById('AppBundle:PioneerparkSwiper', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/swiper/index');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/swiper/index');
        }
    }
}