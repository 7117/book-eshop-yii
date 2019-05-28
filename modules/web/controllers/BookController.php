<?php

namespace app\modules\web\controllers;

use Yii;
use app\common\services\ConstantMapService;
use app\models\book\BookCat;
use app\modules\web\controllers\common\BaseController;

class BookController extends BaseController
{
    public function __construct($id,$module,array $config=[]){
        parent::__construct($id,$module,$config);
        $this->layout="main";
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCat_set()
    {

        if (Yii::$app->request->isGet) {
            $id = intval(trim($this->get('id')));
            $info = BookCat::find()->where(['id' => $id])->one();

            return $this->render('cat_set',[
                'info' => $info
            ]);
        }

        $id = intval(trim($this->post('id')));
        $name = trim($this->post('name'));
        $weight = intval(trim($this->post('weight')));
        $now = date("Y-m-d H:i:s",time());

        if (mb_strlen($name,"utf-8") <1 ) {
            return $this->renderJSON([],"名字不对",-1);
        }

        $is_has = BookCat::find()->where(['name' => $name])->andWhere(['!=','id',$id])->one();

        if ($is_has) {
            return $this->renderJSON([],"已经存在",-1);
        }

        $info = BookCat::find()->where(['id' => $id ])->one();

        if ( !$info ){
            $info = new BookCat();
            $info->created_time = $now;
        }

        $info->name = $name;
        $info->weight = $weight;
        $info->updated_time = $now;
        $info->save();

        return $this->renderJSON([],'操作成功',200);

    }

    public function actionInfo()
    {
        return $this->render('info');
    }

    public function actionImages()
    {
        return $this->render('images');
    }

    public function actionCat()
    {
        $status = intval ( $this->get("status",ConstantMapService::$status_default) );
        $query = BookCat::find();

        if ( $status > ConstantMapService::$status_default ) {
            $query->where(['status' => $status]);
        }

        $list = $query->orderBy(['weight'=>SORT_DESC,'id'=>SORT_DESC])->all();

        return $this->render('cat',[
            'list'=>$list,
            'status_mapping'=>ConstantMapService::$status_mapping,
            'search_conditions' => [
                'status' => $status
            ]
        ]);

    }

    public function actionRemove(){
        $id = intval(trim($this->post('id')));
        $now = date("Y-m-d H:i:s",time());

        $info = BookCat::find()->where(['id' => $id ])->one();

        if ( !$info ) {
            return $this->renderJSON([],"不存在",-1);
        }

        $info->status = 0;
        $info->updated_time = $now;
        $info->update();

        return $this->renderJSON([],"操作完成",200);
    }

    public function actionRecover(){
        $id = intval(trim($this->post('id')));
        $now = date("Y-m-d H:i:s",time());

        $info = BookCat::find()->where(['id' => $id ])->one();

        if (!$info){
            return $this->renderJSON([],"不存在",-1);
        }

        $info->status = 1;
        $info->updated_time = $now;

        $info->update();

        return $this->renderJSON([],"操作完成",200);

    }
}
