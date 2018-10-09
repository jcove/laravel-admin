<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 10:36
 */

namespace Jcove\Admin\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use Restful;

    private $roles;
    private $permissions;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model                                =   app('Jcove\Admin\Models\AdminUser');
        $this->setExceptField(['password_confirmation','roles','permissions']);
    }

    protected function validator($data){
        if(request()->method()== "PUT"){
            $rules['username']                      =   'required|unique:admin_users,id,'.request()->id;
            $rules['password']                      =   'between:6,60|confirmed';
            $rules['password_confirmation']         =   'between:6,60';
        }else{
            $rules['username']                      =   'required|unique:admin_users';
            $rules['password']                      =   'required|between:6,60|confirmed';
            $rules['password_confirmation']         =   'required|between:6,60';
        }
        $rules['name']                              =   'required';
        if(!empty( $data['roles'])){
            $rules['roles']                         =   'array';
        }

        if(isset($data['roles']) && $data['roles'] && is_string($data['roles'])){
            $data['roles']                          =   explode(',',$data['roles']);
        }

        return Validator::make($data,$rules);
    }

    protected function prepareSave(){
        $this->validateRoles(request()->roles);
        $this->validatePermissions(request()->permissions);
        $this->model->password                     =    bcrypt(request()->password);
    }

    protected function validateRoles($roles){
        if(is_string($roles)){
            $roles                                  =   explode(',',$roles);
        }
        if(is_array($roles) && count($roles) > 0){
            foreach ($roles as $role){
                 Role::findByName($role,config('admin.api_guard'));
            }
            $this->roles                            =   $roles;
        }
    }


    protected function validatePermissions($permissions){
        if(is_array($permissions) && count($permissions) > 0){
            foreach ($permissions as $permission){
                Permission::findByName($permission,config('admin.guard'));
            }
            $this->permissions                      =   $permissions;
        }
    }

    protected function saved(){
        $this->model->generateToken();
        $this->assignRole();
        $this->givePermissionTo();

    }
    protected function beforeUpdate(){
        if(request()->password == $this->model->getOriginal('password')) {
            $this->model->offsetUnset('password');
        }elseif($this->model->getAttribute('password')){
            $this->model->password           =    bcrypt(request()->password);
        }

    }
    protected function updated(){
        $this->syncRoles();
        $this->syncPermissions();
    }

    protected function assignRole(){
        if($this->roles){
            $this->model                    =   $this->model->assignRole($this->roles);
        }
    }

    protected function beforeShow(){
//
        $roles                                  =   $this->model->getRoleNames();
//        $this->model->offsetUnset('roles');
//        $array                              =   [];
//        if($roles->count() > 0){
//            $array                          =   implode(',',$roles->all());
//        }
//        $this->model->setAttribute('roles',$array);
    }



    protected function givePermissionTo(){
        if($this->permissions){
            $this->model                    =   $this->model->givePermissionTo($this->permissions);
        }

    }

    protected function syncRoles(){
        if($this->roles){
            $this->model                    =   $this->model->syncRoles($this->roles);
        }
    }
    protected function syncPermissions(){
        if($this->permissions){
            $this->model                    =   $this->model->syncPermissions($this->permissions);
        }
    }
}