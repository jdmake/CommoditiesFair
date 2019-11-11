<?php
/**
 * Created by PhpStorm.
 * User: pro3
 * Date: 2019/11/7
 * Time: 22:15
 */

namespace AppBundle\Controller\v1\Tradefair;


use AppBundle\Controller\Common\CommonController;
use AppBundle\Service\TradefairService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * 展会控制器
 * @Route("/v1/tradefair")
 * Class TradefairController
 * @package AppBundle\Controller\v1\tradefair
 */
class TradefairController extends CommonController
{
    /**
     * @Route("/list")
     */
    public function listAction(TradefairService $service)
    {
        $tradefairs = $service->getTradefairList();

        return $this->jsonSuccess('获取全部展会', [
            'tradefairs' => $tradefairs
        ]);
    }

    /**
     * @Route("/getTradefairDetail")
     */
    public function getTradefairDetailAction()
    {
        $id = $this->input('id');
        $tradefair = $this->getDoctrine()->getRepository('AppBundle:Tradefair')
            ->find($id);

        return $this->jsonSuccess('获取全部展会', [
            'detail' => $tradefair
        ]);
    }
}