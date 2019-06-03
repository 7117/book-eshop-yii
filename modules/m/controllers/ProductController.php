<?php

namespace app\modules\m\controllers;
use app\common\services\ConstantMapService;
use app\common\services\DataHelper;
use app\common\services\PayOrderService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\book\Book;
use app\models\City;
use app\models\member\MemberAddress;
use app\models\member\MemberCart;
use app\models\member\MemberFav;
use app\modules\m\controllers\common\BaseController;

class ProductController extends BaseController {

    public function actionIndex(){

        $kw = trim( $this->get("kw","") );
        $sort_field = trim( $this->get("sort_field","default") );
        $sort = trim( $this->get("sort","") );
        $sort = in_array(  $sort,['asc','desc'] )?$sort:'desc';

        $list = $this->getSearchData( );
        $data = [];

        //放进数组
        if( $list ){
            foreach( $list as $_item ){
                $data[] = [
                    'id' => $_item['id'],
                    'name' => UtilService::encode( $_item['name'] ),
                    'price' => UtilService::encode( $_item['price'] ),
                    'main_image_url' => UrlService::buildPicUrl("book",$_item['main_image'] ),
                    'month_count' => $_item['month_count']
                ];
            }
        }

        $search_conditions = [
            'kw' => $kw,
            'sort_field' => $sort_field,
            'sort' => $sort
        ];

        return $this->render("index",[
            'list' => $data,
            'search_conditions' => $search_conditions
        ]);
    }

    public function actionInfo(){
        $id = intval( $this->get("id",0) );
        $reback_url = UrlService::buildMUrl("/product/index");
        if( !$id ){
            return $this->redirect( $reback_url );
        }

        $info = Book::findOne([ 'id' => $id ]);
        if( !$info ){
            return $this->redirect( $reback_url );
        }

        $has_faved = false;
        if(  $this->current_user ){
            $has_faved = MemberFav::find()->where([ 'member_id' => $this->current_user['id'],'book_id' => $id ])->count();
        }


        return $this->render("info",[
            'info' => $info,
            'has_faved' => $has_faved
        ]);
    }

    public function actionOps(){
        $act = trim( $this->post("act","") );
        $book_id = intval( $this->post("book_id",0) );
        $book_info = Book::findOne([ 'id' => $book_id ]);
        if( !$book_info ){
            return $this->renderJSON( [],ConstantMapService::$default_syserror,-1 );
        }
        $book_info->view_count += 1;
        $book_info->update( 0 );
        return $this->renderJson( [] );
    }

    public function actionSearch(){
        $list = $this->getSearchData( );
        $data = [];
        if( $list ){
            foreach( $list as $_item ){
                $data[] = [
                    'id' => $_item['id'],
                    'name' => UtilService::encode( $_item['name'] ),
                    'price' => UtilService::encode( $_item['price'] ),
                    'main_image_url' => UrlService::buildPicUrl("book",$_item['main_image'] ),
                    'month_count' => $_item['month_count']
                ];
            }
        }
        return $this->renderJson( [ 'data' => $data ,'has_next' => ( count( $data ) == 4 )?1:0 ] );
    }

    private function getSearchData( $page_size = 4  ){
        $kw = trim( $this->get("kw","") );
        $sort_field = trim( $this->get("sort_field","default") );
        $sort = trim( $this->get("sort","") );
        $sort = in_array(  $sort,['asc','desc'] )?$sort:'desc';
        $p = intval( $this->get("p",1 ) );
        if( $p < 1 ){
            $p = 1;
        }

        $query = Book::find()->where([ 'status' => 1 ]);
        if( $kw ){
            $where_name = [ 'LIKE','name','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $where_tags = [ 'LIKE','tags','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query->andWhere([ 'OR',$where_name,$where_tags ]);
        }

        switch ( $sort_field ){
            case "view_count":
            case "month_count":
            case "price":
                $query->orderBy( [  $sort_field => ( $sort == "asc")?SORT_ASC:SORT_DESC,'id' => SORT_DESC ] );
                break;
            default:
                $query->orderBy([ 'id' => SORT_DESC ]);
                break;
        }
        $info = $query->offset(  ( $p - 1 ) * $page_size )
            ->limit( $page_size )
            ->all();

        return $info;
    }
}