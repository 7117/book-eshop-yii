<?php
namespace app\common\components;

use yii\base\Controller;

class BaseWebController extends Controller {

    public $enableCsrfValidation = false;

    public function get($key,$default_val="") {
        return Yii::$app->request->get($key,$default_val);
    }

    public function post($key,$default_val="") {
        return Yii::$app->request->post($key,$default_val);
    }

    public function setCookie($name,$value,$expire = 0){
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => $expire
        ]));
    }

    public function getCookie($name,$default_val=''){
        $cookies = Yii::$app->request->cookies();
        return $cookies->getValue($name,$default_val);
    }

    public function removeCookie($name){
        $cookies = Yii::$app->response->cookies;
        $cookies->remove($name);
    }

    public function renderJson($data = [],$msg = 'ok',$code = 200){
        header("Content-type:application/json");

        echo json_encode([
            "code" => $code,
            "msg" => $msg,
            "data" => $data,
            "req_id" => uniqid()
        ]);
    }
}