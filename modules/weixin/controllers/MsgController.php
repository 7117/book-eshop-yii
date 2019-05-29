<?php
namespace app\modules\weixin\controllers;

use app\common\components\BaseWebController;

class MsgController extends BaseWebController {

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        if (!$this->checkSignature()) {
            return "error signature";
        }

        if (array_key_exists("echostr",$_GET) && $_GET['echostr'] ) {
            return $_GET['echostr'];
        }

    }

    public function checkSignature(){
        $signature = trim( $this->get("signature","") );
        $timestamp = trim( $this->get("timestamp","") );
        $nonce = trim( $this->get("nonce","") );
        $tmpArr = array( \Yii::$app->params['weixin']['token'],$timestamp,$nonce );
        sort( $tmpArr,SORT_STRING );
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr ==  $signature ){
            return true;
        }else{
            return false;
        }
    }
}