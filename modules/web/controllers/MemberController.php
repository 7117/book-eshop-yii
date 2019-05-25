<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\modules\web\controllers\common\BaseController;
use app\models\member\Member;
use Yii;

class MemberController extends BaseController
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionIndex()
    {
        $mix_kw = trim( $this->get("mix_kw","") );
        $status = intval( $this->get("status",ConstantMapService::$status_default) );
        $p = intval( $this->get("p",1) );
        $p = ( $p > 0 ) ? $p : 1;

        $query = Member::find();

        if( $mix_kw ){
            $where_nickname = [ 'LIKE','nickname','%'.strtr($mix_kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $where_mobile = [ 'LIKE','mobile','%'.strtr($mix_kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query->andWhere([ 'OR',$where_nickname,$where_mobile ]);
        }

        if ($status > ConstantMapService::$status_default ){
            $query->andWhere(['status' => $status ]);
        }

        $page_size = 1;
        $total_res_count = $query->count();
        $total_page = ceil($total_res_count / $page_size );
        $offset = ($p - 1)*$page_size;

        $list = $query -> orderBy(['id' => SORT_DESC ])
            ->offset($offset)
            ->limit($page_size)
            ->all();

        return $this->render('index',[
            'list' => $list,
            'pages' => [
                'total_count' => $total_res_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p
            ],
            'status_mapping' => ConstantMapService::$status_mapping,
            'search_conditions' => [
                'mix_km' => $mix_kw,
                'p' => $p,
                'status' => $status,
            ]
        ]);
    }

    public function actionInfo()
    {
        $id = intval(trim($this->get('id')));

        $info = Member::find()->where(['id' => $id ])->one();

        if ( $info ) {
            return $this->render('info',[
                'info' => $info
            ]);
        }

    }

    public function actionSet()
    {
        if (Yii::$app->request->isGet){
            $id = intval($this->get('id'));
            $info = [];
            if ( $id ){
                $info = Member::find()->where(['id' => $id])->one();
            }
            return $this->render("set",[
                'info' => $info
            ]);
        }

        $id = intval($this->post('id'));
        $nickname = trim($this->post('nickname'));
        $mobile = trim($this->post('mobile'));
        $data = date("Y-m-d H:i:s");

        if (mb_strlen($nickname,'utf-8') <1 ){
            return $this->renderJSON([],"name error",-1);
        }

        if (mb_strlen($mobile,'utf-8') < 1 ){
            return $this->renderJSON([],"mobile error",-1);
        }

        $info = Member::find()->where(['id' => $id])->one();

        if ( !$info ) {
            $info = new Member();
            $info->status = 1;
            $info->avatar = ConstantMapService::$default_avatar;
            $info->created_time = $data;
        }

        $info->nickname = $nickname;
        $info->mobile = $mobile;
        $info->updated_time = $data;
        $info->save();

        return $this->renderJSON([],"操作成功",200);

    }

    public function actionRemove(){

        if ( Yii::$app->request->isGet ){
            return $this->renderJSON([],ConstantMapService::$status_default,-1);
        }

        $id = trim($this->post('id'));

        $info = Member::find()->where(['id' => $id])->one();

        if ( $info ) {
            $info->status = 0;
            $info->updated_time = date("Y-m-d H:i:s");
            $info->update();
            return $this->renderJSON([],"删除完成",200);
        }else{
            return $this->renderJSON([],"账户不存在",-1);
        }
    }

    public function actionRecover(){

        if ( Yii::$app->request->isGet ){
            return $this->renderJSON([],ConstantMapService::$status_default,-1);
        }

        $id = trim($this->post('id'));

        $info = Member::find()->where(['id' => $id])->one();

        if ( $info ) {
            $info->status = 1;
            $info->updated_time = date("Y-m-d H:i:s");
            $info->update();
            return $this->renderJSON([],"恢复完成",200);
        }else{
            return $this->renderJSON([],"账户不存在",-1);
        }
    }


    public function actionComment()
    {
        return $this->render('comment');
    }

}
