<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/2
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Transaction;


use AdminBundle\Controller\Common\AbsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\EditCells;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\FormatNumber;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/transaction/boothorder")
 */
class BoothOrderController extends AbsController
{
    /**
     * @Route("/", name="admin_transaction_boothorder")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $status = $this->request()->get('status', 0);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('order_service')->findBoothOrderPageList($search, $status, $page, 15);

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("展位订单")
            ->addFilter('status', '待支付', [
                1 => '已支付',
                2 => '已取消',
                3 => '已删除',
            ])
            ->addTopSearch('search', '输入订单号进行搜索', '/admin/transaction/boothorder/')
            ->add('id', '编号')
            ->add('avatar', '头像', [
                'type' => new Image()
            ])
            ->add('nickname', '会员')
            ->add('orderNo', '订单号码')
            ->add('total', '订单金额', [
                'type' => new EditCells('/admin/transaction/boothorder/editTotal', [
                    'filter' => [
                        new FormatNumber(2)
                    ]
                ])
            ])
            ->add('boothTitle', '展位名称')
            ->add('boothNumber', '展位编号')
            ->add('boothSize', '展位面积/m²')
            ->add('remarks', '联系方式')
            ->add('orderStatus', '状态', [
                'type' => new Enable([
                    0 => ['title' => '待支付', 'class' => 'badge badge-default'],
                    1 => ['title' => '已支付', 'class' => 'badge badge-success'],
                    2 => ['title' => '已取消', 'class' => 'badge badge-orange'],
                    3 => ['title' => '已删除', 'class' => 'badge badge-pink'],
                ])
            ])
            ->add('createAt', '下单时间', [
                'type' => new DateTime('Y-m-d H:i')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '改变状态',
                    'url' => '/admin/transaction/boothorder/changeOrderStatus?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '清除',
                    'url' => '/admin/transaction/boothorder/delete?id={%id%}',
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
     * @Route("/editTotal", name="admin_transaction_boothorder_editTotal")
     */
    public function editTotalAction()
    {
        $id = $this->request()->get('id');
        $value = $this->request()->get('value');

        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothOrder')
            ->findOneBy([
                'id' => $id
            ]);
        if(!$entry) {
            return $this->jsonError(1, '数据不存在或已被删除');
        }

        $entry->setTotal($value);
        $this->getManager()->flush($entry);

        return $this->jsonSuccess('修改成功');
    }

    /**
     * @Route("/changeOrderStatus", name="admin_transaction_boothorder_changeOrderStatus")
     */
    public function changeOrderStatusAction()
    {
        $id = $this->request()->get('id');
        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothOrder')
            ->find($id);
        if(!$entry) {
            return $this->jsonError(1, '数据不存在或已被删除');
        }

        $form = $this->createFormBuilder($entry)
            ->add('orderStatus', ChoiceType::class, [
                'label' => '订单状态', 'choices' => [
                    '待支付' => 0,
                    '已支付' => 1,
                    '已取消' => 2,
                    '已删除' => 3,
                ]
            ])
            ->add("submit", SubmitType::class,
                ['label' => '保存修改'])
            ->getForm();

        if($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            $entry->setOrderStatus($data['orderStatus']);
            $this->getManager()->flush($entry);

            return $this->success('修改成功', 1);
        }

        return $this->render('@Admin/form/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_transaction_boothorder_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id');
        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothOrder')
            ->find($id);
        if(!$entry) {
            return $this->jsonError(1, '数据不存在或已被删除');
        }

        $this->getManager()->remove($entry);
        $this->getManager()->flush($entry);


        return $this->success('删除成功', 1, '/admin/transaction/boothorder/');
    }
}