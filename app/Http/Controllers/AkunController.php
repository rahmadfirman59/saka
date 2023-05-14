<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Akun;
use Illuminate\Support\Facades\Session;

class AkunController extends Controller
{
    
    public function index()
    {
        $akun = Akun::all();
        return view('akun.index')
            ->with('akun', $akun);
    }

    public function detail($id){
        return Akun::find($id);
    }

    public function store_update(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required',
            'jenis_akun_id' => 'required',
            'nama_akun' => 'required',
            'jumlah' => 'required|numeric',
        ],
        [
            'about.required' => 'Kode Akun Belum Diisi',
            'jenis_akun_id.required' => 'Jenis Akun Belum Diisi',
            'nama_akun.required' => 'Nama Akun Belum Diisi',
            'jumlah.required' => 'Jumlah Belum Diisi',
            'jumlah.numeric' => 'Jumlah Harus Angka'
        ]);

        if(isset($request->id)){
            $request->request->add(['updated_by' => Session::get('useractive')->id]);
        }


        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }
        
        Akun::updateOrCreate(['id' => $request->id],$request->all() );

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }

    public function delete($id){
        $delete = Akun::find($id);

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
