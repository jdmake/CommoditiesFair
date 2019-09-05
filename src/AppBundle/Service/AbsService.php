<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/5
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbsService
{
    protected $error;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->container->get("doctrine");
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 获取数据总条数
     * @param EntityManager $entityManager
     * @return int
     */
    protected function getTotal($query)
    {
        $query->setFirstResult(null)->setMaxResults(null);

        if($query instanceof QueryBuilder) {
            $res = $query->getQuery()->getResult();
        }else {
            $res = $query->getResult();
        }

        return count($res);
    }

    /**
     * 对象转Array
     */
    public function toArray($object) {
        return json_decode(json_encode($object), true);
    }

    /**
     *  创建数据
     * @param $entry
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($entry)
    {
        $this->getEm()->persist($entry);
        $this->getEm()->flush($entry);
    }
}