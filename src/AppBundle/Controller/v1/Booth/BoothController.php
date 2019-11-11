<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/24
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Booth;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Entity\BoothBooking;
use AppBundle\Entity\SalesRecord;
use AppBundle\Service\BoothService;
use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * 展位控制器
 * @Route("/v1/booth")
 * Class BoothController
 * @package AppBundle\Controller\v1\Booth
 */
class BoothController extends CommonController
{
    /**
     * 获取可购买的展位
     * @Route("/getEnableBooth")
     * @param BoothService $boothService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getEnableBoothAction(BoothService $boothService)
    {
        $id = $this->input('id');

        $booths = $boothService->getBoothMatrix($id);

        return $this->jsonSuccess('获取可用展位', [
            'booths' => $booths
        ]);
    }


    /**
     * 获取我的展位选择列表
     * @Route("/getMyBoothChoice")
     */
    public function getMyBoothChoiceAction()
    {
        $booth_service = $this->get('booth_service');
        $objects = $booth_service->getMyBoothChoice($this->getUserSession('uid'));

        $choices = [];
        foreach ($objects as $object) {
            $choices[] = $object['title'];
        }

        return $this->jsonSuccess('获取我的展位选择列表', [
            'objects' => $objects,
            'choices' => $choices
        ]);
    }

    /**
     * 上传销售报告
     * @Route("/submitSales")
     */
    public function submitSalesAction()
    {
        $id = $this->input('id');
        $goods_name = $this->input('goods_name');
        $count = $this->input('count');
        $price = $this->input('price');

        if (empty($id)) {
            return $this->jsonError(1, '展位不能为空');
        }
        if (empty($goods_name)) {
            return $this->jsonError(1, '商品名称不能为空');
        }
        if (empty($count)) {
            return $this->jsonError(1, '售出数量不能为空');
        }
        if (empty($price)) {
            return $this->jsonError(1, '售出价格不能为空');
        }

        // 入库
        $salesRecord = new SalesRecord();
        $salesRecord->setUid($this->getUserSession('uid'));
        $salesRecord->setBoothId($id);
        $salesRecord->setGoodsName($goods_name);
        $salesRecord->setGoodsCount($count);
        $salesRecord->setGoodsTotal($price);
        $salesRecord->setReportTime(new \DateTime());
        $this->getDoctrine()->getManager()->persist($salesRecord);
        $this->getDoctrine()->getManager()->flush();

        return $this->jsonSuccess('上传销售报告', [
        ]);
    }

    /**
     * 获取销售上传记录
     * @Route("/getUpdateLog")
     */
    public function getUpdateLogAction()
    {
        $booth_service = $this->get('booth_service');
        $logs = $booth_service->getUpdateLog($this->getUserSession('uid'));
        foreach ($logs as &$log) {
            $log['reportTime'] = $log['reportTime']->format('Y-m-d H:i:s');
        }
        return $this->jsonSuccess('获取销售上传记录', [
            'logs' => $logs
        ]);
    }

    /**
     * 获取展位详情
     * @Route("/getDetail")
     * @param BoothService $boothService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDetailAction(BoothService $boothService)
    {
        $id = $this->input('id');

        $booth = $boothService->findDetailById($id);
        $booth = $boothService->toArray($booth);
        $category = $boothService->findCategoryByCid($booth['category']);
        $booth['categoryName'] = $category->getTitle();
        $booth['status'] = 0;
        $booth['starttime'] = explode(' ', $booth['starttime']['date'])[0];
        $booth['endtime'] = explode(' ', $booth['endtime']['date'])[0];
        return $this->jsonSuccess('获取展位详情', [
            'detail' => $booth
        ]);
    }

    /**
     * 展位购买协议
     * @Route("/getAgreement")
     * @param BoothService $boothService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAgreementAction(BoothService $boothService)
    {
        $agreement = $boothService->getBoothAgreement();

        return $this->jsonSuccess('展位购买协议', [
            'agreement' => $agreement
        ]);
    }

    /**
     * 创建展位购买订单
     * @Route("/createOrder")
     * @param OrderService $orderService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createOrderAction(OrderService $orderService)
    {
        $bid = $this->input('bid');
        $uid = $this->getUserSession('uid');
        $contacts = $this->input('contacts');
        $mobile = $this->input('mobile');

        if (empty($contacts)) {
            return $this->jsonError(1, '联系人不能为空');
        }
        if (empty($mobile)) {
            return $this->jsonError(1, '手机号码不能为空');
        }

        if ($bid <= 0) {
            return $this->jsonError(1, '展位不存在，或已被删除');
        }

        $order_no = $orderService->createOrder($uid, $bid, $contacts, $mobile);
        if ($order_no === false) {
            return $this->jsonError(1, $orderService->getError());
        }

        return $this->jsonSuccess('创建展位购买订单', [
            'order_no' => $order_no
        ]);
    }

    /**
     * 获取我的展位
     * @Route("/getMyBooth")
     * @param BoothService $boothService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getMyBoothAction(BoothService $boothService)
    {
        $page = $this->input('page', 1);
        $isuse = $this->input('isuse', 0);
        $uid = $this->getUserSession('uid');

        $res = $boothService->getMyBoothPageList($uid, $isuse, $page, 10);

        return $this->jsonSuccess('获取我的展位', $res);
    }

    /**
     * 获取展位核销二维码
     * @Route("/getQrCode")
     */
    public function getQrCodeAction()
    {
        $code = $this->input('code');
        $recordEntry = $this->getDoctrine()->getRepository('AppBundle:BoothBuyRecord')
            ->findOneBy([
                'verificationCode' => $code
            ]);
        if (!$recordEntry) {
            return $this->jsonError(1, '展位不存在，或已被删除');
        }

        $qrcode_service = $this->get('qrcode_service');
        $qrcodeBase64 = $qrcode_service->makeQrcode($recordEntry->getVerificationCode());

        return $this->jsonSuccess('获取展位核销二维码', [
            'qrcode' => str_replace("\r\n", '', $qrcodeBase64)
        ]);
    }

