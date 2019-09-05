<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Service;


use AdminBundle\Traits\EntryPaginatorTrait;


class AdminService extends AbsService
{
    use EntryPaginatorTrait;

    /**
     * 状态切换
     * @param $id
     * @return bool
     */
    public function switchStatus($id) {
        $entry = $this->getDoctrine()->getRepository('AppBundle:Admin')
            ->find($id);
        if($entry) {
            $entry->setStatus(!$entry->getStatus());
            $this->getEm()->flush($entry);
            return true;
        }

        return false;
    }

    /**
     * 对象是否已经存在
     * @param $username
     * @return bool
     */
    public function isExistAdmin($username)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:Admin')
            ->findOneBy([
                'username' => $username
            ]);
        return $entry ? true : false;
    }
}