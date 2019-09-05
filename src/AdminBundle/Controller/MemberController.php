<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/14
// +----------------------------------------------------------------------


namespace AdminBundle\Controller;


use AdminBundle\Controller\Common\AbsController;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\SwitchButton;

/**
 * @Route("/member")
 */
class MemberController extends AbsController
{
    /**
     * @Route("/index", name="admin_member_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('username', '');

        $pagination = $this->get('admin_member_service')->getPageList('AppBundle:Member', $page, 15,
            function (QueryBuilder $query) use ($search) {
                $query
                    ->select('a.uid,a.openid,a.mobile,a.level,a.parentid,a.credit,a.regtime,a.enable,b.avatar,b.nickname')
                    ->innerJoin('AppBundle:MemberProfile', 'b', 'WITH', 'a.profileid=b.id');
                if ($search != '') {
                    $query->where('b.nickname like :nickname')
                        ->setParameter('nickname', "%{$search}%");
                }
                $query->orderBy('a.regtime', 'desc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setDefaultPk('uid')
            ->setPageTitle("粉丝列表")
            ->addTopSearch('username', '输入昵称进行搜索', '/admin/member/index')
            ->add('uid', '会员号')
            ->add('avatar', '头像', [
                'type' => new Image()
            ])
            ->add('nickname', '昵称')
            ->add('mobile', '手机号码')
            ->add('enable', '状态', [
                'type' => new SwitchButton('/admin/member/switchStatus')
            ])
            ->add('regtime', '时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/member/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/member/delete?id={%id%}',
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
     * @Route("/switchStatus", name="admin_member_switchstatus")
     */
    public function switchStatusAction()
    {
        $id = $this->request()->get('id');

        $this->get('admin_member_service')->switchStatus($id);

        return $this->json([
            'error' => 0,
            'msg' => '改变状态',
        ]);
    }

    /**
     * @Route("/edit", name="admin_member_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id');
        $memberEntry = $this->get('admin_member_service')->getUserDetail($id);
        $form = $this->createFormBuilder($memberEntry)
            ->add('avatar', 'AdminBundle\Custom\Form\Type\AvatarType', [
                'label' => '头像'
            ])
            ->add('nickname', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '昵称'
            ])
            ->add('mobile', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '手机号码'
            ])
            ->add('level', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '会员等级'
            ])
            ->add('credit', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '积分'
            ])
            ->add('enable', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label' => '是否可用'
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if ($this->request()->getMethod() == 'POST') {
            $data = $this->request()->get('form');
            // 保存数据
            $member = $this->get('admin_member_service')->findById('AppBundle:Member', $id) ;
            $member->setMobile($data['mobile']);
            $member->setLevel($data['level']);
            $member->setCredit($data['credit']);
            $member->setEnable($data['enable']);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('修改成功');
        }

        return $this->render('@Admin/form/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/findUser")
     */
    public function findUserAction()
    {
        $nickname = $this->request()->get('nickname');
        $uid = $this->request()->get('uid');

        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQueryBuilder();
        $query->select('a.uid,b.nickname,b.avatar')
            ->from('AppBundle:Member', 'a')
            ->innerJoin('AppBundle:MemberProfile', 'b', 'WITH', 'a.profileid=b.id');

        if(empty($uid)) {
            $query->where('b.nickname like :nickname')
                ->setParameter('nickname', "%{$nickname}%");
        }else {
            $query->where('a.uid=:uid')
                ->setParameter('uid', $uid);
        }


        $query->setFirstResult(0);
        $query->setMaxResults(1);

        $res = $query->getQuery()->getResult();
        if(!$res) {
            return $this->jsonError(1, '用户不存在');
        }

        $user = $res[0];
        return $this->jsonSuccess('查找用户', [
            'user' => $user
        ]);
    }
}