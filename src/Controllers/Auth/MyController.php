<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/24
 * Time: 11:01
 */

namespace Jcove\Admin\Controllers\Auth;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Jcove\Admin\Facades\Admin;
use Jcove\Restful\Restful;

class MyController
{
    use Restful;
    public function permission(){
        $user                                   =   Admin::guard(config('admin.api_guard'))->user();
        $permissions1                           =   $user->permissions;
        $permissions2                           =   $user->getAllPermissions();
        $permissions                            =   [];
        if($permissions1->count() >0){
            foreach ($permissions1 as $item){
                $permissions[]                  =   $item->name;
            }
        }
        if($permissions2->count() >0){
            foreach ($permissions2 as $item){
                $permissions[]                  =   $item->name;
            }
        }


        return $permissions;
    }

    public function role(){
        $user                                   =   Admin::guard(config('admin.api_guard'))->user();
        $roles                                  =   $user->getRoleNames();
        return $roles->all();
    }
    public function my(){
        return Admin::guard(config('admin.api_guard'))->user();
    }
}