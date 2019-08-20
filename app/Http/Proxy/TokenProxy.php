<?php


namespace App\Http\Proxy;


use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

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

    public function proxy($grantType, array $data)
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

        return response()->json($token)
            ->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);
    }
}
