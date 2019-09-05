<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/5/1
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Filter;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class SessionFilter
{
    public static function doFilter(Request $request)
    {
        $allows = [
            '/admin/user/login'
        ];

        if(!in_array($request->getPathInfo(), $allows)) {
            if (!(new Session())->get('user_id')) {
                $redirectResponse = new RedirectResponse("/admin/user/login", 302);
                $redirectResponse->send();
                exit();
            }
        }
    }
}