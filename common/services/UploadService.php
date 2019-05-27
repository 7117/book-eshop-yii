<?php
namespace app\common\services;

use Yii;

class UploadService extends BaseService
{
    public static function uploadByFile ($file_name,$file_path,$bucket) {

        if ( !$file_name ) {
            return self::_err("参数文件名是必须的");
        }

        if ( !$file_path || !file_exists( $file_path )) {
            return self::_err("参数文件名是必须的");
        }

        $upload_config = Yii::$app->params['upload'];

        if (!isset ($upload_config[$bucket])) {
            return self::_err("指定参数篮子错误");
        }

        $tmp_file_extend = explode(".",$file_name);
        $file_type = strtolower( end( $tmp_file_extend ) );

        $hash_key = md5 (file_get_contents($file_path));
        $upload_dir_path = UtilService::getRootPath() . "/web" . $upload_config[ $bucket ].'/';
        $folder_name = date ("Ymd");
        $upload_dir = $upload_dir_path.$folder_name;

        if ( ! file_exists( $upload_dir ) ){
            mkdir( $upload_dir,0777);
            chmod( $upload_dir,0777);
        }

        $upload_file_name = $folder_name."/".$hash_key.".{$file_type}";

        if ( is_uploaded_file( $file_path ) ){
            move_uploaded_file($file_path,$upload_dir_path.$upload_file_name);
        }else{
            file_put_contents( $upload_dir_path.$upload_file_name,file_get_contents( $file_path ) );
        }

        return [
            'code' => 200,
            'path' => $upload_file_name,
            'prefix' => $upload_config[ $bucket ] ."/"
        ];
    }


}