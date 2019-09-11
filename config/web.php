<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
//    名字
    'id' => 'basic',
//    路径
    'basePath' => dirname(__DIR__),
//    启动的一起
    'bootstrap' => ['log'],
//    设置别名
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
//    组件部分
    'components' => [
//        请求组件
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'atbrntnyudcx',
        ],
//        缓冲组件
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        用户组件
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
//        错误组件
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
//        邮箱组件
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
//        日志组件
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
//      数据库组件  $db = require __DIR__ . '/db.php';
        'db' => $db,
//        路由组件
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                "/"=>"/web/user/login"
            ],
        ],

    ],
//    来源
//    $params = require __DIR__ . '/params.php';
    'params' => $params,
//    模块注册
    'modules' => [
        'web' => [
            'class' => 'app\modules\web\WebModule',
        ],
        'm' => [
            'class' => 'app\modules\m\MModule',
        ],
        'weixin' => [
            'class' => 'app\modules\weixin\WeixinModule',
        ],
    ],
];
//开发环境
if (YII_ENV_DEV) {
//    启动debug
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => [],
    ];
//启动gii
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
//返回配置文件
return $config;
