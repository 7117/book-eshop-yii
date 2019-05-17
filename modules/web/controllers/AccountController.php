<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class AccountController extends Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

    public function actionSet()
    {
        $this->layout=false;
        return $this->render('set');
    }

    public function actionInfo()
    {
        $this->layout=false;
        return $this->render('info');
    }
}
