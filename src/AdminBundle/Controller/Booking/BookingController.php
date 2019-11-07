<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/29
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Booking;


use AdminBundle\Controller\Common\AbsController;
use AdminBundle\Service\WxApiService;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\ImageMultiple;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;


/**
 * @Route("/booking")
 */
class BookingController extends AbsController
{
    /**
     * @Route("/", name="admin_booking")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $status = $this->request()->get('status', 0);

        $pagination = $this->get('admin_service')->getPageList('AppBundle:BoothBooking', $page, 15,
            function (QueryBuilder $query) use ($status) {
                $query->select('a.id, d.nickname, d.avatar, a.businessLicense, a.sfzLicense, a.sb, a.lsspLicense, a.wghzsLicense, a.scxkzLicense, a.xgzl, a.status, a.reviewMessage, a.createAt, b.title, b.number')
                    ->innerJoin('AppBundle:Booth', 'b', 'WITH', 'a.boothId=b.id')
                    ->innerJoin('AppBundle:Member', 'c', 'WITH', 'a.uid=c.uid')
                    ->innerJoin('AppBundle:MemberProfile', 'd', 'WITH', 'c.profileid=d.id')
                    ->where('a.status=:status')->setParameter('status', $status);
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("展位预订列表")
            ->addFilter('status', '', [
                0 => '待审核',
                1 => '已通过',
                2 => '未通过',
                3 => '已完成',
            ])
            ->add('id', '编号')
            ->add('avatar', '头像', [
                'type' => new Image()
            ])
            ->add('nickname', '昵称')
            ->add('number', '展位编号')
            ->add('businessLicense', '营业执照', [
                'type' => new Image()
            ])
            ->add('sfzLicense', '身份证复印件', [
                'type' => new Image()
            ])
            ->add('sb', '商标', [
                'type' => new Image()
            ])
            ->add('lsspLicense', '绿色食品', [
                'type' => new Image()
            ])
            ->add('wghzsLicense', '无公害', [
                'type' => new Image()
            ])
            ->add('scxkzLicense', '生产许可', [
                'type' => new Image()
            ])
            ->add('xgzl', '相关资料', [
                'type' => new ImageMultiple()
            ])
            ->add('status', '状态', [
            ])
            ->add('createAt', '提交时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '审核',
                    'url' => '/admin/booking/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/booking/delete?id={%id%}',
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
     * @Route("/edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id');

        $bookingEntry = $this->getDoctrine()->getRepository('AppBundle:BoothBooking')
            ->find($id);

        $form = $this->createFormBuilder($bookingEntry)
            ->add('status', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => '状态',
                'choices' => [
                    '待审核' => 0,
                    '已通过' => 1,
                    '未通过' => 2,
                ]
            ])
            ->add('reviewMessage', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'label' => '审核信息'
            ])
            ->add("submit", "Symfony\Component\Form\Extension\Core\Type\SubmitType",
                ['label' => '保存提交'])
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            $bookingEntry->setStatus($data['status']);
            $bookingEntry->setReviewMessage($data['reviewMessage']);
            $this->getDoctrine()->getManager()->flush();

            if ($data['status'] == 2) {
                // 发送审核不通过信息
                $wxapi = $this->get('admin_wxapi_service');
                $wxapi->sendApprovalMsg(
                    $bookingEntry->getUid(),
                    $bookingEntry->getFormid(),
                    'pages/booth/detail?id=' . $bookingEntry->getBoothId() . '&is_soldout=false',
                    '资质审核不通过',
                    $data['reviewMessage']
                );
            } else if ($data['status'] == 1) {
                // 发送审核通过信息
                $wxapi = $this->get('admin_wxapi_service');
                $wxapi->sendApprovalMsg(
                    $bookingEntry->getUid(),
                    $bookingEntry->getFormid(),
                    '/pages/agreement/agreement?bid=' . $bookingEntry->getBoothId(),
                    '资质审核已通过',
                    $data['reviewMessage']
                );
            }

            return $this->success('保存成功', 1);
        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }


    /**
     * 删除预订
     * @Route("/delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id');
        $bookingEntry = $this->getDoctrine()->getRepository('AppBundle:BoothBooking')
            ->find($id);
        if (!$bookingEntry) {
            return $this->error('数据不存在或已被删除', 3, 'ref', false);
        }

        $this->getDoctrine()->getManager()->remove($bookingEntry);
        $this->getDoctrine()->getManager()->flush();

        return $this->success('删除成功', 1, 'ref', false);
    }
}