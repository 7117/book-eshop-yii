<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class UserController extends Controller
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionLogin()
    {
        $this->layout=false;
        return $this->render('login');
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }

    public function actionResetPwd()
    {
        return $this->render('reset_pwd');
    }
}
