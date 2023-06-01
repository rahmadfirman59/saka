<?php

namespace App\Http\Controllers;

use App\Models\HistoryPasien;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::with('user')->get();
        return view('pasien.index')
            ->with('pasien', $pasien);
    }

    public function detail($id){
        return Pasien::find($id);
    }

    public function check_history($id){
        $history_pasien = HistoryPasien::where('id_pasien', $id)->with('transaksi')->orderBy('created_at', 'desc')->get();
        return Response::json($history_pasien);
    }

    public function store_update(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_pasien' => 'required',
            'no_telp' => 'required',
        ],
        [
            'nama_pasien.required' => 'Nama Pasien Belum Diisi',
            'no_telp.required' => 'No Telepon Belum Diisi',
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
        
        Pasien::updateOrCreate(['id' => $request->id],$request->all() );

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }

    public function delete($id){
        $delete = Pasien::find($id);

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
