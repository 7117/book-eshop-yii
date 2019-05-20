<?php

namespace app\modules\web\controllers;

use Yii;
use app\models\User;
use app\common\services\UrlService;
use app\modules\web\controllers\common\BaseController;

class UserController extends BaseController
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionLogin()
    {
        if(Yii::$app->request->isGet){
            $this->layout = "user";
            return $this->render('login');
        }

        $login_name = trim($this->post("login_name",""));
        $login_pwd = trim($this->post("login_pwd",""));

        if(!$login_name || !$login_pwd){
            return $this->renderJs('请输入正确的用户名与密码',UrlService::buildWebUrl("/user/login/"));
        }

        $user_info = User::find()->where(['login_name' => $login_name])->one();
        if(!$user_info){
            return $this->renderJs('请输入正确的用户名与密码',UrlService::buildWebUrl("/user/login/"));
        }

        //加密算法：md5($login_pwd + md5($user_info(login_salt)))
        $auth_pwd = md5($login_pwd.md5($user_info['login_salt']));
        if($auth_pwd != $user_info['login_pwd']){
            return $this->renderJs('请输入正确的用户名与密码',UrlService::buildWebUrl("/user/login/"));
        }

        //加密字符串."#".uid   加密字符串 = md5(login_name + login_pwd + login_salt)
        $this->setLoginStatus($user_info);

        return $this->redirect(UrlService::buildWebUrl("/dashboard/index"));

    }

    public function actionLogout(){
        $this->removeCookie($this->auth_cookie_name);
        return $this->redirect(UrlService::buildWebUrl("/user/login"));
    }

    public function actionEdit()
    {
        if(Yii::$app->request->isGet){
            return $this->render("edit",['user_info' => $this->current_user]);
        }

        $nickname = trim($this->post("nickname",""));
        $email = trim($this->post("email",""));

        if (mb_strlen($nickname,"utf-8") <1 ) {
            return $this->renderJSON([],"请输入合法姓名",-1);
        }

        if (mb_strlen($email,"utf-8") < 1 ) {
            return $this->renderJSON([],"请输入合法邮箱",-1);
        }

        $user_info = $this->current_user;
        $user_info->nickname = $nickname;
        $user_info->email = $email;
        $user_info->updated_time = date("Y-m-d H:i:s");

        $user_info->update(0);

        return $this->renderJSON([],$msg="操作成功");
    }

    public function actionResetPwd()
    {
        return $this->render('reset_pwd');
    }
}
