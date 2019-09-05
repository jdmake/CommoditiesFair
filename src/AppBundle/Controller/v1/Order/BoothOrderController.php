<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/26
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Order;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/boothOrder")
 * Class BoothOrderController
 * @package AppBundle\Controller\v1\Order
 */
class BoothOrderController extends CommonController
{
    /**
     * 获取订单分页列表
     * @Route("/getOrderList")
     * @param OrderService $orderService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getOrderListAction(OrderService $orderService)
    {
        $page = $this->input('page', 1);
        $status = $this->input('status', 0);
        $uid = $this->getUserSession('uid');

        $order = $orderService->getBoothOrderPageList($uid, $status, $page, 10);

        return $this->jsonSuccess('获取订单分页列表', $order);
    }

    /**
     * 取消订单
     * @Route("/cancelBoothOrder")
     * @param OrderService $orderService
     * @return bool
     */
    public function cancelBoothOrderAction(OrderService $orderService)
    {
        $orderNo = $this->input('order_no');
        $uid = $this->getUserSession('uid');

        $res = $orderService->setBoothStatus($uid, $orderNo, 2);
        if (!$res) {
            return $this->jsonError(1, $orderService->getError());
        }

        return $this->jsonSuccess('取消订单');
    }

    /**
     * 取消订单
     * @Route("/deleteBoothOrder")
     * @param OrderService $orderService
     * @return bool
     */
    public function deleteBoothOrderAction(OrderService $orderService)
    {
        $orderNo = $this->input('order_no');
        $uid = $this->getUserSession('uid');

        $res = $orderService->setBoothStatus($uid, $orderNo, 3);
        if (!$res) {
            return $this->jsonError(1, $orderService->getError());
        }

        return $this->jsonSuccess('删除订单');
    }

    /**
     * 获取代付款的展位订单数量
     * @Route("/getWaitPayBoothOrderCount")
     * @param OrderService $orderService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getWaitPayBoothOrderCountAction(OrderService $orderService)
    {
        $uid = $this->getUserSession('uid');
        $count = $orderService->getWaitPayBoothOrderCount($uid);

        return $this->jsonSuccess('获取代付款的展位订单数量', [
            'count' => $count
        ]);
    }
}