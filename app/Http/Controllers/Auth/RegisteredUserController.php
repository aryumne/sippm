<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'nidn' => ['required', 'numeric', 'digits:10', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $nidn = Dosen::where('nidn', $request->nidn)->get();
        if (count($nidn) > 0) {
            $user = User::create([
                'nidn' => $request->nidn,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
            ]);
            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        } else {
            Alert::toast('NIDN tidak valid', 'error');
            return back();
        }

    }
}
