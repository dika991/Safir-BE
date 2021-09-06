<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use JsonResponse;

    public function userDashboard()
    {
        $users = User::all();
        return $this->successWithData("Success Get User.", $users);
    }

    public function adminDashboard()
    {
        $admins = Admin::all();
        return $this->successWithData("Success Get Admin.", $admins);
    }

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'user']);

            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            $success['token'] = $user->createToken('MyApp',['user'])->accessToken;

            return $this->successWithData("Success Login.", $success, 200);
        } else {
            return $this->fail('Email and Password are wrong.');
        }
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->failValidate($validator->errors(),422);
        }

        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'admin']);

            $admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
            $success = $admin;
            $success['token'] = $admin->createToken('MyApp', ['admin'])->accessToken;

            return $this->successWithData("success", $success, 200);
        } else {
            return $this->fail('Email and Password are wrong.');
        }
    }
}
