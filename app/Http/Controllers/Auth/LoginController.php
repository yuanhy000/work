<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogin;
use App\Events\UserRegister;
use App\Http\Controllers\Controller;
use App\Http\Proxy\TokenProxy;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelSocialite\Socialite;

class LoginController extends Controller
{
//    use AuthenticatesUsers;

    protected $proxy;
    protected $redirectTo = '/';

    public function __construct(TokenProxy $proxy)
    {
        $this->proxy = $proxy;
    }

    public function githubLogin()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback()
    {
        $user = Socialite::driver('github')->user();

        $account = $this->checkUserExist($user);
        $result = $this->proxy->githubLogin($account->email);

        return $this->redirectWithToken($result);
    }

    public function login()
    {
        switch (request('type')) {
            case 'email':
                return $this->proxy->emailLogin(request('email'), request('password'));
            case  'phone':
                return $this->proxy->phoneCodeLogin(request('phone'), request('code'));
        }
    }

    public function logout()
    {
        return $this->proxy->logout();
    }

    public function refresh()
    {
        return $this->proxy->refresh();
    }

    public function checkUserExist($user)
    {
        $account = User::where('email', '=', $user->email)->first();
        if (!$account) {
            event(new UserRegister($user = User::create([
                'email' => $user->email,
                'name' => $user->nickname,
                'avatar' => $user->avatar,
                'number' => User::generateUserID()
            ])));
            return $user;
        }
        return $account;
    }

    public function redirectWithToken($result)
    {
        $access_token = $result['access_token'];
        $auth_id = $result['auth_id'];
        $redirect_url = '/#/auth-callback?' . 'access_token=' . $access_token . '&auth_id=' . $auth_id;
        return redirect($redirect_url)
            ->cookie('refreshToken', $result['refresh_token'], 14400, null, null, false, true);
    }
}
