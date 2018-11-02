<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 10:30
 */
return [
    'route' => [

        'prefix' => 'admin',

        'namespace'     => 'App\\Http\\Controllers',

        'middleware'    => [],
    ],
    'directory' => app_path('Admin'),

    'auth'              =>  [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
            'admin_api' => [
                'driver'   => 'admin_token',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => Jcove\Admin\Models\AdminUser::class,
            ],
        ],

//        'defaults'  =>  [
//            'guard' =>  'admin_api'
//        ]

    ],
    'guard'         =>  'admin',
    'api_guard'     =>  'admin_api',
    'admin_token'   =>  'Admin-Token',//接口请求时token的key
    'validate_access'=> false
];