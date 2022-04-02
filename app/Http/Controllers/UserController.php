<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    public function update(Request $request, $id)
    {

        // dd("BISA");
        $rules = [
            'current_password' => ['required'],
            'password' => ['required', 'min:8'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $id = Auth::user()->id;

        if (Hash::check($request->current_password, Auth::user()->password)) {
            User::findOrFail($id)->update([
                'password' => Hash::make($request->password),
            ]);
            Alert::success('Tersimpan', 'Password anda sudah diubah');
            return back();
        }

        throw ValidationException::withMessages([
            'current_password' => "Password anda salah!",
        ]);
    }

}
