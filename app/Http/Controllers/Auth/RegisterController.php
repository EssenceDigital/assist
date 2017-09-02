<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first' => 'required|string|max:25',
            'last' => 'required|string|max:25',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|string|max:25',
            'hourly_rate_one' => 'required|string|max:25',
            'hourly_rate_two' => 'string|max:25',
            'gst_number' => 'required|string|max:25'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first' => $data['first'],
            'last' => $data['last'],
            'permissions' => $data['permissions'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'company_name' => $data['company_name'],
            'hourly_rate_one' => $data['hourly_rate_one'],
            'hourly_rate_two' => $data['hourly_rate_two'],
            'gst_number' => $data['gst_number']
        ]);
    }
}
