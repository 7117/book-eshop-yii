<?php

namespace app\common\services;

use Yii;

class StaticService {
    public static function includeAppJsStatic($path,$depend) {
        self::includeAppStatic('js',$path,$depend);
    }

    public static function includeAppCssStatic($path,$depend) {
        self::includeAppStatic('css',$path,$depend);
    }

    public static function includeAppStatic($type,$path,$depend) {
        $version = defined('VERSION') ? VERSION : time();
        $path = $path.'?ver='.$version;

        if ($type =='css') {
            Yii::$app->getView()->registerCssFile($path,["depends" => $depend]);
        }else{
            Yii::$app->getView()->registerJsFile($path,["depends" => $depend]);
        }
    }
}