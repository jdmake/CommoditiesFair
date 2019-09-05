<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/19
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkComment;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\SwitchButton;

/**
 * @Route("/comment")
 */
class CommentController extends AbsController
{
    /**
     * @Route("/index", name="admin_comment_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);

        $pagination = $this->get('category_service')->getPageList('AppBundle:PioneerparkComment', $page, 15,
            function (QueryBuilder $query) {
                $query->select('a.id,a.content,a.zantotal,a.enable,a.createAt,c.avatar,c.nickname,d.title');
                $query->innerJoin('AppBundle:PioneerparkMember', 'b', 'WITH', 'a.uid=b.uid');
                $query->innerJoin('AppBundle:PioneerparkMemberProfile', 'c', 'WITH', 'b.profileid=c.id');
                $query->innerJoin('AppBundle:PioneerparkDongtai', 'd', 'WITH', 'd.id=a.fromid');
                $query->orderBy('a.createAt', 'desc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("评论列表")
            ->add('id', '编号')
            ->add('content', '评论内容')
            ->add('avatar', '用户头像', [
                'type' => new Image()
            ])
            ->add('nickname', '用户昵称')
            ->add('zantotal', '点赞次数')
            ->add('title', '文章标题')
            ->add('enable', '审核', [
                'type' => new SwitchButton('/admin/comment/pass')
            ])
            ->add('createAt', '评论时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/comment/delete?id={%id%}',
                    'confirm' => '真的要删除吗？',
                    'class' => 'btn btn-pink'
                ]),
            ])
            ->buildView();

        return $this->render('@Admin/Table/base.html.twig', [
            'tableView' => $tableView,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/pass", name="admin_comment_pass")
     */
    public function passAction()
    {
        $id = $this->request()->get('id');
        /** @var PioneerparkComment $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkComment', $id);
        if($entry) {
            $entry->setEnable(!$entry->getEnable());
            $this->getDoctrine()->getManager()->flush();
            return $this->jsonSuccess('操作成功');
        }else {
            return $this->jsonError(1, '数据不存在，或已被删除');
        }
    }

    /**
     * @Route("/delete", name="admin_comment_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkComment $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkComment', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/comment/index');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/comment/index');
        }
    }
}