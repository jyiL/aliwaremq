# Aliwaremq

[![Latest Stable Version](https://poser.pugx.org/jyil/aliwaremq/v/stable)](https://packagist.org/packages/jyil/aliwaremq)
[![Total Downloads](https://poser.pugx.org/jyil/aliwaremq/downloads)](https://packagist.org/packages/jyil/aliwaremq)
[![License](https://poser.pugx.org/jyil/aliwaremq/license)](https://packagist.org/packages/jyil/aliwaremq)
[![Build Status](https://api.travis-ci.org/jyiL/aliwaremq.svg?branch=master)](https://travis-ci.org/jyiL/aliwaremq)


### Support

- [x] [Laravel(中文文档)](https://github.com/jyiL/aliwaremq/wiki/Laravel-%E6%96%87%E6%A1%A3)
- [x] [Lumen(中文文档)](https://github.com/jyiL/aliwaremq/wiki/Lumen-%E6%96%87%E6%A1%A3)

### Composer
    composer require jyil/aliwaremq
    
### Example
    $provider = new AliyunCredentialsProvider([
        'host' => '*',
        'port' => '*',
        'virtualHost' => '*',
        'accessKey' => '*',
        'accessSecret' => '*',
        'resourceOwnerId' => '*',
    ]);
    
    // send
    $provider->send('queue', 'Hello World');
    
    $provider->send('queue', 'Hello World', 'exchange', 'exchangeType');
    
    // receive
    $provider->receive('queue', '', function ($msgBody) {
        echo 'body---' . $msgBody;
    });
    
    $provider->receive('queue', '', function ($msgBody) {
        echo 'body---' . $msgBody;
    }, 'exchange', 'exchangeType');
    
### License

Aliwaremq is open-sourced software licensed under the [MIT License](https://github.com/medz/cors/blob/master/LICENSE).    
