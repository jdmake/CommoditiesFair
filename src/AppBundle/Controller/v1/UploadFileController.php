<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/31
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/upload")
 */
class UploadFileController extends CommonController
{
    /**
     * @Route("/picture", name="upload_picture")
     */
    public function pictureAction() {
        $allows = ['.png', '.gif', '.jpg', '.jpeg'];
        $file = $_FILES['file'];
        $extend = '.' . explode('/', $file['type'])[1];

        if (!in_array($extend, $allows)) {
            return $this->json([
                'error' => 1,
                'msg' => '只支持上传' . join(',', $allows) . '格式的文件',
            ]);
        }

        $fileName = md5(time()) . $extend;
        $dir = UPLOAD_PATH . '/upload/' . date('Y-m-d');
        $url = 'upload/' . date('Y-m-d') . '/' . $fileName;
        @mkdir($dir, 0777);

        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $fileName)) {
            return $this->json([
                'error' => 1,
                'msg' => '文件保存失败',
            ]);
        }

        return $this->json([
            'error' => 0,
            'msg' => '上传成功',
            'data' => [
                'url' => STATIC_URL . $url
            ]
        ]);
    }
}