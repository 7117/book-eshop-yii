<?php

namespace app\modules\web\controllers;

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
        return $this->render('set');
    }

    public function actionInfo()
    {
        return $this->render('info');
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
