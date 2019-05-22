<?php
namespace app\common\services;

class ConstantMapService
{
    public static $status_default = -1;

    public static $status_mapping = [
        0 => '删除',
        1 =>'正常',
    ];

    public static $default_avatar = "default_avatar";
}