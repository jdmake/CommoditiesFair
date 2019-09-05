<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing;

use YuZhi\TableingBundle\Tableing\Components\AbsButton;

class Table
{
    private $defaultPk = 'id';
    private $thead = [];
    private $pageTitle = '';
    private $dataMaping = [];
    private $tableView;
    private $topSearch;
    private $topButton;
    private $filter;

    private $actions = [];


    /**
     * Table constructor.
     * @param array $dataMaping
     */
    public function __construct($dataMaping = [])
    {
        $this->dataMaping = $dataMaping;
        $this->tableView = new TableView();
    }

    /**
     * @param string $pageTitle
     * @return Table
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }



    /**
     * 添加表格列
     * @param $id
     * @param $title
     * @param array $options
     * @return Table
     */
    public function add($id, $title, array $options = [])
    {
        $this->thead[] = [
            'id' => $id,
            'title' => $title,
            'options' => $options
        ];
        return $this;
    }

    public function addAction($title, array $components) {
        $this->add('__action__', $title);

        $this->actions = $components;

        return $this;
    }

    /**
     * 添加顶部搜索
     * @param $name
     * @param $title
     * @param string $action
     * @return Table
     */
    public function addTopSearch($name, $title, $action = '') {
        $this->topSearch = [
            'name' => $name,
            'title' => $title,
            'action' => $action,
        ];

        return $this;
    }


    /**
     * 添加过滤
     * @param $name
     * @param $default
     * @param array $choice
     * @return Table
     */
    public function addFilter($name, $default, $choice = []) {
        $this->filter[] = [
          'name' => $name,
          'default' => $default,
          'choice' => $choice
        ];
        return $this;
    }

    /**
     * 添加顶部按钮
     * @param AbsButton $button
     * @return Table
     */
    public function addTopButton(AbsButton $button) {
        $this->topButton[] = $button;
        return $this;
    }

    /**
     * 编译视图
     * @return TableView
     */
    public function buildView()
    {
        $this->tableView->setTopSearch($this->topSearch);
        $this->tableView->setThead($this->thead);
        $this->tableView->setTopButton($this->topButton);
        $this->tableView->setFilter($this->filter);
        $this->tableView->setPageTitle($this->pageTitle);

        foreach ($this->dataMaping as $row) {
            $cols = [];
            foreach ($this->thead as $item) {
                if($item['id'] === '__action__') continue;

                if(isset($item['options']['type']) && $item['options']['type']) {
                    $type = $item['options']['type'];
                }else {
                    $type = 'text';
                }

                if(!empty($item['options']['default'])) {
                    $default = $item['options']['default'];
                }else {
                    $default = '';
                }

                $field = $item['id'];
                $cols[] = [
                    'type' => $type,
                    'value' => $row[$field],
                    'default' => $default
                ];
            }
            $tablecol = new TableCols($row[$this->defaultPk]);
            $tablecol->add($cols);
            $this->tableView->buildView($tablecol, $this->actions);
        }

        return $this->tableView;
    }

    /**
     * @return string
     */
    public function getDefaultPk()
    {
        return $this->defaultPk;
    }

    /**
     * @param string $defaultPk
     */
    public function setDefaultPk($defaultPk)
    {
        $this->defaultPk = $defaultPk;
        return $this;
    }


}