<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/23
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Security;

use AppBundle\Controller\Common\CommonController;
use AppBundle\Service\WxBaseService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/user")
 * Class UserController
 * @package AppBundle\Controller\v1\Security
 */
class UserController extends CommonController
{
    /**
     * @Route("/login")
     * @param WxBaseService $wxBaseService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loginAction(WxBaseService $wxBaseService)
    {
        $code = $this->input('code');

        // 获取 OPENID
        $res = $wxBaseService->code2Session($code);

        if (!$res) {
            return $this->jsonError(1, $wxBaseService->getError());
        }

        if (!isset($res['openid'])) {
            return $this->jsonError(1, '获取openid失败');
        }

        // 获取用户数据，保存到session中 ManagerRegistry
        $userEntry = $this->repository('AppBundle:Member')->findOneBy([
            'openid' => $res['openid']
        ]);
        if ($userEntry) {
            // 保存用户到 session 中
            $this->get('session')->set('user', $userEntry);
        }

        return $this->jsonSuccess('会员登录', $res);
    }

    /**
     * @Route("/register")
     */
    public function registerAction()
    {
        $openid = $this->input('openid');
        $avatarUrl = $this->input('avatarUrl');
        $city = $this->input('city');
        $country = $this->input('country');
        $gender = $this->input('gender');
        $language = $this->input('language');
        $nickName = $this->input('nickName');
        $province = $this->input('province');

        if (empty($openid)) {
            return $this->jsonError(1, 'openid 不能为空');
        }

        $member_service = $this->get('member_service');
        $uid = $member_service->createUserOnly($openid, [
            'avatarUrl' => $avatarUrl,
            'city' => $city,
            'country' => $country,
            'gender' => $gender,
            'language' => $language,
            'nickName' => $nickName,
            'province' => $province,
        ]);

        if ($uid <= 0) {
            return $this->jsonError(1, '创建用户信息失败');
        }

        $userEntry = $member_service->findByUid($uid);
        if ($userEntry) {
            // 保存用户到 session 中
            $this->get('session')->set('user', $userEntry);
        }

        return $this->jsonSuccess('创建用户', [
            'uid' => $uid
        ]);
    }
}