    /**
     * 获取展位记录
     * @Route("/getBoothRecord")
     */
    public function getBoothRecordAction()
    {
        $code = $this->input('code');
        $recordEntry = $this->getDoctrine()->getRepository('AppBundle:BoothBuyRecord')
            ->findOneBy([
                'verificationCode' => $code
            ]);
        if (!$recordEntry) {
            return $this->jsonError(1, '展位不存在，或已被删除');
        }

        $booth = $this->getDoctrine()->getRepository('AppBundle:Booth')
            ->find($recordEntry->getBoothId());
        $booth = $this->get('booth_service')->toArray($booth);
        $booth['category'] = $this->getDoctrine()->getRepository('AppBundle:BoothCategory')
            ->find($booth['category']);
        $booth['isuse'] = $recordEntry->getIsuse();

        return $this->jsonSuccess('获取展位记录', [
            'record' => $booth
        ]);
    }

    /**
     * 展位核销
     * @Route("/verificationBooth")
     */
    public function verificationBoothAction()
    {
        $code = $this->input('code');
        $recordEntry = $this->getDoctrine()->getRepository('AppBundle:BoothBuyRecord')
            ->findOneBy([
                'verificationCode' => $code
            ]);
        if (!$recordEntry) {
            return $this->jsonError(1, '展位不存在，或已被删除');
        }

        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothVerificationUser')->findOneBy([
            'uid' => $this->getUserSession('uid')
        ]);
        if (!$entry) {
            return $this->jsonError(1, '没有权限操作');
        }

        $recordEntry->setIsuse(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->jsonSuccess('展位核销成功', []);
    }

    /**
     * 提交资质审核
     * @Route("/submitQualification")
     */
    public function submitQualificationAction()
    {
        $form = $this->input('form');

        if (empty($form['business_license'])) {
            return $this->jsonError(1, '营业执照不能为空');
        }
        if (empty($form['sfz_license'])) {
            return $this->jsonError(1, '身份证不能为空');
        }

        $query = $this->manager()->createQueryBuilder();
        $res = $query->select('a')
            ->from('AppBundle:BoothBooking', 'a')
            ->andWhere('a.boothId=:boothId')->setParameter('boothId', $form['bid'])
            ->andWhere('a.status!=:status')->setParameter('status', 2)
            ->getQuery()->getResult();
        if ($res) {
            return $this->jsonError(1, '展位已被预订');
        }

        $bookingEntry = new BoothBooking();
        $bookingEntry->setUid($this->getUserSession('uid'));
        $bookingEntry->setFormid($form['formId']);
        $bookingEntry->setBoothId($form['bid']);
        $bookingEntry->setBusinessLicense($form['business_license']);
        $bookingEntry->setLsspLicense($form['lssp_license']);
        $bookingEntry->setSb($form['sb']);
        $bookingEntry->setScxkzLicense($form['scxkz_license']);
        $bookingEntry->setSfzLicense($form['sfz_license']);
        $bookingEntry->setWghzsLicense($form['wghzs_license']);
        $bookingEntry->setXgzl(isset($form['xgzl']) ? join(',', $form['xgzl']) : '');
        $bookingEntry->setStatus(0);
        $bookingEntry->setReviewMessage('');
        $bookingEntry->setCreateAt(new \DateTime());

        $this->getDoctrine()->getManager()->persist($bookingEntry);
        $this->getDoctrine()->getManager()->flush();

        return $this->jsonSuccess('资质提交成功，请等待管理员审核', $form);
    }

    /**
     * 获取我的展位预订列表
     * @Route("/getMyBooking")
     */
    public function getMyBookingAction()
    {
        $selected = $this->input('selected', 0);

        $query = $this->manager()->createQueryBuilder();
        $query->select('a.id, a.uid, a.boothId, a.reviewMessage, a.status, a.createAt, b.title, b.number, b.size, b.price, b.starttime, b.endtime')
            ->from('AppBundle:BoothBooking', 'a')
            ->innerJoin('AppBundle:Booth', 'b', 'WITH', 'a.boothId=b.id')
            ->where('a.uid=:uid')->setParameter('uid', $this->getUserSession('uid'))
            ->andWhere('a.status=:status')->setParameter('status', $selected)
            ->orderBy('a.createAt', 'desc');

        $list = $query->getQuery()->getResult();
        foreach ($list as &$item) {
            $item['createAt'] = $item['createAt']->format('Y-m-d H:i');
            $item['starttime'] = $item['starttime']->format('Y-m-d');
            $item['endtime'] = $item['endtime']->format('Y-m-d');
        }

        return $this->jsonSuccess('获取我的展位预订列表', [
            'list' => $list
        ]);
    }

}