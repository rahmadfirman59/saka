<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        $validate = [
            'username'         => 'required',
            'password'         => 'required',
        ];

        $messages = [
            'username.required'   => 'Email wajib di isi',
            'password.required'   => 'Password wajib diisi',
        ];

        $validator = Validator::make($request->all(), $validate, $messages);

        if($validator->fails()){
            return redirect('login')
                ->with('message', $validator->errors()->first())
                ->with('message_type', 'error');
        }

        $data = [
            'email'     => $request->input('username'),
            'password'  => $request->input('password'),
        ];

        auth::attempt($data);
        
        if (Auth::check()) {
            $user = User::where("email", '=', $request->username)->first();
            Session::put('useractive', $user);

            return redirect('')
                ->with('message', 'Login Success')
                ->with('message_type', 'success');
        } else {
            return redirect('login')
                ->with('message', 'Password Salah')
                ->with('message_type', 'error');
        }
    }

    public function logout()
    {
        Session::forget('useractive');
        Session::forget('admin_temporary');
        return redirect('/login');
    }
}
