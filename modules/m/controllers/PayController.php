<?php

namespace app\modules\m\controllers;

use yii\web\Controller;

/**
 * Default controller for the `m` module
 */
class PayController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionBuy ()
    {
        $this->layout=false;
        return $this->render('buy');
    }
}
