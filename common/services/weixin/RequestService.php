<?php
namespace app\modules\weixin;

use app\common\components\HttpClient;
use app\common\services\BaseService;
use app\models\member\OauthAccessToken;

class RequestService extends BaseService
{

    public static $app_token = "";
    public static $appid = "";
    public static $app_secret = "";
    public static $url = "http://qw9d38.natappfree.cc/";

    public static function getAccessToken()
    {
        $data_now = date("Y-m-d H:i:s");
        $access_token_info = OauthAccessToken::find()->where(['>','expired_time',$data_now])->limit(1)->one();

        if ( $access_token_info ) {
            return $access_token_info['access_token'];
        }

        $path = 'token?grant_type=client_credential&appid='.self::getAppid().'&secret='.self::getAppSecret();
        $res = self::send( $path );
        if ( !$res ) {
            return self::_err( self::getLastErrorMsg() );
        }

        $model_access_token = new OauthAccessToken();
        $model_access_token->access_token = $res['access_token'];
        $model_access_token->expired_time = date("Y-m-d H:i:s",$res['expire_in'] + time() - 200);
        $model_access_token->created_time = $data_now;
        $model_access_token->save();
        return $res['access_token'];
    }

    public static function send ($path,$data = [],$method = 'GET')
    {
        $request_url = self::$url.$path;
        if ($method == "POST") {
            $res = HttpClient::post( $request_url,$data);
        }else{
            $res = HttpClient::get( $request_url,$data);
        }

        $ret = @json_decode($res,true);

        if ( !$ret || (isset($res['errcode']) && $ret['errcode'] )){
            return self::_err($ret['errmsg']);
        }

        return $ret;

    }

    public static function setConfig ($appid,$app_token,$app_secret) {
        self::$appid = $appid;
        self::$app_token  = $app_token;
        self::$app_secret = $app_secret;
    }
}