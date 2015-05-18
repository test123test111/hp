<?php

return [
    "channels" => [
        "wx_client" => [
            "name" => "微信支付",
            "className" => "common\\extensions\\spay\\channels\\WeiXin",
            "type" => "client"
        ],
        "zfb" => [
            "name" => "支付宝",
            "className" => "common\\extensions\\spay\\channels\\Alipay",
            "type" => "web"
        ],
        "zfb_client" => [
            "name" => "支付宝客户端",
            "className" => "common\\extensions\\spay\\channels\\Alipay",
            "type" => "client"
        ],
    ],
];