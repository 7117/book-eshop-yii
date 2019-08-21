<?php
use \app\common\services\UrlService;
?>

<div class="col-md-6">
    <div class="ibox-content">
        <form class="m-t" role="form" action="<?php UrlService::buildWebUrl("/user/login")?>" method="post">
            <h2 class="font-bold text-center"><?php echo Yii::$app->params['title'];?></h2>
            <div class="form-group text-center">
            </div>
            <div class="form-group">
                <input type="text" name="login_name" class="form-control" placeholder="请输入登录用户名">
            </div>
            <div class="form-group">
                <input type="password" name="login_pwd" class="form-control" placeholder="请输入登录密码">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">登录</button>
        </form>
    </div>
</div>
