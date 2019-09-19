<?php
use \app\common\services\UrlService;
use \app\common\services\StaticService;
StaticService::includeAppJsStatic( "/js/m/product/order.js",\app\assets\MAsset::className() );
?>
<div class="page_title clearfix">
        <span>订单提交</span>
    </div>
    <div class="order_box">
        <div class="order_header">
            <h2>确认收货地址</h2>
        </div>

        <ul class="address_list">
            <li style="padding: 5px 5px;">
                <label>
                    <input style="display: inline;" type="radio" name="address_id" value="2"  checked   >
                    上海市浦东区太阳洋泾街道（孙潇）13774355081                </label>

            </li>
            <li style="padding: 5px 5px;">
                <label>
                    <input style="display: inline;" type="radio" name="address_id" value="1"   >
                    天津市河东区狗不理包子100号（孙潇收）13774355074                </label>

            </li>
        </ul>


        <div class="order_header">
            <h2>确认订单信息</h2>
        </div>
        <ul class="order_list">
            <?php foreach($product_list as $k=>$v ):?>
            <li data="<?=$v['id']?>" data-quantity="<?=$v['quantity']?>">
                 <a href="<?=UrlService::buildMUrl('/product/info',['id' => $v['id']])?>">
                    <i class="pic">
                        <img src="<?=$v['main_image']?>" style="width: 100px;height: 100px;"/>
                    </i>
                    <h2><?=$v['name']?></h2>
                    <h2>数量：<?=$v['quantity']?></h2>
                    <h4>&nbsp;</h4>
                    <b>¥ <?=$v['price']?></b>
                </a>
            </li>
            <?php endforeach;?>
        </ul>
        <div class="order_header" style="border-top: 1px dashed #ccc;">
            <h2>总计：<?=$total_pay_money?></h2>
        </div>
    </div>
    <div class="op_box">
        <input type="hidden" name="sc" value="product">
        <input style="width: 100%;" type="button" value="确定下单" class="red_btn do_order"  />
    </div>
