<?php
namespace app\modules\weixin\controllers;

use app\common\services\UrlService;
use app\common\services\weixin\RequestService;
use app\common\components\BaseWebController;
use Yii;

class MenuController extends BaseWebController
{
//    设置菜单的代码
    public function actionSet(){
        $menu = [
            "button" => [
                [
                    "name" => "首页",
                    "type" => "view",
                    "url" => UrlService::buildMUrl("/default/index")
                ],
                [
                    "name" => "登录",
                    "type" => "view",
                    "url" => UrlService::buildMUrl("/user/bind")
                ],
            ],
        ];

        $config = Yii::$app->params['weixin'];
        RequestService::setConfig( $config['appid'],$config['token'],$config['sk'] );
        $access_token = RequestService::getAccessToken();

        if ( $access_token ) {
            $url = "menu/create?access_token={$access_token}";
            $ret = RequestService::send($url,json_encode($menu,JSON_UNESCAPED_UNICODE),'POST');

            var_dump($ret);
        }

    }
}