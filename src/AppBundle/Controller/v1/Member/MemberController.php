<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/23
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Member;

use AppBundle\Controller\Common\CommonController;
use AppBundle\Service\MemberService;
use AppBundle\Service\WxPhoneNumberService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * 会员控制器
 * @Route("/v1/member")
 * Class MemberController
 * @package AppBundle\Controller\v1\Member
 */
class MemberController extends CommonController
{
    /**
     * 获取用户资料
     * @Route("/getUserInfo")
     * @param MemberService $memberService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserInfoAction(MemberService $memberService)
    {
        $user = $memberService->findByUid($this->getUserSession('uid'));
        $user['isVerification'] = $this->getDoctrine()->getRepository('AppBundle:BoothVerificationUser')->findOneBy([
            'uid' => $user['uid']
        ]) ? true : false;
        return $this->jsonSuccess('获取用户资料', $user);
    }

    /**
     * 获取微信手机号码
     * @Route("/getUserPhoneNumber")
     */
    public function getUserPhoneNumberAction()
    {
        $encryptedData = $this->getJsonParameter('encryptedData');
        $iv = $this->getJsonParameter('iv');
        $sessionKey = $this->getJsonParameter('sessionKey');

        $wxPhoneNumberService = new WxPhoneNumberService($sessionKey);
        $wxPhoneNumberService->decryptData($encryptedData, $iv, $data);

        $phone = json_decode($data, true)['phoneNumber'];

        return $this->jsonSuccess('获取微信用户手机号码', [
            'phone' => $phone
        ]);
    }

    /**
     * 绑定手机号码
     * @Route("/bindMobile")
     * @param MemberService $memberService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bindMobileAction(MemberService $memberService)
    {
        $phone = $this->input('phone');
        $uid = $this->getUserSession('uid');
        $entry = $memberService->getMemberByUid($uid);

        $entry->setMobile($phone);
        $this->getDoctrine()->getManager()->flush();

        return $this->jsonSuccess('绑定手机号码', [
            'phone' => $phone
        ]);
    }
}