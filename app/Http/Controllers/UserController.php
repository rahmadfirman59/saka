<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDO;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return view('users.index')
            ->with('user', $user);
    }

    public function detail($id){
        return User::find($id);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required',
            'level' => 'required',
            'email' => 'required|email:dns',
            'password' => 'required',
            'password_retype' => 'required|same:password',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }
            
        $request->request->add(['password' =>Hash::make($request->password)]);
        User::create([
            'name' => $request->nama_user,
            'email' => $request->email,
            'level' => $request->level,
            'password' => $request->password,
        ]);

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'level' => 'required',
            'email' => 'required|email:dns',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }
        
        
        $request->request->add(['updated_by' => Session::get('useractive')->id]);
        if(isset($request->change_password)){
            $request->request->add(['password' =>Hash::make($request->password)]);
            $request->request->remove('change_password');
        }

        User::where('id', $request->id)->update($request->except(['_token']) );

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }

    public function delete($id){
        $delete = User::find($id);
        if($delete <> ""){
            $delete->delete();
            return [
                'status' => 200,
                'message' => 'Data berhasil dihapus'
            ];
        }

        return [
            'status' => 300,
            'message' => 'Data tidak ditemukan'
        ];
    }
}
