<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class LoginController extends Controller
{
    use ThrottlesLogins;

    private $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = Client::find(2);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @@eturn \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data, [
            $this->username() => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            ]
        );
    }

    /**
     * Handle a login request to the application
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validator($request->all())->validate();

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $this->incrementLoginAttempts($request);

        $params = [
        'grant_type' => 'password',
        'client_id' => $this->client->id,
        'client_secret' => $this->client->secret,
        'username' => request('email'),
        'password' => request('password'),
        'scope' => '*',
        ];

        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    public function refresh(Request $request)
    {
        $this->validate(
            $request, [
            'refresh_token' => 'required'
            ]
        );
    
        $params = [
        'grant_type' => 'refresh_token',
        'client_id' => $this->client->id,
        'client_secret' => $this->client->secret,
        ];

        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);

    }

    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response([], 204);
    }
}
