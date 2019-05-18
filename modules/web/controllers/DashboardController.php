<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class DashboardController extends Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }
}
