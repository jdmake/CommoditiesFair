<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/5/1
// +----------------------------------------------------------------------


namespace AppBundle\Controller\Filter;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class SessionFilter
{
    public static function doFilter(Request $request)
    {
        $allows = [
            '/v1/user/login',
            '/v1/user/register',
            '/v1/booth/getEnableBooth',
            '/v1/booth/getDetail',
            '/v1/booth/getAgreement',
            '/v1/member/getUserInfo',
            '/v1/boothOrder/getWaitPayBoothOrderCount',
        ];
        if(!in_array($request->getPathInfo(), $allows)) {
            if (!(new Session())->get('user')) {
                $res = new Response(json_encode([
                    'error' => 1000,
                    'msg' => '登录后继续操作'
                ]), 203);
                $res->send();
                exit();
            }
        }
    }
}