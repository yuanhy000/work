<?php


namespace App\Http\Proxy;


use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

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
            'message' => 'Credentials not match'
        ], 404);
    }

    public function phoneCodeLogin($phone, $code)
    {
        if (User::where('phone', $phone)->first()) {
            if (Cache::get('login.code.' . $phone) == $code) {
                return $this->proxy('client_credentials', [
                    'scope' => '*'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'code not match'
            ], 404);
        }
        return response()->json([
            'status' => false,
            'message' => 'phone not exist'
        ], 404);

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

        switch ($grantType) {
            case 'password':
                return response()->json($token)
                    ->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);
            case 'client_credentials':
                return response()->json($token);
            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Internal Server Error!'
                ], 500);
        }
    }
}
