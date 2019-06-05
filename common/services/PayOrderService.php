<?php

namespace app\common\services;


use app\common\services\BaseService;
use app\common\services\book\BookService;
use app\common\services\ConstantMapService;
use app\models\book\Book;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderCallbackData;
use app\models\pay\PayOrderItem;
use \Exception;

class PayOrderService extends  BaseService {

    public static function createPayOrder( $member_id,$items = [],$params = []){
        $total_price = 0;
        $continue_cnt = 0;
        foreach( $items as $_item ){
            if( $_item['price'] < 0 ){
                $continue_cnt += 1;
                continue;
            }
            $total_price += $_item['price'];
        }

        if( $continue_cnt >= count( $items ) ){
            return self::_err( "商品items为空" );
        }

        $discount = isset( $params['discount'] )?$params['discount']:0;
        $total_price = sprintf("%.2f",$total_price);
        $discount = sprintf("%.2f",$discount);
        $pay_price = $total_price - $discount;
        $pay_price = sprintf("%.2f",$pay_price);

        $date_now = date("Y-m-d H:i:s");
        $connection =  PayOrder::getDb();
        $transaction = $connection->beginTransaction();
        try{
            $tmp_book_table_name = Book::tableName();
            $tmp_book_ids = array_column( $items,'target_id' );
            $tmp_sql = "SELECT id,stock FROM {$tmp_book_table_name} WHERE id in (".implode(",",$tmp_book_ids).") FOR UPDATE";
            $tmp_book_list = $connection->createCommand($tmp_sql)->queryAll();
            $tmp_book_unit_mapping = [];
            foreach( $tmp_book_list as $_book_info ){
                $tmp_book_unit_mapping[ $_book_info['id'] ] = $_book_info['stock'];
            }

            //订单表
            $model_pay_order = new PayOrder();
            $model_pay_order->order_sn = self::generate_order_sn();
            $model_pay_order->member_id = $member_id;
            $model_pay_order->pay_type = isset($params['pay_type'])?$params['pay_type']:0;
            $model_pay_order->pay_source = isset($params['pay_source'])?$params['pay_source']:0;
            $model_pay_order->target_type = isset($params['target_type'])?$params['target_type']:0;
            $model_pay_order->total_price = $total_price;
            $model_pay_order->discount = $discount;
            $model_pay_order->pay_price = $pay_price;
            $model_pay_order->note = isset($params['note'])?$params['note']:'';
            $model_pay_order->status = isset($params['status'])?$params['status']:-8;
            $model_pay_order->express_status = isset($params['express_status'])?$params['express_status']:-8;
            $model_pay_order->express_address_id = isset($params['express_address_id'])?$params['express_address_id']:0;
            $model_pay_order->pay_time = ConstantMapService::$default_time_stamps;
            $model_pay_order->updated_time = $date_now;
            $model_pay_order->created_time = $date_now;
            if( !$model_pay_order->save(0) ){
                throw new Exception("创建订单失败");
            }

            foreach($items as $_item){

                $tmp_left_stock = $tmp_book_unit_mapping[ $_item['target_id'] ];
                if( $tmp_left_stock < $_item['quantity'] ){
                    throw new Exception("购买书籍库存不够,目前剩余库存：{$tmp_left_stock},你购买:{$_item['quantity']}");
                }

                if( !Book::updateAll( [ 'stock' => $tmp_left_stock - $_item['quantity'] ],[ 'id' => $_item['target_id'] ] ) ){
                    throw new Exception("下单失败请重新下单");
                }

                //商品表  item一个商品
                $new_item = new PayOrderItem();
                $new_item->pay_order_id = $model_pay_order->id;
                $new_item->member_id = $member_id;
                $new_item->quantity  = $_item['quantity'];
                $new_item->price  = $_item['price'];
                $new_item->target_type  = $_item['target_type'];
                $new_item->target_id  = $_item['target_id'];
                $new_item->status = isset($_item['status'])?$_item['status']:1;

                if( isset( $_item['extra_data'] ) ){
                    $new_item->extra_data = json_encode( $_item['extra_data'] );
                }

                $new_item->note = isset( $_item['note'] )?$_item['note']:"";
                $new_item->updated_time = $date_now;
                $new_item->created_time  = $date_now;
                if( !$new_item->save(0) ){
                    throw new Exception("创建订单失败");
                }

                BookService::setStockChangeLog( $_item['target_id'],-$_item['quantity'],"在线购买" );

            }

            $transaction->commit();

            return [
                'id' => $model_pay_order->id,
                'order_sn' => $model_pay_order->order_sn,
                'pay_money' => $model_pay_order->pay_price,
            ];

        }catch (Exception $e) {
            $transaction->rollBack();
            return self::_err( $e->getMessage() );
        }
    }

    public static function generate_order_sn(){
        do{
            $sn = md5(microtime(1).rand(0,9999999).'!@%egg#$');

        }while( PayOrder::findOne( [ 'order_sn' => $sn ] ) );

        return $sn;
    }
}