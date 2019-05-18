<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class StatController extends Controller
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProduct()
    {
        return $this->render('product');
    }

    public function actionMember()
    {
        return $this->render('member');
    }

    public function actionShare()
    {
        return $this->render('share');
    }

}
