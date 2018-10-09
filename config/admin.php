<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 10:30
 */
return [
    'route' => [

        'prefix' => 'admin',

        'namespace'     => 'Jcove\\Admin\\Controllers',

        'middleware'    => ['web', 'admin'],
    ],

    'auth'              =>  [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => Jcove\Admin\Models\AdminUser::class,
            ],
        ],
    ]
];