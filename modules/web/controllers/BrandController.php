<?php

namespace app\modules\web\controllers;

use Yii;
use app\models\brand\BrandSetting;
use app\modules\web\controllers\common\BaseController;

/**
 * Default controller for the `web` module
 */
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

    // 编辑
    public function actionSet()
    {
        if ( Yii::$app->request->isGet ) {
            var_dump("ssss");
            $info =BrandSetting::find()->one();
            return $this->render('set',['info' => $info ]);
        }

        $name = trim($this->post("name",""));
        $mobile = trim($this->post("mobile",""));
        $address = trim($this->post("address",""));
        $description = trim($this->post("description",""));
        $date = date("Y-m-d H:i:s");

        if (mb_strlen($name,"utf-8") < 1 ) {
            return $this->renderJson([],"名字错误",-1);
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
        $brand->mobile = $mobile;
        $brand->address = $address;
        $brand->description = $description;
        $brand->updated_time = $date;
        $brand->save();

        return $this->renderJson([],"操作完成",200);

    }

    public function actionImages()
    {
        return $this->render('images');
    }
}
