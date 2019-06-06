<?php

namespace app\common\services\weixin;

use app\common\components\HttpClient;
use app\common\services\applog\ApplogService;
use Yii;
use yii\log\FileTarget;

class PayApiService {
    private $params = [];
    private $wxpay_params = [];
    private $prepay_id = null;
    public  $prepay_info = null;

    public function __construct( $wxpay_params ){
        $this->wxpay_params = $wxpay_params;
    }

    public function setWxpay_params ( $wxpay_params ){
        $this->wxpay_params = $wxpay_params;
    }

    //设置预订单需要的数据
    public function setParameter($parameter, $parameterValue){
        $this->params[$parameter] = $parameterValue;
    }

    //获取预订单
    public function getPrepayInfo(){
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $this->params["nonce_str"] = $this->createNoncestr();
        $this->params["sign"] = $this->getSign($this->params);
        $xml_data = $this->arrayToXml($this->params);
        $ret = HttpClient::post($url,$xml_data);
        if( $ret ){
            $wx_order = $this->xmlToArray($ret);
            $this->prepay_info = $wx_order;
            $this->record_xml( var_export($wx_order,true) );
            if(isset($wx_order['result_code']) && $wx_order['result_code'] == 'SUCCESS'){
                return $wx_order;
            }
        }
        return false;
    }

    //设置prepay_id
    public function setPrepayId($prepay_id){
        $this->prepay_id = $prepay_id;
    }

    //设置
    public function getSignParams(){
        $this->params["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->params["sign"] = $this->getSign($this->params);//签名
        return $this->params;
    }
    private function createNoncestr( $length = 32 ){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    private function getSign($Obj){
        foreach ($Obj as $k => $v){
            $Parameters[$k] = $v;
        }
        ksort($Parameters);//键值排序
        $String = $this->formatBizQueryParaMap($Parameters, false);
        $String = $String."&key=".$this->wxpay_params['pay']['key'];
        $String = md5($String);
        $result_ = strtoupper($String);
        return $result_;
    }

    public function checkSign($sign)
    {
        $tmpData = $this->params;
        $wxpay_sign = $this->getSign($tmpData);//本地签名

        if ($wxpay_sign == $sign) {
            return TRUE;
        }
        return FALSE;
    }

    //拼接成有序字符串
    private function formatBizQueryParaMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){
            if($urlencode){
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0){
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    //给js的数据
    public function getParameters(){
        $jsApiObj["appId"] = $this->wxpay_params['appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = $timeStamp;
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=".$this->prepay_id;
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        return $jsApiObj;
    }

    //转换
    public function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";

            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    //转换
    public function xmlToArray($xml){
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    //记录
    private function record_xml($xml){
        $log = new FileTarget();
        $log->logFile = Yii::$app->getRuntimePath() . "/logs/wxpay_sign_".date("Ymd").".log";
        $log->messages[] = [
            "[url:{$_SERVER['REQUEST_URI']}],[xml data:{$xml}]",
            1,
            'application',
            time()
        ];
        $log->export();
    }
}