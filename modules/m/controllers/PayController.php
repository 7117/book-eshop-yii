<?php

namespace app\modules\m\controllers;
use app\common\services\ConstantMapService;
use app\common\services\PayOrderService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\weixin\PayApiService;
use app\models\member\OauthMemberBind;
use app\models\pay\PayOrder;
use app\modules\m\controllers\common\BaseController;
use yii\log\FileTarget;

class PayController extends BaseController
{
    //展示
    public function actionBuy(){

        $pay_order_id = intval( $this->get("pay_order_id",0) );
        $reback_url = UrlService::buildMUrl("/user/index");
        if( !$pay_order_id ){
            return $this->redirect( $reback_url );
        }

        $pay_order_info = PayOrder::find()->where([ 'member_id' => $this->current_user['id'],'id' => $pay_order_id,'status' => -8 ])->one();
        if( !$pay_order_info ){
            return $this->redirect( $reback_url );
        }

        return $this->render('buy',[
            'pay_order_info' => $pay_order_info
        ]);

    }

    //预支付
    public function actionPrepare(){
        $pay_order_id = intval( $this->post("pay_order_id",0) );
        if( !$pay_order_id ){
            return $this->renderJSON( [],"系统繁忙，请稍后再试",-1 );
        }

        if( !UtilService::isWechat() ) {
            return $this->renderJSON([],"仅支持微信支付，请将页面链接粘贴至微信打开",-1);
        }

        $pay_order_info = PayOrder::find()->where([ 'member_id' => $this->current_user['id'],'id' => $pay_order_id,'status' => -8 ])->one();
        if( !$pay_order_info ){
            return $this->renderJSON( [],ConstantMapService::$default_syserror,-1 );
        }

        $openid = $this->getOpenId();
        if( !$openid  ){
            $err_msg = "购买卡前请绑微信";
            return $this->renderJSON([],$err_msg,-1);
        }

        $config_weixin = \Yii::$app->params['weixin'];
        $wx_target = new PayApiService( $config_weixin );

        $notify_url = $config_weixin['pay']['notify_url']['m'];

        //设置必须参数
        $wx_target->setParameter("appid",$config_weixin['appid']);
        $wx_target->setParameter("mch_id",$config_weixin['pay']['mch_id']);
        $wx_target->setParameter("openid",$openid);
        $wx_target->setParameter("body",$pay_order_info['note']);
        $wx_target->setParameter("out_trade_no",$pay_order_info['order_sn'] );
        $wx_target->setParameter("total_fee",$pay_order_info['pay_price'] * 100 );
        $wx_target->setParameter("notify_url",UrlService::buildMUrl( $notify_url ) );
        $wx_target->setParameter("trade_type","JSAPI");

        //获取返回信息
        $prepayInfo = $wx_target->getPrepayInfo();
        if(!$prepayInfo){
            return false;
        }

        $wx_target->setPrepayId($prepayInfo['prepay_id']);
        return $this->renderJSON( $wx_target->getParameters() );
    }

}
