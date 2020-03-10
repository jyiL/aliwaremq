<?php
/**
 * Author: jyl
 * Date: 2020-03-10
 * Time: 19:26
 * Email: avril.leo@yahoo.com
 */

return [
    // 接入点
    'host' => env('ALIWARE_MQ_HOST', '**'),

    // 默认端口
    'port' => 5672,

    // 资源隔离
    'virtualHost' => env('ALIWARE_MQ_VIRTUAL_HOST', 'test'),

    // 阿里云的accessKey
    'accessKey' => env('ALIWARE_MQ_ACCESS_KEY', '**'),

    // 阿里云的accessSecret
    'accessSecret' => env('ALIWARE_MQ_ACCESS_SECRET', '**'),

    // 主账号id
    'resourceOwnerId' => env('ALIWARE_MQ_RESOURCE_OWNER_ID', '**'),
];