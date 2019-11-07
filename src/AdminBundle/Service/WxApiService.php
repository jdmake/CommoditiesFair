<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/8
// +----------------------------------------------------------------------


namespace AdminBundle\Service;

use AppBundle\Util\HttpRequestUtil;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * 微信API
 */
class WxApiService extends AbsService
{
    private $appid = 'wx4a53f0b36a8357b7';
    private $secret = 'cd27c483d81b58e4c2ba055e0811e060';

    /**
     * 获取小程序全局唯一后台接口调用凭据（access_token）。调调用绝大多数后台接口时都需使用 access_token，开发者需要进行妥善保存。
     * GET https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
     */
    public function getAccessToken()
    {
        $cache = new FilesystemAdapter('', 7200);
        $cacheAccessToken = $cache->getItem('wx.access_token');
        $accessToken = $cacheAccessToken->get();
        if (!$accessToken) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
            $res = HttpRequestUtil::httpGet($url);
            $jsonArray = json_decode($res, true);

            if (is_array($jsonArray) && array_key_exists('errcode', $jsonArray)) {
                $error = $jsonArray['errcode'];
                switch ($error) {
                    case '-1':
                        $this->error = '系统繁忙，此时请开发者稍候再试';
                        break;
                    case '40001':
                        $this->error = 'AppSecret 错误或者 AppSecret 不属于这个小程序，请开发者确认 AppSecret 的正确性';
                        break;
                    case '40002':
                        $this->error = '请确保 grant_type 字段值为 client_credential';
                        break;
                    case '40013':
                        $this->error = '不合法的 AppID，请开发者检查 AppID 的正确性，避免异常字符，注意大小写';
                        break;
                    default:
                        $this->error = '未知错误';
                        break;
                }
                return null;

            } else if (!is_array($jsonArray)) {
                $this->error = '远程服务器错误';
                return null;
            }
            // 写入缓存
            $accessToken = $cacheAccessToken->set($jsonArray['access_token']);
            $cache->save($cacheAccessToken);
            $cache->commit();
        }

        return $accessToken;
    }

    /**
     * 发送模板消息
     * POST https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=ACCESS_TOKEN
     * @param $access_token
     * @param $touser
     * @param $template_id
     * @param $page
     * @param $form_id
     * @param array $data
     * @param string $emphasis_keyword
     * @return mixed
     */
    public function templateMessageSend($access_token, $touser, $template_id, $page, $form_id, $data = [], $emphasis_keyword = '')
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $res = HttpRequestUtil::httpPostJson($url, json_encode([
            'touser' => $touser,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' => $data,
            'emphasis_keyword' => $emphasis_keyword,
        ]));
        $jsonArray = json_decode($res, true);

        if (is_array($jsonArray) && array_key_exists('errcode', $jsonArray)) {
            $error = $jsonArray['errcode'];
            switch ($error) {
                case '40037':
                    $this->error = 'template_id不正确';
                    break;
                case '41028':
                    $this->error = 'form_id不正确，或者过期';
                    break;
                case '41029':
                    $this->error = 'form_id已被使用';
                    break;
                case '41030':
                    $this->error = 'page不正确';
                    break;
                case '45009':
                    $this->error = '接口调用超过限额（目前默认每个帐号日调用限额为100万）';
                    break;
                default:
                    $this->error = '未知错误';
                    break;
            }
            return null;

        } else if (!is_array($jsonArray)) {
            $this->error = '远程服务器错误';
            return null;
        }

        return $jsonArray;
    }

    /**
     * 发送微信推送消息 - 审核结果通知
     * @param $uid
     * @param $form_id
     * @param $page
     */
    public function sendApprovalMsg($uid, $form_id, $page, $status, $msg)
    {

        // 获取用户信息
        $userEntry = $this->get('admin_member_service')->getMember($uid);
        $touser = $userEntry->getOpenid();

        $access_token = $this->getAccessToken();

        if ($access_token) {
            $res = $this->templateMessageSend($access_token, $touser
                , 'HI0e2qd9b08crm6kKlHaB6oxWsnnM8g6uCd_BT8uglg'
                , $page
                , $form_id
                , [
                    'keyword1' => [
                        'value' => $status
                    ],
                    'keyword2' => [
                        'value' => date('Y-m-d H:i:s', time())
                    ],
                    'keyword3' => [
                        'value' => $msg
                    ],
                ]
            );

        }
    }

    /**
     * 发送微信推送消息 - 预约成功
     * @param $uid
     * @param $form_id
     * @param $page
     */
    public function sendReservationMsg($uid, $form_id, $date, $housingName, $address, $pro)
    {

        // 获取用户信息
        $userEntry = $this->get('fans_service')->getMemberByUid($uid);
        $touser = $userEntry->getMemberOpenid();
        $profile = $this->get('fans_service')->getMemberProfile($userEntry->getMemberProfileid());
        $name = $profile->getProfileRealname() ?: $profile->getProfileNickname();

        $access_token = $this->getAccessToken();
        if ($access_token) {
            $res = $this->templateMessageSend($access_token, $touser
                , 'IXbONAAeL2CYI02H6JSCqdmFgocuZrY3Jk4QQTNgl8A'
                , ''
                , $form_id
                , [
                    'keyword1' => [
                        'value' => $date
                    ],
                    'keyword2' => [
                        'value' => $housingName
                    ],
                    'keyword3' => [
                        'value' => $address
                    ],
                    'keyword4' => [
                        'value' => $pro
                    ],
                ], ''
            );
        }
    }
}