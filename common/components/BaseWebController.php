<?php
namespace app\common\components;

use Yii;
use yii\web\Controller;

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
        $cookies = Yii::$app->request->cookies;
        return $cookies->getValue($name,$default_val);
    }

    public function removeCookie($name){
        $cookies = Yii::$app->response->cookies;
        $cookies->remove($name);
    }

    protected function renderJSON($data=[], $msg ="ok", $code = 200)
    {
        header('Content-type: application/json');

        print_r(json_encode([
            "code" => $code,
            "msg"   =>  $msg,
            "data"  =>  $data,
            "req_id" =>  uniqid()
        ]));

        return Yii::$app->end();
    }
    
    public function renderJs($msg,$url){
        return $this->renderPartial("@app/views/common/js",['msg' => $msg,'url' => $url]);
    }
}