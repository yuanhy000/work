<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegister;
use App\Http\Proxy\TokenProxy;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $proxy;
    protected $redirectTo = '/home';

    public function __construct(TokenProxy $proxy)
    {
        $this->proxy = $proxy;
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new UserRegister($user = $this->create($request->all())));
//        Auth::guard()->login($user);
        return $this->proxy->getRegisterToken($user->email, $user->email);
    }

    protected function validator(array $userInfo)
    {
        return Validator::make($userInfo, [
            'userName' => 'required|string|max:255|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:9',
            'phone' => 'required|string|size:11|unique:users',
            'code' => 'in:' . Cache::get('register.code.' . $userInfo['phone'])
        ]);
    }

    protected function create(array $userInfo)
    {
        return User::create([
            'name' => $userInfo['userName'],
            'email' => $userInfo['email'],
            'password' => Hash::make($userInfo['password']),
            'phone' => $userInfo['phone'],
            'number' => User::generateUserID()
        ]);
    }
}
