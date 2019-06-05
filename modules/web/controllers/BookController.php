<?php

namespace app\modules\web\controllers;

use Yii;
use app\common\services\book\BookService;
use app\models\book\Images;
use app\models\book\Book;
use app\common\services\UrlService;
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
        $status = trim($this->get("status",-1));
        $cat_id = trim($this->get("cat_id",0));
        $mix_kw = trim($this->get("mix_kw",""));
        $p = intval(trim($this->get("p","")));
        $p = $p ? $p : 1;

        $query = Book::find();

        if ($status > -1 ) {
            $query->andWhere(['status' => $status]);
        }

        if ($cat_id > 0 ){
            $query->andWhere(['cat_id' => $cat_id]);
        }

        if ( $mix_kw ) {
            $where_name = ['LIKE','name','%'.$mix_kw.'%',false];
            $where_tags = ['LIKE','tags','%'.$mix_kw.'%',false];
            $query->andWhere(['OR',$where_name,$where_tags]);
        }

        $page_size = 2;
        $total_count = $query->count();
        $total_page = ceil($total_count / $page_size);

        $list = $query->orderBy(['id' => SORT_DESC])
            ->offset( ($p-1)*$page_size )
            ->limit($page_size)
            ->all();


        $cat_mapping = BookCat::find()->orderBy(['id' => SORT_DESC])->indexBy("id")->all();

        return $this->render("index",[
            'list' => $list,
            'cat_mapping' => $cat_mapping,
            'status' => ConstantMapService::$status_mapping,
            'pages' => [
                'total_page' => $total_page,
                'total_count' => $total_count,
                'page_size' => $page_size,
                'p' => $p,
            ]
        ]);

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
        $id = intval( trim($this->get('id')));

        if ($id){
            $info = Book::find()->where(['id' => $id ])->one();
        }

        return $this->render('info',[
            'info' => $info
        ]);
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

    public function actionBook_remove(){
        $id = intval(trim($this->post('id')));
        $now = date("Y-m-d H:i:s",time());

        $info = Book::find()->where(['id' => $id ])->one();

        if ( !$info ) {
            return $this->renderJSON([],"不存在",-1);
        }

        $info->status = 0;
        $info->updated_time = $now;
        $info->update();

        return $this->renderJSON([],"操作完成",200);
    }

    public function actionBook_recover(){
        $id = intval(trim($this->post('id')));
        $now = date("Y-m-d H:i:s",time());

        $info = Book::find()->where(['id' => $id ])->one();

        if (!$info){
            return $this->renderJSON([],"不存在",-1);
        }

        $info->status = 1;
        $info->updated_time = $now;

        $info->update();

        return $this->renderJSON([],"操作完成",200);

    }

    public function actionSet(){
        if( Yii::$app->request->isGet ) {
            $id = intval( $this->get("id", 0) );
            $info = [];
            if( $id ){
                $info = Book::find()->where([ 'id' => $id ])->one();
            }

            $cat_list = BookCat::find()->orderBy([ 'id' => SORT_DESC ])->all();
            return $this->render('set',[
                'cat_list' => $cat_list,
                'info' => $info
            ]);
        }

        $id = intval( $this->post("id",0) );
        $cat_id = intval( $this->post("cat_id",0) );
        $name = trim( $this->post("name","") );
        $price = floatval( $this->post("price",0) );
        $main_image = trim( $this->post("main_image","") );
        $summary = trim( $this->post("summary","") );
        $stock = intval( $this->post("stock",0) );
        $tags = trim( $this->post("tags","") );
        $date_now = date("Y-m-d H:i:s");

        if( !$cat_id ){
            return $this->renderJSON([],"请输入图书分类",-1);
        }

        if( mb_strlen( $name,"utf-8" ) < 1 ){
            return $this->renderJSON([],"请输入符合规范的图书名称",-1);
        }

        if( $price <= 0  ){
            return $this->renderJSON([],"请输入符合规范的图书售卖价格",-1);
        }

        if( mb_strlen( $main_image ,"utf-8") < 3 ){
            return $this->renderJSON([],"请上传封面图",-1);
        }

        if( mb_strlen( $summary,"utf-8" ) < 10 ){
            return $this->renderJSON([],"请输入图书描述，并不能少于10个字符",-1);
        }

        if( $stock < 1 ){
            return $this->renderJSON([],"请输入符合规范的库存量",-1);
        }

        if( mb_strlen( $tags,"utf-8" ) < 1 ){
            return $this->renderJSON([],"请输入图书标签，便于搜索",-1);
        }


        $info = [];
        if( $id ){
            $info = Book::findOne(['id' => $id]);
        }
        if( $info ){
            $model_book = $info;
        }else{
            $model_book = new Book();
            $model_book->status = 1;
            $model_book->created_time = $date_now;
        }

        $before_stock = $model_book->stock;

        $model_book->cat_id = $cat_id;
        $model_book->name = $name;
        $model_book->price = $price;
        $model_book->main_image = $main_image;
        $model_book->summary = $summary;
        $model_book->stock = $stock;
        $model_book->tags = $tags;
        $model_book->updated_time = $date_now;
        if( $model_book->save() ){
            BookService::setStockChangeLog( $model_book->id,( $model_book->stock - $before_stock ) );
        }

        return $this->renderJSON([],"操作成功",200);
    }

    public function actionImages()
    {
        $p = intval( $this->get("p",1) );
        $p = ( $p > 0 )?$p:1;

        $bucket = "book";
        $query = Images::find()->where([ 'bucket' => $bucket ]);

        $page_size = 1;
        $total_res_count = $query->count();
        $total_page = ceil( $total_res_count / $page_size );


        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset( ( $p - 1 ) * $page_size )
            ->limit($page_size)
            ->all( );

        $data = [];
        if( $list ){
            foreach ( $list as $_item ){
                $data[] = [
                    'url' => UrlService::buildPicUrl( $bucket,$_item['file_key'] )
                ];
            }
        }
        return $this->render("images",[
            'list' => $data,
            'pages' => [
                'total_count' => $total_res_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p
            ]
        ]);
    }

}
