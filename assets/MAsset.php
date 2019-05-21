<?php
namespace app\assets;

use yii\web\AssetBundle;

class MAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'font-awesome/css/font-awesome.css',
        'css/m/css_style.css',
        'css/m/app.css?ver=20170401',
    ];
    public $js = [
        "plugins/jquery-2.1.1.js",
        "js/m/TouchSlide.1.1.js",
        "js/m/common.js",
    ];
}
