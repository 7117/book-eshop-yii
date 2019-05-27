<?php

namespace app\modules\web\controllers;

use app\common\services\AppLogService;
use app\common\services\UrlService;
use app\models\log\AppAccessLog;
use Yii;
use app\models\User;
use app\common\services\ConstantMapService;
use app\modules\web\controllers\common\BaseController;


class AccountController extends BaseController
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionIndex()
    {
        $status = intval($this->get("status",ConstantMapService::$status_default));
        $mix_kw = trim($this->get("mix_kw",""));
        $p = intval($this->get("p",1));

        $query = User::find();

        if ($status > ConstantMapService::$status_default) {
            $query->andWhere(['uid' => $status]);
        }


        if ($mix_kw){
            $where_nickname = ['LIKE','nickname','%'.$mix_kw.'%',false];
            $where_mobile = ['LIKE','mobile','%'.$mix_kw.'%',false];
            $query->andWhere(['OR',$where_nickname,$where_mobile]);
        }

        //分页
        $page_size = 2;
        $total_res_count = $query->count();
        $total_page = ceil($total_res_count/$page_size);

        $list = $query->orderBy(['uid' => SORT_DESC ])->offset(($p-1)* $page_size)->limit($page_size)->all();


        return $this->render('index',[
            'list' => $list,
            'status_mapping' => ConstantMapService::$status_mapping,
            'search_conditions' => [
                'mix_kw' => $mix_kw,
                'status' => $status,
                'p' => $p,
            ],
            'pages' => [
               'total_count' =>$total_res_count,
               'page_size' => $page_size,
               'total_page' =>$total_page,
               'p' => $p,
            ],
        ]);
    }

    public function actionSet()
    {
        if (Yii::$app->request->isGet){
            return $this->render("set");
        }

        $nickname = trim($this->post("nickname",""));
        $mobile = trim($this->post("mobile",""));
        $email = trim($this->post("email",""));
        $login_name = trim($this->post("login_name",""));
        $login_pwd = trim($this->post("login_pwd",""));

        if(mb_strlen($nickname,"utf-8") < 1 ){
            return $this->renderJson([],"姓名不对",-1);
        }
        if(mb_strlen($mobile,"utf-8") < 1 ){
            return $this->renderJson([],"手机不对",-1);
        }
        if(mb_strlen($email,"utf-8") < 1 ){
            return $this->renderJson([],"邮箱不对",-1);
        }
        if(mb_strlen($login_name,"utf-8") < 1 ){
            return $this->renderJson([],"登录名不对",-1);
        }
        if(mb_strlen($login_pwd,"utf-8") < 1 ){
            return $this->renderJson([],"密码不对",-1);
        }

        $is_exist = User::find()->where(['login_name' => $login_name])->count();

        if ($is_exist) {
            return $this->renderJson([],"登录名已经存在",-1);
        }

        $user = new User();
        $user->nickname = $nickname;
        $user->mobile = $mobile;
        $user->email = $email;
        $user->avatar = ConstantMapService::$default_avatar;
        $user->login_name = $login_name;
        $user->login_pwd = $login_pwd;
        $user->setSalt();
        $user->setPassword($login_pwd);
        $user->updated_time = date("Y-m-d H:i:s");
        $user->created_time = date("Y-m-d H:i:s");

        $user->save();

        return $this->renderJson([],"操作成功");
    }

    public function actionInfo()
    {
        $id = intval($this->get("id",0));
        $back_url = UrlService::buildNullUrl("/account/index");

        if (!$id) {
            return $this->redirect($back_url);
        }

        $user_info = User::find()->where(['uid'=>$id])->one();

        if ( !$user_info ) {
            return $this->redirect($back_url);
        }

        $access_list = AppAccessLog::find()->where(['uid' => $user_info['uid']])->orderBy(['id'=>SORT_DESC])->limit(10)->all();

        return $this->render('info',[
            'user_info' => $user_info,
            'access_list' => $access_list
        ]);
    }

    public function actionOps()
    {
        if (!Yii::$app->request->isPost){
            return $this->renderJson([],"系统忙",-1);
        }

        $uid = intval( $this->post("uid",0));
        $act = trim( $this->post("act",""));
        if (!$uid) {
            return $this->renderJson([],"输入账号",-1);
        }

        if (!in_array($act,["remove","recover"])){
            return $this->renderJson([],"操作有误",-1);
        }

        $user_info = User::find()->where(['uid' => $uid])->one();
        if(!$user_info){
            return $this->renderJson([],"无此账号",-1);
        }

        switch ($act) {
            case "remove":
                $user_info->status = 0;
                break;
            case "recover":
                $user_info->status = 1;
                break;
        }

        $user_info->updated_time = date("Y-m-d H:i:s");
        $user_info->update();

        return $this->renderJson([],"操作完成");
    }
}
