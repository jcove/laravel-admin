<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 13:43
 */

namespace Jcove\Admin\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;

class PermissionController extends Controller
{
    use Restful;
    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        $this->model                                    =   app('Spatie\Permission\Models\Permission');
        $this->setExceptField(['admin_token']);

    }

    protected function prepareSave(){
        $this->model->guard_name                        =   config('admin.api_guard');
    }

    protected function beforeUpdate(){
        $this->model->guard_name                        =   config('admin.api_guard');
    }
    protected function validator($data){
        if(request()->method()== "PUT"){
            $rule['name']               =   'required|unique:permissions,id,'.request()->id;
        }else{
            $rule['name']               =   'required|unique:permissions';
        }
        return Validator::make($data,$rule);
    }
}