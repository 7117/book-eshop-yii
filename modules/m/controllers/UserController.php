<?php

namespace app\modules\m\controllers;

use Yii;
use app\common\services\ConstantMapService;
use app\common\services\DataHelper;
use app\common\services\QueueListService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\City;
use app\models\member\Member;
use app\models\member\MemberAddress;
use app\models\member\MemberComments;
use app\models\member\MemberFav;
use app\models\member\OauthMemberBind;
use app\models\pay\PayOrderItem;
use app\models\sms\SmsCaptcha;
use app\modules\m\controllers\common\BaseController;
use app\common\services\AreaService;

class UserController extends BaseController
{
    public function actionIndex(){
        return $this->render('index',[
            'current_user' => $this->current_user
        ]);
    }

    public function actionBind(){
        if( \Yii::$app->request->isGet ){
            return $this->render( "bind" );
        }

        $mobile = trim( $this->post("mobile") );
        $img_captcha = trim( $this->post("img_captcha") );
        $captcha_code = trim( $this->post("captcha_code") );
        $date_now = date("Y-m-d H:i:s");

        $openid = $this->getCookie( $this->auth_cookie_current_openid );

        if( mb_strlen($mobile,"utf-8") < 1 || !preg_match("/^[1-9]\d{10}$/",$mobile) ){
            return $this->renderJSON([],"请输入符合要求的手机号码",-1);
        }
        if (mb_strlen( $img_captcha, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合要求的图像校验码", -1);
        }
        if (mb_strlen( $captcha_code, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合要求的手机验证码", -1);
        }
        if ( !SmsCaptcha::checkCaptcha($mobile, $captcha_code ) ) {
            return $this->renderJSON([], "请输入正确的手机验证码", -1);
        }

        $member_info = Member::find()->where([ 'mobile' => $mobile,'status' => 1 ])->one();

        if( !$member_info ){
            if( Member::findOne([ 'mobile' => $mobile]) ){
                $this->renderJSON([], "手机号码已注册，请直接使用手机号码登录", -1);
            }

            $model_member = new Member();
            $model_member->nickname = $mobile;
            $model_member->mobile = $mobile;
            $model_member->setSalt();
            $model_member->avatar = ConstantMapService::$default_avatar;
            $model_member->reg_ip = sprintf("%u",ip2long( UtilService::getIP() ) );
            $model_member->status = 1;
            $model_member->created_time = $model_member->updated_time = date("Y-m-d H:i:s");
            $model_member->save( 0 );
            $member_info = $model_member;
        }

        if ( !$member_info || !$member_info['status']) {
            return $this->renderJSON([], "您的账号已被禁止，请联系客服解决", -1);
        }


        if( $openid ){
            $bind_info = OauthMemberBind::find()->where([ 'member_id' => $member_info['id'],'openid' => $openid,'type' => ConstantMapService::$client_type_wechat  ])->one();

            if( !$bind_info ){
                $model_bind = new OauthMemberBind();
                $model_bind->member_id = $member_info['id'];
                $model_bind->type = ConstantMapService::$client_type_wechat;
                $model_bind->client_type = "weixin";
                $model_bind->openid = $openid;
                $model_bind->unionid = '';
                $model_bind->extra = '';
                $model_bind->updated_time = $date_now;
                $model_bind->created_time = $date_now;
                $model_bind->save( 0 );

                QueueListService::addQueue( "bind",[
                    'member_id' => $member_info['id'],
                    'type' => 1,
                    'openid' => $model_bind->openid
                ] );
            }
        }

        if( UtilService::isWechat() && $member_info['nickname']  == $member_info['mobile'] ){
            return $this->renderJSON([ 'url' => UrlService::buildMUrl( "/oauth/login",[ 'scope' => 'snsapi_userinfo' ] )  ],"绑定成功");
        }
        $this->setLoginStatus( $member_info );
        return $this->renderJSON([ 'url' => UrlService::buildMUrl( "/default/index" )  ],"绑定成功");
    }

    public function actionCart ()
    {
        return $this->render('cart');
    }

    public function actionOrder ()
    {
        return $this->render('order');
    }

    public function actionFav()
    {
        return $this->render('fav');
    }

    public function actionComment(){
        $list = MemberComments::find()->where([ 'member_id' => $this->current_user['id'] ])
            ->orderBy([ 'id' => SORT_DESC ])->asArray()->all();

        return $this->render('comment',[
            'list' => $list
        ]);
    }

    public function actionComment_set(){
        if( \Yii::$app->request->isGet ){
            $pay_order_id = intval( $this->get("pay_order_id",0) );
            $book_id = intval( $this->get("book_id",0) );
            $pay_order_info = PayOrder::findOne([ 'id' => $pay_order_id,'status' => 1,'express_status' => 1 ]);
            $reback_url = UrlService::buildMUrl("/user/index");
            if( !$pay_order_info ){
                return $this->redirect( $reback_url );
            }

            $pay_order_item_info  = PayOrderItem::findOne([ 'pay_order_id' => $pay_order_id,'target_id' => $book_id ]);
            if( !$pay_order_item_info ){
                return $this->renderJSON( [],ConstantMapService::$default_syserror,-1 );
            }

            if(  $pay_order_item_info['comment_status'] ){
                return $this->renderJS( "您已经评论过啦，不能重复评论",$reback_url );
            }


            return $this->render('comment_set',[
                'pay_order_info' => $pay_order_info,
                'book_id' => $book_id
            ]);
        }

        $pay_order_id = intval( $this->post("pay_order_id",0) );
        $book_id = intval( $this->post("book_id",0) );
        $score = intval( $this->post("score",0) );
        $content = trim( $this->post('content','') );
        $date_now  = date("Y-m-d H:i:s");

        if( $score <= 0 ){
            return $this->renderJSON([],"请打分",-1);
        }

        if( mb_strlen( $content,"utf-8" ) < 3 ){
            return $this->renderJSON([],"请输入符合要求的评论内容",-1);
        }

        $pay_order_info = PayOrder::findOne([ 'id' => $pay_order_id,'status' => 1,'express_status' => 1 ]);
        if( !$pay_order_info ){
            return $this->renderJSON( [],ConstantMapService::$default_syserror,-1 );
        }

        $pay_order_item_info  = PayOrderItem::findOne([ 'pay_order_id' => $pay_order_id,'target_id' => $book_id ]);
        if( !$pay_order_item_info ){
            return $this->renderJSON( [],ConstantMapService::$default_syserror,-1 );
        }

        if(  $pay_order_item_info['comment_status'] ){
            return $this->renderJSON( [],"您已经评论过啦，不能重复评论",-1 );
        }

        $book_info = Book::findOne([ 'id' => $book_id ]);
        if( !$book_info ){
            return $this->renderJSON( [],ConstantMapService::$default_syserror,-1 );
        }

        $model_comment = new MemberComments();
        $model_comment->member_id = $this->current_user['id'];
        $model_comment->book_id = $book_id;
        $model_comment->pay_order_id = $pay_order_id;
        $model_comment->score = $score * 2;
        $model_comment->content = $content;
        $model_comment->created_time = $date_now;
        $model_comment->save( 0 );

        $pay_order_item_info->comment_status = 1;
        $pay_order_item_info->update( 0 );

        $book_info->comment_count += 1;
        $book_info->update( 0 );


        return $this->renderJSON([],"评论成功");
    }

    public function actionAddress()
    {
        return $this->render('address');
    }

    public function actionAddress_set()
    {
        return $this->render('address_set');
    }
}
