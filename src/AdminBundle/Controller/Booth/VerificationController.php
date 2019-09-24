<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/20
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Booth;


use AdminBundle\Controller\Common\AbsController;
use AdminBundle\Custom\Form\Type\SelectMemberType;
use AppBundle\Entity\BoothVerificationUser;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/booth/verificationuser")
 */
class VerificationController extends AbsController
{
    /**
     * @Route("/", name="admin_booth_verificationuser")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('booth_service')->getPageList('AppBundle:BoothVerificationUser', $page, 15,
            function (QueryBuilder $query) use ($search) {
                $query->select('a.id, a.uid, c.avatar, c.nickname, a.createAt')
                    ->innerJoin('AppBundle:Member', 'b', 'WITH', 'a.uid=b.uid')
                    ->innerJoin('AppBundle:MemberProfile', 'c', 'WITH', 'b.profileid=c.id')
                    ->orderBy('a.id', 'desc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("核销人员列表")
            ->addTopButton(new LinkButton([
                'title' => ' + 添加核销人员',
                'url' => '/admin/booth/verificationuser/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('avatar', '会员头像', [
                'type' => new Image()
            ])
            ->add('nickname', '会员昵称')
            ->add('createAt', '创建时间', [
                'type' => new DateTime()
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/booth/verificationuser/delete?id={%id%}',
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
     * @Route("/edit", name="admin_booth_verificationuser_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        $booth_service = $this->get('booth_service');
        $boothCateEntry = $booth_service->findCategoryByCid($id);

        $form = $this->createFormBuilder($boothCateEntry)
            ->add('uid', SelectMemberType::class, [
                'label' => '会员',
                'url' => '/admin/member/findUser',
            ])
            ->add("submit", SubmitType::class,
                ['label' => '确认添加'])
            ->getForm();

        if ($this->request()->isMethod('POST')) {
            $data = $this->request()->get('form');
            if(empty($data['uid'])) {
                return $this->error('会员必须选择', 3, 'javascript:window.history.back();');
            }

            $res = $this->getDoctrine()->getRepository('AppBundle:BoothVerificationUser')
                ->findOneBy([
                    'uid' => $data['uid']
                ]);
            if(!$res) {
                $verificationuserEntry = new BoothVerificationUser();
                $verificationuserEntry->setUid($data['uid']);
                $verificationuserEntry->setCreateAt(new \DateTime());
                $this->getDoctrine()->getManager()->persist($verificationuserEntry);
                $this->getDoctrine()->getManager()->flush();
            }


            return $this->success('添加成功', 1);
        }

        return $this->render("@Admin/form/form.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_booth_verificationuser_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);

        $entry = $this->getDoctrine()->getRepository('AppBundle:BoothVerificationUser')->find($id);

        if (!$entry) {
            return $this->error('数据不存在或已被删除', 5, '/admin/booth/verificationuser/', false);
        }

        $this->getManager()->remove($entry);
        $this->getManager()->flush($entry);

        return $this->success('操作成功', 1, '/admin/booth/verificationuser/', false);
    }
}