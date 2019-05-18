<?php
namespace app\common\services;

use yii\helpers\Url;

class UrlService {

    public static function buildWebUrl($path,$params = [] ){
        $path = Url::toRoute(array_merge([$path],$params));
        return "/web".$path;
    }

    public static function buildMUrl($path,$params = [] ){
        $path = Url::toRoute(array_merge([$path],$params));
        return "/m".$path;
    }

    public static function buildWwwUrl($path,$params = [] ){
        $path = Url::toRoute(array_merge([$path],$params));
        return $path;
    }

    public static function buildNullUrl(){
        return "javascript:void(0);";
    }

}