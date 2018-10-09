<?php

namespace Jcove\Admin\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Jcove\Admin\Facades\Admin;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Admin::guest(config('admin.api_guard'))) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        $user                               =   Admin::user(config('admin.api_guard'));
        //超级管理员，跳过
        if($user->id != 1){
            if (! Admin::user(config('admin.api_guard'))->hasAnyRole($roles)) {
                throw UnauthorizedException::forRoles($roles);
            }
        }

        config(['restful.guard'=>config('admin.api_guard')]);
        config(['restful.validate_access'=>config('admin.validate_access')]);
        return $next($request);
    }
}
