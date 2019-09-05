<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/26
// +----------------------------------------------------------------------


namespace AppBundle\Controller;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/")
 */
class IndexController extends CommonController
{
    /**
     * @Route("/")
     */
    public function indexAction() {
        return $this->render('default.html.twig', [
        ]);
    }
}