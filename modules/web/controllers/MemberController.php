<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\modules\web\controllers\common\BaseController;
use app\models\member\Member;

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
        return $this->render('info');
    }

    public function actionSet()
    {
        return $this->render('set');
    }

    public function actionComment()
    {
        return $this->render('comment');
    }

}
