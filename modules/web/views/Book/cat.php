<?php
use app\common\services\StaticService;
use app\common\services\UrlService;
StaticService::includeAppJsStatic("/js/web/book/cat.js",['depends' => \app\assets\WebAsset::className()]);

?>

<?=Yii::$app->view->renderFile("@app/modules/web/views/common/tab_book.php",['current' => 'cat']);?>

<div class="row">
    <div class="col-lg-12">
        <form class="form-inline wrap_search">
            <div class="row  m-t p-w-m">
                <div class="form-group">
                    <select name="status" class="form-control inline">
                        <option value="-1">请选择状态</option>
                        <?php foreach($status_mapping as $k => $v ) :?>
                            <option value="<?=$k?>"><?=$v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/book/cat_set">
                        <i class="fa fa-plus"></i>分类
                    </a>
                </div>
            </div>

        </form>
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>序号</th>
                <th>分类名称</th>
                <th>状态</th>
                <th>权重</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $k=>$v ):?>
                <tr>
                    <td><?=$v['id']?></td>
                    <td><?=$v['name']?></td>
                    <td><?=$status_mapping[$v['status']]?></td>
                    <td><?=$v['weight']?></td>

                    <td>
                        <a class="m-l" href="<?=UrlService::buildWebUrl("/book/cat_set",['id' => $v['id']])?>">
                            <i class="fa fa-edit fa-lg"></i>
                        </a>

                        <?php if($v['status']):?>
                            <a class="m-l remove" href="javascript:void(0);" data="<?=$v['id']?>">
                                <i class="fa fa-trash fa-lg"></i>
                            </a>
                        <?php else:?>
                            <a class="m-l recover" href="javascript:void(0);" data="<?=$v['id']?>">
                                <i class="fa fa-rotate-left fa-lg"></i>
                            </a>
                        <?php endif;?>
                    </td>

                </tr>
            <?php endforeach;?>

            </tbody>
        </table>
    </div>
</div>