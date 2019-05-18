<?php
namespace app\assets;

use yii\web\AssetBundle;

class WebAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/web/bootstrap.min.css',
        'font-awesome/css/font-awesome.css',
        'css/web/style.css?ver=20170401',
    ];
    public $js = [
        "plugins/jquery-2.1.1.js",
        "js/web/bootstrap.min.js",
        "js/web/common.js",
    ];
}
