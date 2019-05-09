# Aliwaremq

[![Latest Stable Version](https://poser.pugx.org/jyil/aliwaremq/v/stable)](https://packagist.org/packages/jyil/aliwaremq)
[![Total Downloads](https://poser.pugx.org/jyil/aliwaremq/downloads)](https://packagist.org/packages/jyil/aliwaremq)
[![License](https://poser.pugx.org/jyil/aliwaremq/license)](https://packagist.org/packages/jyil/aliwaremq)


### Support

- [x] laravel

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
    
    $provider->send('queue', 'Hello World');
    
### License

Aliwaremq is open-sourced software licensed under the [MIT License](https://github.com/medz/cors/blob/master/LICENSE).    