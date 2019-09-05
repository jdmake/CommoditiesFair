<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Twig\Extension;


use PhpParser\Node\Expr\Closure;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use YuZhi\TableingBundle\Tableing\TableView;

class TableingExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('table', [$this, 'table'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_thead', [$this, 'table_thead'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_tbody', [$this, 'table_tbody'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_actions', [$this, 'table_actions'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_action_closure', [$this, 'table_action_closure'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_callable', [$this, 'table_callable'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_widget', [$this, 'table_widget'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_component', [$this, 'table_component'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_url', [$this, 'table_url'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('table_path', [$this, 'table_path'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * 渲染表格
     */
    public function table(Environment $env, TableView $tableView)
    {
        return $env->render('@YuZhiTableing/table.html.twig', [
            'tableView' => $tableView
        ]);
    }

    /**
     * 渲染表格头
     */
    public function table_thead(Environment $env, TableView $tableView)
    {
        return $env->render('@YuZhiTableing/table_thead.html.twig', [
            'tableView' => $tableView
        ]);
    }

    /**
     * 渲染表格正文
     */
    public function table_tbody(Environment $env, TableView $tableView)
    {
        return $env->render('@YuZhiTableing/table_tbody.html.twig', [
            'tableView' => $tableView
        ]);
    }

    /**
     * 是否是回调函数
     */
    public function table_action_closure(Environment $env, $closure)
    {
        return is_callable($closure);
    }

    /**
     * 渲染表格ACTION按钮
     */
    public function table_actions(Environment $env, TableView $tableView, $item)
    {
        return $env->render('@YuZhiTableing/table_actions.html.twig', [
            'tableView' => $tableView,
            'pk_value' => $item->getPkValue(),
            'item' => $item,
        ]);
    }

    /**
     * 渲染表格小部件
     */
    public function table_widget(Environment $env, $pk_value, $col)
    {
        return $env->render('@YuZhiTableing/table_widget.html.twig', [
            'pk_value' => $pk_value,
            'col' => $col,
        ]);
    }

    /**
     * 渲染表单组件
     */
    public function table_component(Environment $env, $pk_value, $component)
    {
        return $component['type']->render($pk_value, $component['value']);
    }

    /**
     * 生成带参数的URL
     */
    public function table_url(Environment $env, $param)
    {
        parse_str(@$_SERVER['QUERY_STRING'], $query_arr);
        unset($query_arr[$param]);
        return http_build_query($query_arr);
    }

    /**
     * 获取当前path
     */
    public function table_path(Environment $env)
    {
        return str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
    }

    public function table_callable(Environment $env, $callable, $item) {
        return $callable($item);
    }
}