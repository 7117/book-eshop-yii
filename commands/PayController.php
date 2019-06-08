<?php

namespace app\commands;

use app\common\services\book\BookService;
use app\common\services\PayOrderService;
use app\models\book\Book;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use Yii;

class PayController extends BaseController {

    public function actionProduct_stock(){
        $before_half_date = date("Y-m-d H:i:s",time() - 30 * 60 );
        $before_half_order_list = PayOrder::find()
            ->where( ['target_type' => 1 ,'status' => -8 ] )
            ->andWhere( ['<=',"created_time",$before_half_date] )
            ->all();

        if( !$before_half_date ){
            return $this->echoLog("no data");
        }

        foreach( $before_half_order_list as $_order_info ){
            PayOrderService::closeOrder( $_order_info['id'] );
        }

        return $this->echoLog("it's over");
    }

}
