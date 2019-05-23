<?php

namespace app\modules\m\controllers;

use app\modules\m\controllers\common\BaseController;

class ProductController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInfo()
    {
        return $this->render('info');
    }

    public function actionOrder()
    {
        return $this->render('order');
    }
}
