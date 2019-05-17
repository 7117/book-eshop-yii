<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理后台</title>
    <link href="/css/web/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/web/style.css?ver=20170326180701" rel="stylesheet">
</head>

<body class="gray-bg">
<div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-6 text-center">

        </div>
        <div class="col-md-6">
            <div class="ibox-content">
                <form class="m-t" role="form" action="http://ebk.ebk.ebk/web/user/login" method="post">
                    <h2 class="font-bold text-center">微信管理后台</h2>
                    <div class="form-group text-center">
                        <h2 class="font-bold">登录</h2>
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
    </div>
    <hr>

</div>
</body>
</html>
