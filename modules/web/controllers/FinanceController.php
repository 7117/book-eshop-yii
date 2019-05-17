<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class FinanceController extends Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

    public function actionAccount()
    {
        $this->layout=false;
        return $this->render('account');
    }

    public function actionPay_Info()
    {
        $this->layout=false;
        return $this->render('pay_info');
    }
}
