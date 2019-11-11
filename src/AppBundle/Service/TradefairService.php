<?php
/**
 * Created by PhpStorm.
 * User: pro3
 * Date: 2019/11/7
 * Time: 22:23
 */

namespace AppBundle\Service;


use AdminBundle\Traits\EntryPaginatorTrait;

class TradefairService extends AbsService
{
    use EntryPaginatorTrait;

    public function getTradefairList()
    {
       return $this->getDoctrine()->getRepository('AppBundle:Tradefair')
            ->findBy([
                'isenable' => true
            ], [
                'id' => 'desc'
            ]);
    }
}