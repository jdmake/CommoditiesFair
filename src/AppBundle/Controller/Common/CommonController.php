<?php

namespace AppBundle\Controller\Common;

use AppBundle\Controller\Filter\SessionFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class CommonController extends Controller
{
    public function __construct()
    {
        SessionFilter::doFilter(Request::createFromGlobals());
        //AuthorityFilter::doFilter(Request::createFromGlobals());
    }

    protected function getUserSession($name)
    {
        $user = $this->get('session')->get('user');

        if($user) {
            return @$user[$name];
        }
        return null;
    }

    /**
     * 获取WEB输入
     * @param $name
     * @param string $default
     * @return string
     */
    protected function input($name, $default = '')
    {
        $res = $this->request()->get($name);
        if(empty($res)) {
            $res = $this->getJsonParameter($name);
        }

        return $res ?: $default;
    }

    /**
     * @param $persistentObject
     * @param null $persistentManagerName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function repository($persistentObject, $persistentManagerName = null)
    {
        return $this->getDoctrine()->getRepository($persistentObject, $persistentManagerName);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function manager()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    protected function flush($entry)
    {
        return $this->manager()->flush($entry);
    }

    protected function remove($entry) {
        return $this->manager()->remove($entry);
    }

    protected function request()
    {
        return $this->get('request_stack')->getCurrentRequest();
    }

    protected function getJsonParameter($name, $default = '') {
        $json = json_decode($this->get('request_stack')->getCurrentRequest()->getContent(), true);
        if(!$json) {
            return $default;
        }
        return array_key_exists($name, $json) ? $json[$name] : $default;
    }

    protected function success($msg, $timeout = 3, $url = '')
    {
        return $this->render('success.html.twig', [
            'msg' => $msg,
            'timeout' => $timeout,
            'url' => $url,
        ]);
    }

    protected function error($msg, $timeout = 3, $url = '')
    {
        return $this->render('error.html.twig', [
            'msg' => $msg,
            'timeout' => $timeout,
            'url' => $url,
        ]);
    }

    /**
     * @return array
     */
    protected function getFormData()
    {
        return $this->request()->get('form');
    }

    protected function url($path)
    {
        return $this->request()->getUriForPath($path);
    }

    protected function render($view, array $parameters = [], Response $response = null)
    {
        $admin_name = (new Session())->get("user_name");
        return parent::render($view, array_merge($parameters, [
            'admin_name' => $admin_name
        ]), $response);
    }

    /**
     * 验证器
     * @param array $data
     * @param $rules
     * @return array
     */
    protected function validator($data, $rules)
    {
        $errors = [];
        $validator = $this->get('validator');
        foreach ($rules as $key => $rule) {
            if (array_key_exists($key, $data)) {

                $res = $validator->validate($data[$key], $rule);
                if (count($res) > 0) {
                    $errors[] = $res->get(0)->getMessage();
                }
            }
        }

        return $errors;
    }

    /**
     * 成功返回JSON格式数据
     */
    protected function jsonSuccess($msg, $data = [])
    {
        $output = [
            'error' => 0,
            'msg' => $msg,
            'data' => $data
        ];
        if (!$data) unset($output['data']);
        return $this->json($output);
    }

    /**
     * 错误返回JSON格式数据
     */
    protected function jsonError($error, $msg)
    {
        $output = [
            'error' => $error,
            'msg' => $msg,
        ];
        return $this->json($output);
    }
}
