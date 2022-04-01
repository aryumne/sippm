<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => ['required', 'numeric', 'digits:10', 'unique:users'],
            'email' => ['required', 'email:dns','regex:/(.*)@unipa\.ac\.id/i', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nidn.unique' => 'NIDN ini sudah terdaftar',
            'email.unique' => 'Email ini sudah digunakan',
            'email.regex' => "Email tidak valid, harus menggunakan email UNIPA",
            'email' => "Email tidak valid, harus menggunakan email UNIPA",
            'password.min' => "Password minimal 8 digit",
        ]);


        if ($validator->fails()) {
            Alert::toast('Gagal registrasi, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }


        $nidn = Dosen::where('nidn', $request->nidn)->get();
        if (count($nidn) > 0) {
            $user = User::create([
                'nidn' => $request->nidn,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
            ]);

            Dosen::findOrFail($request->nidn)->update([
                'email' => $request->email
            ]);

            event(new Registered($user));

            Auth::login($user);

            if (Auth::user()->role_id == 1) {
                return redirect()->intended('/admin');
            } else if (Auth::user()->role_id == 2) {
                return redirect()->intended('/pengusul');
            } else if (Auth::user()->role_id == 3) {
                return redirect()->intended('/reviewer');
            } else {
                Alert::toast("You don't have any access.!", 'error');
                return redirect()->route('login');
            }
        } else {
            Alert::toast('NIDN tidak valid', 'error');
            return back()->withInput();
        }
    }
}
