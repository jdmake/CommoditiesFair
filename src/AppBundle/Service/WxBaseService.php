<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/23
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use AppBundle\Util\HttpRequestUtil;

/**
 * 微信基本服务
 * Class WxBaseService
 * @package AppBundle\Service
 */
class WxBaseService extends AbsService
{
    /**
     * 从微信服务器换取 openId, sessionKey, unionId
     * @param $code
     * @return mixed
     */
    public function code2Session($code)
    {
        $config = $this->get('miniapp_config_service');

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $config->getAppid() . '&secret=' . $config->getSecret() . '&js_code=' . $code . '&grant_type=authorization_code';
        $res = HttpRequestUtil::httpGet($url);
        if (isset($res['errcode'])) {
            if ($res['errcode'] == '-1') {
                $this->error = '系统繁忙，此时请开发者稍候再试	';
            }
            if ($res['errcode'] == '41008') {
                $this->error = 'code 无效';
            }
            if ($res['errcode'] == '40029') {
                $this->error = 'code 无效';
            }
            if ($res['errcode'] == '45011') {
                $this->error = '频率限制，每个用户每分钟100次';
            }
            if ($res['errcode'] == '41008') {
                $this->error = 'code 无效';
            }
            return null;
        }

        return json_decode($res, true);
    }
}