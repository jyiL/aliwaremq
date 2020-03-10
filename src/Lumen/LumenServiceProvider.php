<?php
/**
 * Author: jyl
 * Date: 2020-03-10
 * Time: 19:19
 * Email: avril.leo@yahoo.com
 */

namespace Jyil\AliwareMQ\Lumen;

use Illuminate\Support\ServiceProvider;
use Jyil\AliwareMQ\AliyunCredentialsProvider;

class LumenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->configure('aliwaremq');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/lumen.php', 'aliwaremq'
        );

        $this->app->singleton('aliwaremq', function ($app) {
            $config = $app['config']->get('aliwaremq');

            return new AliyunCredentialsProvider($config);
        });
    }
}