<?php
use app\common\services\UrlService;
use app\common\services\StaticService;

StaticService::includeAppJsStatic("/js/web/member/index.js",["depends" => \app\assets\WebAsset::className()]);
?>

<?=Yii::$app->view->renderFile("@app/modules/web/views/common/tab_member.php",['current' => 'index']);?>

<div class="row">
    <div class="col-lg-12">
        <form class="form-inline wrap_search">
            <div class="row  m-t p-w-m">
                <div class="form-group">
                    <select name="status" class="form-control inline">
                        <option value="-1">请选择状态</option>
                        <option value="1"  >正常</option>
                        <option value="0"  >已删除</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="mix_kw" placeholder="请输入关键字" class="form-control" value="">
                        <span class="input-group-btn">
                            <button type="button" class="btn  btn-primary search">
                                <i class="fa fa-search"></i>搜索
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/member/set">
                        <i class="fa fa-plus"></i>会员
                    </a>
                </div>
            </div>

        </form>
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>头像</th>
                <th>姓名</th>
                <th>手机</th>
                <th>性别</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php foreach( $list as $key =>$value ) : ?>
                    <td><img alt="image" class="img-circle" src="<?=UrlService::buildPicUrl("avatar",$value['avatar'])?>" style="width: 40px;height: 40px;"></td>
                    <td><?=$value['nickname']?></td>
                    <td><?=$value['mobile']?></td>
                    <td><?=$value['sex']?></td>
                    <td><?=$value['status']?></td>
                    <td>
                        <a  href="<?php echo UrlService::buildWebUrl("/member/info",[ 'id' => $value['id'] ]);?>">
                            <i class="fa fa-eye fa-lg"></i>
                        </a>
                        <?php if($value['status']):?>
                            <a class="m-l" href="/web/member/set?id=1">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>

                            <a class="m-l remove" href="javascript:void(0);" data="<?=$value['id']?>">
                                <i class="fa fa-trash fa-lg"></i>
                            </a>
                        <?php else:?>
                            <a class="m-l recover" href="<?=UrlService::buildNullUrl();?>" data="<?=$value['id'];?>">
                                <i class="fa fa-rotate-left fa-lg"></i>
                            </a>
                        <?php endif;?>

                    </td>
                <?php endforeach;?>
            </tr>
            </tbody>
        </table>

        <?php echo Yii::$app->getView()->renderFile("@app/modules/web/views/common/page.php",[
            'pages' => $pages,
            'url'  => '/member/index'
        ]);?>

    </div>
</div>