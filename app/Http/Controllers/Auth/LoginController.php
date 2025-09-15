<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Validator;
use Session;
use Auth;


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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function checklogin(Request $request)
    {
        $this->validate($request, [
            'username'     => 'required|string',
            'password'  => 'required|alphaNum|min:4'
        ]);

        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password')
        );
        if(Auth::attempt($user_data))
        {
            if(auth()->user()->user_type == 'ict'){

                return redirect(route('approvers.index'));

            } else {

                $user = Auth::user();

                if (!empty($user->trans_types)) {
                    $transTypes = explode('|', $user->trans_types);
                    $firstType = $transTypes[0] ?? 'OREM'; // fallback to OREM if empty
                } else {
                    $firstType = 'OREM'; // default if no trans_types
                }

                return redirect()->route('transactions.index_new', ['details' => $firstType]);
            }  
        }
        else
        {
            return back()->with('error','These credentials do not match our records.');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        
        return redirect()->route('login');
    }
}
