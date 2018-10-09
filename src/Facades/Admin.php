<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 11:11
 */

namespace Jcove\Admin\Facades;


use Illuminate\Support\Facades\Facade;

class Admin extends Facade
{
    public static function getFacadeAccessor(){
        return 'admin';
    }
}