<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/2
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Transaction;


use AdminBundle\Controller\Common\AbsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\FormatNumber;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/transaction/soldoutbooth")
 */
class SoldoutBoothController extends AbsController
{
    /**
     * @Route("/", name="admin_transaction_soldoutbooth")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('booth_service')->getSoldoutBoothPageList($search, $page, 15);

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("已售出展位")
            ->addTopSearch('search', '输入展位编号进行搜索', '/admin/transaction/soldoutbooth/')
            ->add('id', '编号')
            ->add('avatar', '会员头像', [
                'type' => new Image()
            ])
            ->add('nickname', '会员昵称')
            ->add('title', '展位名称')
            ->add('number', '展位编号')
            ->add('size', '面积')
            ->add('total', '支付金额', [
                'type' => new FormatNumber(2)
            ])
            ->add('createAt', '成交时间', [
                'type' => new DateTime('Y-m-d H:i')
            ])
            ->add('starttime', '开始时间', [
                'type' => new DateTime('Y-m-d')
            ])
            ->add('endtime', '结束时间', [
                'type' => new DateTime('Y-m-d')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/transaction/soldoutbooth/delete?id={%id%}',
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
     * @Route("/delete", name="admin_transaction_soldoutbooth_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id');
        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothBuyRecord')
            ->find($id);

        if(!$entry) {
            return $this->error('数据不存在或已被删除', 5, '/admin/transaction/soldoutbooth/', false);
        }

        $this->getManager()->remove($entry);
        $this->getManager()->flush($entry);

        return $this->success('操作成功', 1, '/admin/transaction/soldoutbooth/', false);
    }


}