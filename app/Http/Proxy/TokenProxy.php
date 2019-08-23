<?php


namespace App\Http\Proxy;


use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TokenProxy
{
    protected $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function getRegisterToken($email, $password)
    {
        return $this->proxy('password', [
            'username' => $email,
            'password' => $password,
            'scope' => '*'
        ]);
    }

    public function githubLogin($email)
    {
        $user = User::where('email', '=', $email)->first();

        return $this->proxy('password', [
            'username' => $email,
            'password' => $user->password,
            'scope' => '*'
        ], 'social');
    }

    public function emailLogin($email, $password)
    {
        if (auth()->attempt(['email' => $email, 'password' => $password])) {
            return $this->proxy('password', [
                'username' => $email,
                'password' => $password,
                'scope' => '*'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => '邮箱密码不匹配，请重试'
        ], 404);
    }

    public function phoneCodeLogin($phone, $code)
    {
        $user = User::checkUserByPhone($phone, $code);
        if ($user instanceof JsonResponse) {
            return $user;
        }
        return $this->proxy('password', [
            'username' => $user->email,
            'password' => $user->password,
            'scope' => '*'
        ]);

    }

    public function refresh()
    {
        $refreshToken = request()->cookie('refreshToken');
        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    public function proxy($grantType, array $data, $loginType = 'normal')
    {
        $data = array_merge($data, [
            'client_id' => env('client_id'),
            'client_secret' => env('client_secret'),
            'grant_type' => $grantType,
        ]);
        $response = $this->http->post('http://work.test/oauth/token', [
            'form_params' => $data,
        ]);

        $token = json_decode((string)$response->getBody(), true);

        if ($loginType == 'social') {
            return [
                'access_token' => $token['access_token'],
                'auth_id' => md5($token['refresh_token']),
                'refresh_token' => $token['refresh_token'],
            ];
        }
        return response()->json([
            'access_token' => $token['access_token'],
            'auth_id' => md5($token['refresh_token']),
            'expires_in' => $token['expires_in'],
        ])->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);
    }

    public function logout()
    {
        $user = auth()->guard('api')->user();
        if (is_null($user)) {
            Cookie::queue(Cookie::forget('refreshToken'));
            return response()->json([
                'message' => 'logout!'
            ], 204);
        }

        $accessToken = $user->token();
        DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => 1
            ]);
        Cookie::queue(Cookie::forget('refreshToken'));
        $accessToken->revoke();
        return response()->json([
            'message' => 'logout!'
        ], 202);
    }
}
