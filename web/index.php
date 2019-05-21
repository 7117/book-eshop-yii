<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

//版本控制
if(file_exists("../version")){
    define("VERSION",trim(file_get_contents("../version")));
}else{
    define("VERSION",date("Y-M-D H:i:s"));
}

(new yii\web\Application($config))->run();
