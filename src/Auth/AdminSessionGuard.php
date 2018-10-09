<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 17:43
 */

namespace Jcove\Admin\Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class AdminSessionGuard implements AuthenticatableContract
{
    use Authenticatable;
}