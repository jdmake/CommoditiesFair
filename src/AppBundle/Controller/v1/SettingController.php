<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/19
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/setting")
 */
class SettingController extends CommonController
{
    /**
     * @Route("/getCustomerPhone")
     */
    public function customerPhoneAction()
    {

        $settingEntry = $this->getDoctrine()->getRepository('AppBundle:Settings')
            ->findOneBy([
               'configKey' => 'customer_phone'
            ]);

        return $this->jsonSuccess('获取客服电话号码', [
            'customerPhone' => $settingEntry->getConfigValue()
        ]);
    }
}