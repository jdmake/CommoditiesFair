<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/23
// +----------------------------------------------------------------------


namespace YuZhi\TableingBundle\Tableing;

class TableView
{
    private $thead;
    private $rows = [];
    private $actions = [];
    private $topSearch = [];
    private $topButton = [];
    private $filter = [];
    private $pageTitle = '';

    public function buildView(TableCols $tableCols, array $actions) {
        $this->rows[] = $tableCols;
        $this->actions = $actions;
    }

    /**
     * @param mixed $thead
     */
    public function setThead($thead)
    {
        $this->thead = $thead;
    }

    /**
     * @param array $topSearch
     */
    public function setTopSearch($topSearch)
    {
        $this->topSearch = $topSearch;
    }

    /**
     * @return array
     */
    public function getTopSearch()
    {
        return $this->topSearch;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return mixed
     */
    public function getThead()
    {
        return $this->thead;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getTopButton()
    {
        return $this->topButton;
    }

    /**
     * @param array $topButton
     */
    public function setTopButton($topButton)
    {
        $this->topButton = $topButton;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }



}