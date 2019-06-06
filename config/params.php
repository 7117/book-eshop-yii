<?php

return [
    'title' => '图书商城',
    "domain" => [
        'www' => 'http://super.nat300.top/',
        'm' => 'http://super.nat300.top/m',
        'web' => 'http://super.nat300.top/web',
        'weixin' => 'http://super.nat300.top/weixin',
    ],

    "upload" => [
        'avatar' => "/uploads/avatar",
        'brand' => "/uploads/brand",
        'book' => "/uploads/book",
    ],
    "weixin" => [
        "appid" => 'wxc01ba9b834be5023',
        "sk" => 'da1e24dd41859f769b23c089f827268c',
        "token" => 'tomalang689',
        "aeskey" => 'P6PaB6bPrRzKkva5lq6kHWtYkOOlVhYq4fh1iR7LMKB',
        'pay' => [
            'key' => '',
            'mch_id' => '',
            'notify_url' => [
                'm' => '/pay/callback'
            ]
        ]
    ]

];
