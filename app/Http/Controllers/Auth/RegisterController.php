<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\ConfigsUser;
use App\Models\Shop;
use App\Rules\UkrainePhone;

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
    protected $redirectTo = '/date';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'shops' => Shop::orderBy('name', 'asc')->get()
        ]);
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15', 'unique:users', new UkrainePhone],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'shopid' => ['required', 'numeric', 'exists:shops,id'],
            'groupid' => ['required', 'numeric', 'exists:group_products,id'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $u = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => "exampla@email.com",
            'password' => Hash::make($data['password']),
        ]);
        ConfigsUser::updateOrCreate([
            'user_id'=>$u->id,'key'=>User::CONF_KEY_SHOP],
             ['value'=>$data['shopid']]
            );
        ConfigsUser::updateOrCreate(
            ['user_id'=>$u->id,'key'=>User::CONF_KEY_GROUP],
             ['value'=>$data['groupid'] ?? 0]
            );
         ConfigsUser::updateOrCreate(
            ['user_id'=>$u->id,'key'=>User::CONF_KEY_EXPS_DAYS],
             ['value'=> 7]
            );
        return $u;

    }
}
