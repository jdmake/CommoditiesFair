<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/19
// +----------------------------------------------------------------------


namespace AppBundle\Service;


class QRcodeService extends AbsService
{
    public function makeQrcode($code)
    {
        require_once __DIR__ . '/../../../vendor/phpqrcode/phpqrcode.php';
        ob_start();
        \QRcode::png($code, false, 'L', 10, 1);
        $img = ob_get_contents();
        ob_end_clean();
        $imgInfo = 'data:image/png;base64,' . chunk_split(base64_encode($img));//转base64
        ob_flush();
        return $imgInfo;
    }
}