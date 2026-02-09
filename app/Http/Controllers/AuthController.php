<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $dosen = DB::table('absenmahasiswa.dosen')
            ->where('email',$request->email)
            ->first();

        if(!$dosen)
        {
            return back()->with('error','Email tidak ditemukan');
        }

        if(!Hash::check($request->password,$dosen->password))
        {
            return back()->with('error','Password salah');
        }

        session(['dosen'=>$dosen]);

        return redirect('/dashboard');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
