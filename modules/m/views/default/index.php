<?php
use app\common\services\UtilService;
use app\common\services\UrlService;
use app\common\services\StaticService;

StaticService::includeAppJsStatic("/js/m/default/index.js",['depends' => app\assets\MAsset::className()]);
?>

<div id="slideBox" class="slideBox">
    <div class="bd">
        <?php if ($image_list) :?>
        <ul>
            <?php foreach ($image_list as $_item ) :?>
                <li><img style="max-height: 250px;" src="<?=UrlService::buildPicUrl("brand",$_item['image_key'])?>" /></li>
            <?php endforeach;?>
        </ul>
        <?php endif;?>
    </div>
    <div class="hd"><ul></ul></div>
</div>
<div class="fastway_list_box">
    <ul class="fastway_list">
        <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>品牌名称：<?=$info['name']?></span></a></li>
        <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>联系电话：<?=$info['mobile']?></span></a></li>
        <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>联系地址：<?=$info['address']?></span></a></li>
        <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>品牌介绍：<?=$info['description']?></span></a></li>
    </ul>
</div>