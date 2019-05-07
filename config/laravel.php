<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/7
 * Time: 3:21 PM
 */

return [
    // 接入点
    'host' => env('ALIWARE_MQ_HOST', '**'),

    // 默认端口
    'port' => 5672,

    // 资源隔离
    'virtualHost' => env('ALIWARE_MQ_VIRTUAL_HOST', 'pht-mq'),

    // 阿里云的accessKey
    'accessKey' => env('ALIWARE_MQ_ACCESS_KEY', '**'),

    // 阿里云的accessSecret
    'accessSecret' => env('ALIWARE_MQ_ACCESS_SECRET', '**'),

    // 主账号id
    'resourceOwnerId' => env('ALIWARE_MQ_RESOURCE_OWNER_ID', '**'),
];