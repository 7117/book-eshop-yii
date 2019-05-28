<?php
namespace app\modules\weixin\controllers;

use app\common\components\BaseWebController;
use Yii;

class MsgController extends BaseWebController {

    public function actionIndex()
    {
        if (!$this->checkSignature()) {
            return "error signature";
        }
        return "hello";

        if (array_key_exists("echostr",$_GET) && $_GET['echostr'] ) {
            return $_GET['echostr'];
        }

    }

    public function checkSignature(){
        $signature = trim($this->get("signature"));
        $timestamp = trim($this->get("timestamp"));
        $nounce = trim($this->get("nounce"));
        $tmpArr = array(Yii::$app->params['weixin']['token'],$timestamp,$nounce);

        sort( $tmpArr );
        $tmpStr = implode( $tmpArr );

        $tmpStr = sha1( $tmpStr );

        if ($tmpStr == $signature ) {
            return true;
        }else{
            return false;
        }
    }
}