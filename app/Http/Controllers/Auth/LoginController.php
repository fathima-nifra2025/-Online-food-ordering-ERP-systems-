<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect('/login')->with('error', 'Your account has been deactivated.');
        }

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'restaurant_staff':
                return redirect()->route('staff.dashboard');
            case 'delivery_staff':
                return redirect()->route('delivery.dashboard');
            case 'customer':
            default:
                return redirect()->route('customer.orders');
        }
    }
}
