<?php

namespace Jcove\Admin\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Jcove\Admin\Facades\Admin;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (Admin::guest(config('admin.api_guard'))) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $user                               =   Admin::user(config('admin.api_guard'));
        //超级管理员，跳过
        if($user->id != 1){
            foreach ($permissions as $permission) {
                if (Admin::user(config('admin.api_guard'))->can($permission)) {
                    config(['restful.guard'=>config('admin.api_guard')]);
                    config(['restful.validate_access'=>config('admin.validate_access')]);
                    return $next($request);
                }
            }
            throw UnauthorizedException::forPermissions($permissions);
        }else{
            config(['restful.guard'=>config('admin.api_guard')]);
            config(['restful.validate_access'=>config('admin.validate_access')]);
            return $next($request);
        }


    }
}
