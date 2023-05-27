<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function username()
    {
        return 'username';
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('username', $request->username)->first();
        if ( $user && ($user->l_activo != 'S') ) {
            return $this->sendLockedAccountResponse($request);
        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLockedAccountResponse(Request $request)
    {
        return redirect('login')
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => $this->getLockedAccountMessage(),
            ]);
    }

    protected function getLockedAccountMessage()
    {
        return 'Su cuenta esta Inactiva o se encuentra Bloqueada.';
    }
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
