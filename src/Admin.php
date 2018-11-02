<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/20
 * Time: 11:00
 */

namespace Jcove\Admin;


use Illuminate\Config\Repository;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Admin
{


    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session                  =   $session;
        $this->config                   =   $config;
    }

    public function routes(){
        $attributes = [
            'prefix'        =>  config('admin.route.prefix'),
            'namespace'     =>  'Jcove\Admin\Controllers',
        ];

        Route::group($attributes, function ($router) {
            $router->group([], function ($router) {
                $router->post('login','Auth\LoginController@doLogin')->name('admin.login');
                $router->post('role/givePermission/{roleId?}','RoleController@givePermission');
                $router->get('permission/tree','PermissionController@tree');
                $router->get('role/limit','RoleController@limitRoles');
                $router->resource('role','RoleController')->middleware('permission:Role Manage','role:admin');
                $router->resource('permission','PermissionController')->middleware('permission:Permission Manage','role:admin');
                $router->resource('admin','UserController')->middleware('permission:Admin Manage','role:admin');
                $router->get('my/permission','Auth\MyController@permission');
                $router->get('my/role','Auth\MyController@role');
                $router->get('my','Auth\MyController@my');
                $router->put('my/modify/{id}','Auth\MyController@modify');

            });

        });

    }
    public function user($guard = null)
    {
        return $this->guard($guard)->user();
    }
    public function id($guard = null)
    {
        return $this->guard($guard)->id();
    }

    public function check($guard = null){
        return $this->guard($guard)->check();
    }
    public function guest($guard = null){
        return $this->guard($guard)->guest();
    }
    public function guard($guard = null){
        return Auth::guard($guard ? :config('admin.guard'));
    }



}