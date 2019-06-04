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

        "http://res.wx.qq.com/open/js/jweixin-1.4.0.js",
        "http://res2.wx.qq.com/open/js/jweixin-1.4.0.js",
        "plugins/jquery-2.1.1.js",
        "js/m/TouchSlide.1.1.js",
        "js/m/common.js",
        "js/m/weixin.js",

    ];
}
