<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 10:05
 */

namespace Jcove\Admin\Models;


use Illuminate\Foundation\Auth\User;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends User
{
    use HasRoles;
    protected $guard_name = 'admin_api';
    protected $hidden = [
        'remember_token'
    ];
    public function generateToken()
    {
        $this->admin_token = str_random(60);
        $this->save();

        return $this->admin_token;
    }

    public function getAvatarAttribute($value){
        return storage_url($value);
    }
}