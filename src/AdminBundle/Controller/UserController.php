<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Util\EncryptUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/user")
 */
class UserController extends AbsController
{
    /**
     * 管理员登录页面
     * @Route("/login", name="admin_user_login")
     */
    public function loginAction()
    {
        if ($this->request()->getMethod() === 'POST') {

            $form = $this->getFormData();

            $rep = $this->getDoctrine()->getRepository("AppBundle:Admin");
            $user = $rep->findOneBy([
                'username' => $form['username'],
                'status' => true
            ]);
            if (!$user) {
                return $this->error('账号错误', 3, '/admin/user/login');
            }

            if (!EncryptUtil::verifyPassword($form['password'], $user->getPsw())) {
                return $this->error('密码错误', 3, '/admin/user/login');
            }

            $this->request()->getSession()->set('user_id', $user->getId());
            $this->request()->getSession()->set('user_name', $user->getUsername());

            return $this->success("登录成功", 1, '/admin');
        }

        return $this->render('@Admin/User/login.html.twig');
    }

    /**
     * @Route("/logout", name="admin_user_logout")
     */
    public function logoutAction()
    {
        (new Session())->set('user_id', null);
        (new Session())->set('user_name', null);
        return $this->redirectToRoute('admin_user_login');
    }
}