<?php
namespace app\common\services;

use app\models\log\AppLog;
use Yii;
use yii\helpers\Url;

class UtilService {

    public static function getIP(){
        if( !empty($_SERVER['HTTP_X_FORWARD_FOR'])){
            return $_SERVER['HTTP_X_FORWARD_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}