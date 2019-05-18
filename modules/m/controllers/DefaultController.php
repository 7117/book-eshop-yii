<?php

namespace app\modules\m\controllers;

use yii\web\Controller;

/**
 * Default controller for the `m` module
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }
}
