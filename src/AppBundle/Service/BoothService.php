<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/24
// +----------------------------------------------------------------------


namespace AppBundle\Service;

use AdminBundle\Traits\EntryPaginatorTrait;
use AppBundle\Entity\Booth;
use Knp\Component\Pager\Paginator;

/**
 * 展位服务
 * Class BoothService
 * @package AppBundle\Service
 */
class BoothService extends AbsService
{
    use EntryPaginatorTrait;

    /**
     * 获取摊位矩阵数据
     */
    public function getBoothMatrix($tradefair_id)
    {
        // 获取最大行数
        $maxRow = $this->getDoctrine()->getRepository('AppBundle:Booth')->getMaxRow($tradefair_id) + 1;
        // 获取最大列数
        $maxCol = $this->getDoctrine()->getRepository('AppBundle:Booth')->getMaxCol($tradefair_id) + 1;

        $booths = [];
        for ($i = 0; $i < $maxRow; $i++) { // 行
            for ($j = 0; $j < $maxCol; $j++) {
                $entry = $this->getDoctrine()->getRepository('AppBundle:Booth')
                    ->getBooths($tradefair_id, $i, $j, true);
                if (!$entry) {
                    $entry = [];
                }
                // 是否已售出
                $entry['is_soldout'] = $this->getDoctrine()->getRepository('AppBundle:BoothBuyRecord')
                    ->findOneBy([
                        'boothId' => $entry['id']
                    ]) ? true : false;
                $booths[$i][$j] = $entry;
            }
        }
        return $booths;
    }

    /**
     * 获取上传记录
     * @param $uid
     * @return array
     */
    public function getUpdateLog($uid)
    {
        $query = $this->getEm()->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:SalesRecord', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid',$uid)
            ->setFirstResult(0)
            ->setMaxResults(5)
            ->orderBy('a.reportTime', 'desc');

        return $query->getQuery()->getArrayResult();
    }

    public function getMyBoothChoice($uid)
    {
        $query = $this->getEm()->createQueryBuilder();
        $query->select('b.id, b.title')
            ->from('AppBundle:BoothBuyRecord', 'a')
            ->innerJoin('AppBundle:Booth', 'b', 'WITH', 'a.boothId=b.id')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->orderBy('a.createAt', 'desc');
        $res =$query->getQuery()->getArrayResult();

        return $res;
    }

    /**
     * 根据矩阵位置获取展位
     * @param $row
     * @param $col
     * @return Booth|object|null
     */
    public function getBoothByRowCol($tid, $row, $col)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:Booth')
            ->findOneBy([
                'tradefair_id' => $tid,
                'row' => $row,
                'col' => $col,
            ]);

        return $entry;
    }

    /**
     * 获取展位详情
     * @param $id
     * @return \AppBundle\Entity\Booth|object|null
     */
    public function findDetailById($id)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:Booth')
            ->findOneBy([
                'id' => $id,
                'enable' => true
            ]);
        return $res;
    }


    /**
     * 获取分类
     * @param $cid
     * @return \AppBundle\Entity\BoothCategory|object|null
     */
    public function findCategoryByCid($cid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:BoothCategory')
            ->find($cid);
    }

    /**
     * 展位协议
     */
    public function getBoothAgreement()
    {
        $configEntry = $this->getDoctrine()->getRepository('AppBundle:Settings')
            ->findOneBy([
                'configKey' => 'agreement'
            ]);
        return $configEntry->getConfigValue();
    }

    /**
     * 获取我的展位
     * @param $uid
     * @param $isuse
     * @param $page
     * @param $size
     * @return array
     */
    public function getMyBoothPageList($uid, $isuse, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a.id,a.uid,a.total,a.verificationCode,a.createAt,a.isuse,b.category,b.title,b.number,b.size,b.price,b.content,b.visits,b.enable')
            ->from('AppBundle:BoothBuyRecord', 'a')
            ->innerJoin('AppBundle:Booth', 'b', 'WITH', 'a.boothId=b.id')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->andWhere('a.isuse=:isuse')
            ->setParameter('isuse', $isuse)
            ->setFirstResult(($page - 1) * $size)
            ->setMaxResults($size)
            ->orderBy('a.createAt', 'desc');

        $list = $query->getQuery()->getResult();
        foreach ($list as &$item) {
            $item['createAt'] = $item['createAt']->format('Y-m-d H:i');
        }
        $total = $this->getTotal($query);
        return [
            'list' => $list,
            'total' => $total,
            'page_size' => intval(ceil($total / $size))
        ];
    }

    /**
     * 获取已售出的展位分页列表
     * @param $search
     * @param $page
     * @param $size
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getSoldoutBoothPageList($search, $page, $size)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a.id,a.uid,d.nickname,d.avatar,a.total,a.verificationCode,a.createAt,a.isuse,b.category,b.title,b.number,b.size,b.price,b.content,b.visits,b.enable')
            ->from('AppBundle:BoothBuyRecord', 'a')
            ->innerJoin('AppBundle:Booth', 'b', 'WITH', 'a.boothId=b.id')
            ->innerJoin('AppBundle:Member', 'c', 'WITH', 'c.uid=a.uid')
            ->innerJoin('AppBundle:MemberProfile', 'd', 'WITH', 'c.profileid=d.id')
            ->orderBy('a.createAt', 'desc');

        if ($search != '') {
            $query->andWhere('b.number = :number')
                ->setParameter('number', $search);
        }

        /** @var Paginator $knp_paginator */
        $knp_paginator = $this->get('knp_paginator');
        $pagination = $knp_paginator->paginate($query, $page, $size);

        return $pagination;
    }

    /**
     * 创建展位
     * @param array $form
     * @return int
     */
    public function createBooth(array $form)
    {
        $entry = new Booth();
        $entry->setRow($form['row']);
        $entry->setTradefairId($form['tid']);
        $entry->setCol($form['col']);
        $entry->setCategory($form['category']);
        $entry->setSort(0);
        $entry->setTitle($form['title']);
        $entry->setNumber($form['number']);
        $entry->setSize($form['size']);
        $entry->setPrice($form['price']);
        $entry->setPicture(isset($form['picture']) ? join(',', $form['picture']) : '');
        $entry->setContent($form['content']);
        $entry->setVisits(0);
        $entry->setEnable(true);
        $this->create($entry);

        return $entry->getId();
    }

    /**
     * 编辑展位
     * @param $row
     * @param $col
     * @param array $form
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateBooth($row, $col, array $form)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:Booth')
            ->findOneBy([
                'tradefair_id' => $form['tid'],
                'row' => $row,
                'col' => $col,
            ]);
        if (!$entry) {
            return false;
        }

        $entry->setCategory($form['category']);
        $entry->setSort(0);
        $entry->setTitle($form['title']);
        $entry->setNumber($form['number']);
        $entry->setSize($form['size']);
        $entry->setPrice($form['price']);
        $entry->setPicture(isset($form['picture']) ? join(',', $form['picture']) : '');
        $entry->setContent($form['content']);
        $this->getEm()->flush($entry);

        return true;
    }

    /**
     * 展位是否已售出
     * @param $id
     * @return bool
     */
    public function isSoldoutBooth($id)
    {
        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothBuyRecord')
            ->findOneBy([
               'boothId' => $id
            ]);

        return $entry ? true : false;
    }
}