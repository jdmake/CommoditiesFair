<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/14
// +----------------------------------------------------------------------


namespace AppBundle\Controller\Filter;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthorityFilter
{
    public static function doFilter(Request $request)
    {
        $roles = (new Session())->get('roles');
        $roles = $roles ?: [];

        if(in_array('*', $roles)) {
            return;
        }
        if(!in_array($request->getPathInfo(), $roles) && $request->getPathInfo() != '/user/login' && $request->getPathInfo() != '/user/logout') {
            $res = new Response('<div style="margin: 0 auto;text-align: center;padding-top: 50px"><h1 style="color: #ff3140">没有权限操作</h1></div>');
            $res->send();
            exit();
        }
    }
}