<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/24
 * Time: 11:01
 */

namespace Jcove\Admin\Controllers\Auth;



use Illuminate\Support\Facades\Validator;
use Jcove\Admin\Facades\Admin;
use Jcove\Admin\Models\AdminUser;
use Jcove\Restful\Restful;

class MyController
{
    use Restful;

    protected $user;

    public function permission(){
        return $this->getPermissions();
    }

    protected function getPermissions(){
        $this->user                                   =   Admin::guard(config('admin.api_guard'))->user();
        $permissions1                           =   $this->user->permissions;
        $permissions2                           =   $this->user->getAllPermissions();
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

        $this->user                             =   $this->user->toArray();
        $this->user['permissions']              =   $permissions;
        return $permissions;
    }
    public function role(){
        $user                                   =   Admin::guard(config('admin.api_guard'))->user();
        $roles                                  =   $user->getRoleNames();
        return $roles->all();
    }
    public function my(){
       $this->getPermissions();
        return respond($this->user);
    }

    public function modify($id){
        $data                               =   request()->all();
        $rules['username']                      =   'required|unique:admin_users,id,'.request()->id;
        $rules['password']                      =   'required|between:6,60|confirmed';
        $rules['password_confirmation']         =   'required|between:6,60';
        Validator::make($data,$rules)->validate();
        $this->model                        =   AdminUser::findOrFail($id);
        foreach ($data as $column => $value) {
            if(!in_array($column,$this->getExceptFields())){
                $this->model->setAttribute($column, $value);
            }
        }
        if(request()->password == $this->model->getOriginal('password')) {
            $this->model->offsetUnset('password');
        }elseif($this->model->getAttribute('password')){
            $this->model->password           =    bcrypt(request()->password);
        }
        $this->model->save();
        $this->data['data']                     =   $this->model;
        return $this->respond($this->data);
    }
}