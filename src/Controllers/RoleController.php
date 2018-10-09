<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 11:59
 */

namespace Jcove\Admin\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Jcove\Admin\Facades\Admin;
use Jcove\Restful\Restful;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use Restful;

    private $permissions;
    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->model                                    =   app('Spatie\Permission\Models\Role');
        $this->setExceptField(['admin_token','permissions']);
    }

    protected function prepareSave(){
        $this->model->guard_name                        =   config('admin.api_guard');
        $this->validatePermissions(request()->permissions);
    }
    protected function beforeUpdate(){
        $this->model->guard_name                        =   config('admin.api_guard');
        $this->validatePermissions(request()->permissions);
    }

    protected function validator($data){
        if(request()->method()== "PUT"){
            $rule['name']               =   'required|unique:roles,id,'.request()->id;
        }else{
            $rule['name']               =   'required|unique:roles';
        }
        $rule['permissions']            =   'required|array';
        return Validator::make($data,$rule);
    }

    protected function saved(){
        $this->givePermissionTo();
    }
    protected function updated(){
        $this->syncPermissions();
    }

    public function givePermissionTo(){
       if($this->permissions){
           $this->model->givePermissionTo($this->permissions);
       }
    }

    public function givePermission($roleId = 0){
        $this->model                        =   Role::findOrFail($roleId);
        $this->validatePermissions(request()->permissions);
        $this->model->givePermissionTo($this->permissions);
        $this->data['data']                 =   $this->model;
        $this->beforeShow();
        return $this->respond($this->data);
    }

    protected function syncPermissions(){
        if($this->permissions){
            $this->model                    =   $this->model->syncPermissions($this->permissions);
        }
        dump($this->permissions);
    }

    protected function validatePermissions($permissions){
        if(is_array($permissions) && count($permissions) > 0){
            foreach ($permissions as $permission){
                Permission::findByName($permission,config('admin.api_guard'));
            }
            $this->permissions                      =   $permissions;
        }
    }
    protected function beforeShow(){
        $this->model->permissions                   =   $this->model->permissions;
    }
}