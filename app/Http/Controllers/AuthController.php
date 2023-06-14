<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //

    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $check = User::where("email", '=', $request->username)->count();

        if ($check == 1) {
            $user = User::where("email", '=', $request->username)->first();
            // return  encrypt($request->password)
            if ($request->password == decrypt($user->password)) {
                Session::put('useractive', $user);

                return redirect('')
                    ->with('message', 'Login Success')
                    ->with('message_type', 'success');
            } else {
                return redirect('login')
                    ->with('message', 'Password Salah')
                    ->with('message_type', 'error');
            }
        } else {
            return redirect('login')
                ->with('message', 'Username tidak ditemukan')
                ->with('message_type', 'error');
        }
        // return $user;
    }

    public function logout()
    {
        Session::forget('useractive');
        Session::forget('admin_temporary');
        return redirect('/login');
    }
}
