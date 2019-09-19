<?php
use \app\common\services\UrlService;
use \app\common\services\UtilService;
\app\assets\MAsset::register($this);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <title><?=Yii::$app->params['title'];?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div style="min-height: 500px;">
    <?=$content;?>
</div>
<div class="copyright clearfix">
    <?php if( isset( $this->params['current_user'] ) ):?>
        <p class="name">欢迎您，<?=UtilService::encode( $this->params['current_user']["nickname"] );?></p>
    <?php endif;?>
</div>
<?php if( !\Yii::$app->view->params['menu_hide'] ):?>
    <div class="footer_fixed clearfix">
        <span><a href="<?=UrlService::buildMUrl("/default/index");?>" class="default"><i class="home_icon"></i><b>首页</b></a></span>
        <span><a href="<?=UrlService::buildMUrl("/product/index");?>" class="product"><i class="store_icon"></i><b>图书</b></a></span>

        <?php if( isset( $this->params['current_user'] ) ):?>
            <span><a href="<?=UrlService::buildMUrl("/user/index");?>" class="user"><i class="member_icon"></i><b>我的</b></a></span>
        <?php else:?>
           <span><a href="<?=UrlService::buildMUrl("/user/bind");?>" class="user"><i class="member_icon"></i><b>我的</b></a></span>
        <?php endif;?>

    </div>
<?php endif;?>

<div class="layout_hide_wrap hidden">
    <input type="hidden" id="share_info" value='<?=Yii::$app->getView()->params['share_info'];?>'>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
