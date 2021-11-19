<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Dosen;
use App\Mhs;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['role'] == 'Dosen') {
            $dosen = Dosen::where('id_card', $data['nomer'])->count();
            if ($dosen >= 1) {
                $user = User::where('id_card', $data['nomer'])->count();
                if ($user >= 1) {
                    return Validator::make($data, [
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                        'role' => ['required'],
                        'nomer' => ['required'],
                        'dosen' => ['required'],
                    ]);
                } else {
                    return Validator::make($data, [
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                        'role' => ['required'],
                        'nomer' => ['required'],
                    ]);
                }
            } else {
                return Validator::make($data, [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'role' => ['required'],
                    'nomer' => ['required'],
                    'id_card' => ['required'],
                ]);
            }
        } elseif ($data['role'] == 'Mhs') {
            $mhs = Mhs::where('no_induk', $data['nomer'])->count();
            if ($mhs >= 1) {
                $user = User::where('no_induk', $data['nomer'])->count();
                if ($user >= 1) {
                    return Validator::make($data, [
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                        'role' => ['required'],
                        'nomer' => ['required'],
                        'mhs' => ['required'],
                    ]);
                } else {
                    return Validator::make($data, [
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                        'role' => ['required'],
                        'nomer' => ['required'],
                    ]);
                }
            } else {
                return Validator::make($data, [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'role' => ['required'],
                    'nomer' => ['required'],
                    'no_induk' => ['required'],
                ]);
            }
        } else {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required'],
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if ($data['role'] == 'Dosen') {
            $dosenId = Dosen::where('id_card', $data['nomer'])->get();
            foreach ($dosenId as $val) {
                $dosen = Dosen::findorfail($val->id);
            }
            return User::create([
                'name' => $dosen->nama_dosen,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'id_card' => $data['nomer'],
            ]);
        } else {
            $mhsId = Mhs::where('no_induk', $data['nomer'])->get();
            foreach ($mhsId as $val) {
                $mhs = Mhs::findorfail($val->id);
            }
            return User::create([
                'name' => strtolower($mhs->nama_mhs),
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'no_induk' => $data['nomer'],
            ]);
        }
    }
}
