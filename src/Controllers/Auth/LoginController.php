<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 11:14
 */

namespace Jcove\Admin\Controllers\Auth;



use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Jcove\Admin\Exceptions\AdminException;
use Jcove\Admin\Facades\Admin;
use Jcove\Restful\Restful;

class LoginController extends Controller
{
    use Restful;
    use AuthenticatesUsers;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws AdminException
     */
    public function doLogin(Request $request){
        $credentials = $request->only(['username', 'password']);

        $validator = Validator::make($credentials, [
            'username' => 'required', 'password' => 'required',
        ]);

        $validator->validate();

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();


            return $this->respond($user);
        }
        throw new AdminException(trans('admin.user_password_error'));
    }

    protected function username(){
        return 'username';
    }

    protected function guard()
    {
        return Admin::guard(config('admin.guard'));
    }
}