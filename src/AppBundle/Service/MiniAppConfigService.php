<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/26
// +----------------------------------------------------------------------


namespace AppBundle\Service;

/**
 * 微信小程序配置参数
 */
class MiniAppConfigService
{
    private $appid = 'wx4a53f0b36a8357b7';

    private $secret = 'cd27c483d81b58e4c2ba055e0811e060';

    /**
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }


}