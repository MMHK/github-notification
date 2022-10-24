<?php

namespace App\Providers;

use App\Helper\MM\ShortLink;
use App\Helper\MM\UrlGenerator;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 * @property \Laravel\Lumen\Application|\Illuminate\Contracts\Foundation\Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * 针对开发环境的配置
         */
        if ($this->app->environment(ENV_DEV)) {
            $this->app->configure('ide-helper');
            /**
             *  引入 IDE helper
             */
            $this->app->register(
                \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class
            );
        }

        /**
         * 注入自定义的url 接口
         **/
        $this->app->alias(UrlGenerator::class, 'url');


        /**
         * 设定静态资源的版本号
         */
        app('url')->setVersion(env('ASSET_VERSION', 0));

        /**
         * 注入 Artisan
         */
        $this->app->singleton('artisan', function(\Laravel\Lumen\Application $app){
            return new \App\Console\Kernel($app);
        });

        /**
         * 注册JSONP 中间件
         * 格式化输出中间件
         */
        $this->app->routeMiddleware([
            'jsonp' => \App\Http\Middleware\JSONPMiddleware::class, //支持JSONP
            'json.result' => \App\Http\Middleware\FormatResult::class, //格式化JSON输出
            'layout' => \App\Http\Middleware\RenderLayout::class, //渲染layout
            'api.auth' => \App\Http\Middleware\Authenticate::class, //渲染layout
            'cors' => \Fruitcake\Cors\HandleCors::class, //允许跨域调用
        ]);

        /**
         * 注册session中间件
         */
        $this->app->middleware([
            \App\Http\Middleware\DisableSession::class,
        ]);

        /**
         * 加载 APP config
         */
        $this->app->configure('app');
        $this->app->configure('auth');
        $this->app->configure('services');

        /**
         * 注入 bitly short link
         */
        $this->app->singleton('mm.shortlink', function ($app) {
            return new ShortLink();
        });
    }
}
