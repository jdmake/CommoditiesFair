<?php

namespace AdminBundle\Controller;

use AdminBundle\Controller\Common\AbsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/")
 */
class DefaultController extends AbsController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function indexAction()
    {
        return $this->render('@Admin/Default/index.html.twig');
    }

    /**
     * @Route("/report", name="admin_report")
     */
    public function reportAction()
    {
        $memberService = $this->get('admin_member_service');

        // 今日新增会员
        $newMembersToday = $memberService->getNewMembersToday();

        // 会员总数
        $totalMembership = $memberService->getMemberCount();

        return $this->render('@Admin/Default/report.html.twig', [
            'newMembersToday' => $newMembersToday,
            'totalMembership' => $totalMembership,
        ]);
    }
}
