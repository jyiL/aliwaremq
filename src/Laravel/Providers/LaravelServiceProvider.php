<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/7
 * Time: 3:10 PM
 */

namespace Jyil\AliwareMQ\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Jyil\AliwareMQ\AliyunCredentialsProvider;

class LaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/laravel.php' => config_path('aliwaremq.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                FooCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/laravel.php', 'aliwaremq'
        );

        $this->app->singleton('aliwaremq', function ($app) {
            $config = $app['config']->get('aliwaremq');

            return new AliyunCredentialsProvider($config);
        });
    }
}