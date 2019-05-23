<?php
namespace app\common\services;

use Yii;
use yii\helpers\Html;

class UtilService {

    public static function getIP(){
        if( !empty($_SERVER['HTTP_X_FORWARD_FOR'])){
            return $_SERVER['HTTP_X_FORWARD_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function encode( $display ){
        return Html::encode($display);
    }

    public static function getRootPath(){
        return dirname(Yii::$app->vendorPath);
    }

}