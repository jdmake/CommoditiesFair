<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/30
// +----------------------------------------------------------------------


namespace AppBundle\Repository;


use AppBundle\Entity\BoothCategory;
use Doctrine\ORM\EntityRepository;

class BoothCategoryRepository extends EntityRepository
{
    /**
     * 获取全部分类选择列表
     */
    public function getCategoryChoices()
    {
        $choices = [];
        $entrys = $this->findAll();
        /** @var BoothCategory $entry */
        foreach ($entrys as $entry) {
            $choices[$entry->getTitle()] = $entry->getId();
        }

        return $choices;
    }
}