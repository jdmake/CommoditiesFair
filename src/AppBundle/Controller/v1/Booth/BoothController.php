<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/24
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Booth;


use AppBundle\Controller\Common\CommonController;
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
        $booths = $boothService->getBoothMatrix();

        return $this->jsonSuccess('获取可用展位', [
           'booths' => $booths
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

        if(empty($contacts)) {
            return $this->jsonError(1, '联系人不能为空');
        }
        if(empty($mobile)) {
            return $this->jsonError(1, '手机号码不能为空');
        }

        if($bid <= 0) {
            return $this->jsonError(1, '展位不存在，或已被删除');
        }

        $order_no = $orderService->createOrder($uid, $bid, $contacts, $mobile);
        if($order_no === false) {
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
}