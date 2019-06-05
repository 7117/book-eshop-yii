<?php
use app\common\services\UrlService;

?>

<div class="row  border-bottom">
    <div class="col-lg-12">
        <div class="tab_title">
            <ul class="nav nav-pills">
                <li  class="current"  >
                    <a href="/web/book/index">图书列表</a>
                </li>
                <li  >
                    <a href="/web/book/cat">分类列表</a>
                </li>
                <li  >
                    <a href="/web/book/images">图片资源</a>
                </li>
            </ul>
        </div>
    </div>
</div><style type="text/css">
    .wrap_info img{
        width: 70%;
    }
</style>
<div class="row m-t wrap_info">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-b-md">
                    <a class="btn btn-outline btn-primary pull-right" href="/web/book/set?id=1">
                        <i class="fa fa-pencil"></i>编辑
                    </a>
                    <h2>图书信息</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <p class="m-t">图书名称：<?=$info['name']?></p>
                <p>图书售价：<?=$info['price']?></p>
                <p>库存总量：<?=$info['total_count']?></p>
                <p>图书标签：<?=$info['tags']?></p>
                <p>封面图： <img src="<?=UrlService::buildPicUrl("book",$info['main_image'])?>" style="width: 50px;height: 50px;"/> </p>
                <p>图书描述：<?=$info['summary']?></p>

        <div class="row m-t">
            <div class="col-lg-12">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab-1" data-toggle="tab" aria-expanded="false">销售历史</a>
                                </li>
                                <li>
                                    <a href="#tab-2" data-toggle="tab" aria-expanded="true">库存变更</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>会员名称</th>
                                        <th>购买数量</th>
                                        <th>购买价格</th>
                                        <th>订单状态</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            郭威                                                                                                    </td>
                                        <td>4</td>
                                        <td>355.52</td>
                                        <td>2017-03-16 16:24:37</td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-2">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>变更</th>
                                        <th>备注</th>
                                        <th>时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>-1</td>
                                        <td>在线购买</td>
                                        <td>2017-03-12 15:24:04</td>
                                    </tr>
                                    <tr>
                                        <td>-1</td>
                                        <td>在线购买</td>
                                        <td>2017-03-12 15:17:51</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>在线购买</td>
                                        <td>2017-03-07 18:35:43</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>在线购买</td>
                                        <td>2017-03-07 18:35:43</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>在线购买</td>
                                        <td>2017-03-07 18:35:43</td>
                                    </tr>
                                    <tr>
                                        <td>-1</td>
                                        <td></td>
                                        <td>2017-03-07 18:04:21</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td></td>
                                        <td>2017-03-07 18:04:10</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>