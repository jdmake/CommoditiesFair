<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/12
// +----------------------------------------------------------------------


namespace AppBundle\Service;

/**
 * 微信支付
 */
class WxPayService extends AbsService
{
    private $appid = 'wxdcab8fbe5785c92e';
    private $key = "ba5954abbe0302c6c409ed9d3bd17f7b";
    private $mch_id = "1549604581";

    /**
     * 统一下单
     */
    public function uniformOrder($openid, $money, $order_no, $body)
    {
        $str = "QWERTYUIPADGHJKLZXCVNM1234567890";
        $nonce = str_shuffle($str);
        $pay['appid'] = $this->appid;
        $pay['body'] = $body;               //商品描述
        $pay['mch_id'] = $this->mch_id;         //商户号
        $pay['nonce_str'] = $nonce;             //随机字符串
        $pay['notify_url'] = 'https://pioneerpark.zoro.net.cn/v1/wxpay/notify_url';
        $pay['openid'] = $openid;
        $pay['out_trade_no'] = $order_no;       //订单号
        $pay['spbill_create_ip'] = $_SERVER['SERVER_ADDR']; // 终端IP
        $pay['total_fee'] = 100 * $money; //支付金额
        $pay['trade_type'] = 'JSAPI';    //交易类型

        //组建签名（不可换行 空格  否则哭吧）
        $stringA = "appid=" . $pay['appid'] . "&body=" . $pay['body'] . "&mch_id=" . $pay['mch_id'] . "&nonce_str=" . $pay['nonce_str'] . "&notify_url=" . $pay['notify_url'] . "&openid=" . $pay['openid'] . "&out_trade_no=" . $pay['out_trade_no'] . "&spbill_create_ip=" . $pay['spbill_create_ip'] . "&total_fee=" . $pay['total_fee'] . "&trade_type=" . $pay['trade_type'];
        $stringSignTemp = $stringA . "&key=" . $this->key; //注：key为商户平台设置的密钥key(这个还需要再确认一下)
        $sign = strtoupper(md5($stringSignTemp)); //注：MD5签名方式
        $pay['sign'] = $sign;//签名

        //统一下单请求
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data = $this->_arrayToXml($pay);
        $res = $this->_wxpost($url, $data);
        $pay_arr = $this->_xmlToArray($res);  //这里是数组
        if ($pay_arr['return_code'] == 'FAIL' || $pay_arr['result_code'] == 'FAIL') {
            return $pay_arr;
        }

        $timeStamp = time();
        $nonce_pay = str_shuffle($str);
        $package = $pay_arr['prepay_id'];
        $signType = "MD5";
        $stringPay = "appId=" . $this->appid . "&nonceStr=" . $nonce_pay . "&package=prepay_id=" . $package . "&signType=" . $signType . "&timeStamp=" . $timeStamp . "&key=" . $this->key;
        $paySign = strtoupper(md5($stringPay));
        $rpay['timeStamp'] = (string)$timeStamp;
        $rpay['nonceStr'] = $nonce_pay;
        $rpay['package'] = "prepay_id=" . $package;
        $rpay['signType'] = $signType;
        $rpay['paySign'] = $paySign;
        $rpay['orders'] = $order_no;

        return $rpay;
    }

    private function _arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . $this->_arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    private function _xmlToArray($xml, $type = '')
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        //simplexml_load_string()解析读取xml数据，然后转成json格式
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($type == "json") {
            $json = json_encode($xmlstring);
            return $json;
        }
        $arr = json_decode(json_encode($xmlstring), true);
        return $arr;
    }

    /**
     * 微信post
     * @param $url
     * @param $post
     * @return bool|string
     */
    private function _wxpost($url, $post)
    {
        //初始化
        $curl = curl_init();
        $header[] = "Content-type: text/xml";//定义content-type为xml
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        //定义请求类型
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = $post;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        //显示获得的数据
        //print_r($data);
        if ($data) {
            curl_close($curl);
            return $data;
        } else {
            $res = curl_error($curl);
            curl_close($curl);
            return $res;
        }
    }


    public function notifyUrlCallBack(callable $function)
    {
        $receipt = $_REQUEST;
        if($receipt==null){
            $receipt = file_get_contents("php://input");
        }
        if($receipt == null){
            $receipt = $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        $post_data = $this->_xmlToArray($receipt);
        $postSign = $post_data['sign'];
        unset($post_data['sign']);
        ksort($post_data);// 对数据进行排序
        $str =  $params = http_build_query($post_data);//对数组数据拼接成key=value字符串
        $user_sign = strtoupper(md5($str."&key={$this->key}"));   //再次生成签名，与$postSign比较
        $ordernumber = $post_data['out_trade_no'];// 订单可以查看一下数据库是否有这个订单
        if($post_data['return_code']=='SUCCESS'&& $postSign == $user_sign){
            // 查询订单是否已经支付(通过订单号调取微信的查询订单的接口)
            //如果已经支付 更改数据库中的 支付状态 并写入日志表
            $function($post_data);
        }else{
            // 写个日志记录
            file_put_contents('wxPaylog.txt',$post_data['return_code'].PHP_EOL, FILE_APPEND);
            echo '微信支付失败';
            echo  "success";
        }
    }
}