<?php

namespace app\modules\web\controllers;

use app\common\services\UploadService;
use app\modules\web\controllers\common\BaseController;

class UploadController extends BaseController
{
   public $allow_file_type = ["jpg","gif","jpeg","gif","png"];

   public function actionPic()
   {
        $bucket = trim($this->post("bucket"));

        $callback = "window.parent.upload";

        if ( !$_FILES || !isset($_FILES['pic']) ){
            return "<script>{$callback}.error('请选择文件之后在提交')</script>";
        }

        $file_name = $_FILES['pic']['name'];
        $tmp_file_extend = explode(".",$file_name);

       if ( !in_array( strtolower ( end ( $tmp_file_extend ) ),$this->allow_file_type ) ) {
            return "<script>{$callback}.error('请上传指定类型的文件')</script>";
        }

       //todo upload function
        $ret = UploadService::uploadByFile( $file_name,$_FILES['pic']['tmp_name'],$bucket);

       if ( ! $ret ) {
           return "<script>{$callback}.error('".UploadService::getLastErrorMsg()."')</script>";
       } else {
           return "<script>{$callback}.success('{$ret['path']}')</script>";
       }
   }
}
