<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/15
// +----------------------------------------------------------------------


namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('is_show', array($this, 'isShow')),
        );
    }

    public function isShow($roles, $path)
    {
        if(in_array('*', $roles)) {
            return true;
        }

        return in_array($path, $roles);
    }

    public function getName()
    {
        return 'app_extension';
    }
}