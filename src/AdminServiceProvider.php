<?php

namespace Jcove\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jcove\Admin\Auth\AdminSessionGuard;
use Jcove\Admin\Auth\AdminTokenGuard;


class AdminServiceProvider extends ServiceProvider
{

    private $routeMiddleware        =   [
        'role'                                  => \Jcove\Admin\Middlewares\RoleMiddleware::class,
        'permission'                            => \Jcove\Admin\Middlewares\PermissionMiddleware::class,
    ];

    protected $middlewareGroups                 = [
        'admin' => [
            'role',
            'permission'
        ]
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (file_exists($routes = base_path('routes/admin.php'))) {
            $this->loadRoutesFrom($routes);
        }
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()],'laravel-admin-config');
            $this->publishes([__DIR__.'/../routes' => base_path('routes')],'laravel-admin-routes');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'laravel-admin-lang');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'laravel-admin-migrations');

        }
    }


    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('admin.auth', []), 'auth.'));
    }

    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();
        $this->registerRouteMiddleware();
        $this->app->singleton('admin', function ($app) {
            return new Admin($app['session'], $app['config']);
        });

        Auth::extend('admin_token', function($app, $name, array $config) {
            return new AdminTokenGuard(Auth::createUserProvider($config['provider']),request());
        });

    }

}
