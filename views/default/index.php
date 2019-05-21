<?php
use app\common\services\UrlService;
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>微信图书商城</title>
    <link href="/css/www/app.css" rel="stylesheet"></head>
<body>
<div class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse pull-left">
            <ul class="nav navbar-nav ">
                <li><a href="<?=UrlService::buildMUrl("")?>">前台</a></li>
                <li><a href="<?=UrlService::buildWebUrl('/user/login')?>">后台</a></li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>