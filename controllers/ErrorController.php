<?php

namespace app\controllers;

use Yii;
use yii\log\FileTarget;
use app\common\services\AppLogService;
use app\modules\web\controllers\common\BaseController;

class ErrorController extends BaseController
{
    public function actionError(){
        $request=Yii::$app->request;
        $error=Yii::$app->errorHandler->exception;
        $err_msg='';
        if($error){

            $file=$error->getFile();
            $line=$error->getLine();
            $message=$error->getMessage();
            $code=$error->getCode();
            $get=http_build_query($request->get());
            $post=http_build_query($request->post());
            $ajax=$request->isAjax?'true':'false';

            $err_msg=
                "原因:". "[$message]" ."<br>".
                "文件:"."[{$file}]"."<br>".
                "行数:"."[{$line}]"."<br>".
                "错误码:"."[{$code}]"."<br>".
                "路由:"."[{$_SERVER['REQUEST_URI']}]"."<br>".
                "GET请求:" ."[$get]". " <br>".
                "POST请求:" ."[$post]". "<br>".
                "AJAX请求:" ."[$ajax]". "<br>";

            $log=new FileTarget();

            $log->logFile=Yii::$app->getRuntimePath()."/logs/err.log";
            $log->messages[]=[
                $err_msg,
                1,
                'application',
                microtime(true)
            ];

            $log->export();

            //save to DB
            AppLogService::addErrorLog(Yii::$app->id,$err_msg);
        }

        return '错误提示：<br><br>'.$err_msg;
    }
}
