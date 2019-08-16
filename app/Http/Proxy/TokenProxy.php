<?php


namespace App\Http\Proxy;


use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

class TokenProxy
{
    protected $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
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
    }

    public function phoneCodeLogin($phone, $code)
    {
        return 0;
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

        return response()->json([
            'token' => $token['access_token'],
            'auth_id' => md5($token['refresh_token']),
            'expires_in' => $token['expires_in']
        ])->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);


        return 0;
    }
}
