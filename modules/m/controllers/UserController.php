<?php

namespace app\modules\m\controllers;

use app\modules\m\controllers\common\BaseController;

class UserController extends BaseController
{
    public function actionIndex ()
    {
        return $this->render('index');
    }

    public function actionBind ()
    {
        return $this->render('bind');
    }

    public function actionCart ()
    {
        return $this->render('cart');
    }

    public function actionOrder ()
    {
        return $this->render('order');
    }

    public function actionFav()
    {
        return $this->render('fav');
    }

    public function actionComment()
    {
        return $this->render('comment');
    }

    public function actionComment_set()
    {
        return $this->render('comment_set');
    }

    public function actionAddress()
    {
        return $this->render('address');
    }

    public function actionAddress_set()
    {
        return $this->render('address_set');
    }
}
