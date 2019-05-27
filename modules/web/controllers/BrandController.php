<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\models\brand\BrandImages;
use Yii;
use app\models\brand\BrandSetting;
use app\modules\web\controllers\common\BaseController;

class BrandController extends BaseController
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionInfo()
    {
        $info = BrandSetting::find()->one();

        return $this->render("info",["info" => $info]);
    }

    public function actionSet()
    {
        if ( Yii::$app->request->isGet ) {
            $info =BrandSetting::find()->one();
            return $this->render('set',['info' => $info ]);
        }

        $name = trim($this->post("name",""));
        $image_key = trim($this->post("image_key",""));
        $mobile = trim($this->post("mobile",""));
        $address = trim($this->post("address",""));
        $description = trim($this->post("description",""));
        $date = date("Y-m-d H:i:s");

        if (mb_strlen($name,"utf-8") < 1 ) {
            return $this->renderJson([],"名字错误",-1);
        }

        if (mb_strlen($image_key,"utf-8") < 1 ) {
            return $this->renderJson([],"图片错误",-1);
        }

        if (mb_strlen($mobile,"utf-8") < 1 ) {
            return $this->renderJson([],"号码错误",-1);
        }

        if (mb_strlen($address,"utf-8") < 1 ) {
            return $this->renderJson([],"地址错误",-1);
        }

        if (mb_strlen($description,"utf-8") < 1 ) {
            return $this->renderJson([],"描述错误",-1);
        }

        $info = BrandSetting::find()->one();

        if ( $info ) {
            $brand = $info;
        }else{
            $brand = new BrandSetting();
            $brand ->created_time = $date;
        }

        $brand->name = $name;
        $brand->logo = $image_key;
        $brand->mobile = $mobile;
        $brand->address = $address;
        $brand->description = $description;
        $brand->updated_time = $date;
        $brand->save();

        return $this->renderJson([],"操作完成",200);

    }

    public function actionImages()
    {
        $list = BrandImages::find()->orderBy(['id' => SORT_DESC])->all();

        return $this->render('images',[
            'list' => $list
        ]);
    }

    public function actionSetImage()
    {
        $image_key = trim( $this->post("image_key","") );

        if ( !$image_key ) {
            return $this->renderJSON([],"请上传图片",-1);
        }

        $total_count = BrandImages::find()->count();

        if ( $total_count >= 5 ) {
            return $this->renderJSON([],"最多五张",-1);
        }

        $model = new BrandImages();
        $model->image_key = $image_key;
        $model->created_time = date("Y-m-d H:i:s");
        $model->save();

        return $this->renderJSON([],"操作成功",200);

    }

    public function actionImageOps()
    {
        $id = $this->post('id',[]);

        if( !$id ) {
            return $this->renderJSON([],"选择要删除的图片",-1);
        }

        $info = BrandImages::find()->where(['id' => $id])->one();

        if ( !$info ) {
            return $this->renderJSON([],"图片不存在",-1);
        }

        $info->delete();

        return $this->renderJSON([],"删除完成",200);

    }


}
