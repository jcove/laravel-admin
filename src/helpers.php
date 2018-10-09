<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/21
 * Time: 11:54
 */
if (!function_exists('admin_path')) {
    /**
     * @param string $path
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}