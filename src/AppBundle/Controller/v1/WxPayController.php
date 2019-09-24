<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/12
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;

use AppBundle\Controller\Common\CommonController;
use AppBundle\Entity\BoothBuyRecord;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/v1/wxpay")
 */
class WxPayController extends CommonController
{
    /**
     * 统一下单
     * @Route("/uniformOrder")
     */
    public function uniformOrderAction()
    {
        $order_no = $this->input('order_no');
        $openid = $this->getUserSession('openid');

        // 获取订单信息
        $order_service = $this->get('order_service');
        $order_entry = $order_service->findByOrderNo($order_no);
        if($order_entry == null) {
            return $this->jsonError(1, '订单不存在，或已被删除');
        }

        $money = $order_entry->getTotal();
        $wxpay_service = $this->get('wxpay_service');
        $res = $wxpay_service->uniformOrder($openid, $money, $order_no, $order_no);

        if (isset($res['err_code']) && $res['err_code']) {
            $order_entry->setOrderStatus(2);
            $this->getDoctrine()->getManager()->flush();
            return $this->jsonError(1, '订单已失效');
        }

        return $this->jsonSuccess('统一下单', $res);
    }

    /**
     * @Route("/notify_url")
     */
    public function notifyUrlAction()
    {
        $wxpay_service = $this->get('wxpay_service');
        $wxpay_service->notifyUrlCallBack(function ($data) {
            $out_trade_no = $data['out_trade_no'];
            $total_fee = $data['total_fee'] / 100;
            // 获取订单信息
            $order_service = $this->get('order_service');
            $order_entry = $order_service->findByOrderNo($out_trade_no);
            if($order_entry->getTotal() == $total_fee) {
                // 改变订单状态
                $order_entry->setOrderStatus(1);
                $order_entry->setPayTime(new \DateTime());
                $this->getDoctrine()->getManager()->flush();
                // 添加展位记录
                $order_detail_entry = $order_service->getBoothOrderDetail($order_entry->getOrderNo());
                $res = $this->getDoctrine()->getManager()->getRepository('AppBundle:BoothBuyRecord')
                    ->findOneBy([
                        'boothId' => $order_detail_entry->getBoothId()
                    ]);
                if(!$res) {
                    if($order_detail_entry) {
                        $boothRecordEntry = new BoothBuyRecord();
                        $boothRecordEntry->setUid($order_entry->getUid());
                        $boothRecordEntry->setBoothId($order_detail_entry->getBoothId());
                        $boothRecordEntry->setTotal($order_entry->getTotal());
                        $boothRecordEntry->setVerificationCode(md5(time()));
                        $boothRecordEntry->setCreateAt(new \DateTime());
                        $boothRecordEntry->setIsuse(false);
                        $this->getDoctrine()->getManager()->persist($boothRecordEntry);
                        $this->getDoctrine()->getManager()->flush();
                    }
                }

                return new Response('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA]></return_msg></xml>');
            }else {
                return new Response('<xml><return_code><![CDATA[ERROR]]></return_code><return_msg><![CDATA]></return_msg></xml>');
            }
        });
    }
}