<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Akun;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AkunController extends Controller
{
    
    public function index()
    {
        $akun = Akun::orderBy('kode_akun', 'asc')->get();
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

    public function tambah_modal(Request $request){
        $validator = Validator::make($request->all(), [
            'jumlah_modal' => 'required|numeric',
        ],
        [
            'jumlah_modal.required' => 'Jumlah Modal Harus Diisi',
            'jumlah_modal.numeric' => 'Jumlah Modal Harus Angka',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        $akun1 = Akun::where('kode_akun', 311)->first();
        $akun1->jumlah += $request->jumlah_modal;
        $akun1->save();

        $akun2 = Akun::where('kode_akun', 111)->first();
        $akun2->jumlah += $request->jumlah_modal;
        $akun2->save();

        Log::create([
            'user_id' => Session::get('useractive')->id,
            'nomor_transaksi' => '',
            'waktu' => date('Y-m-d H:i:s'),
            'keterangan' => 'Penambahan Modal Sebanyak ' . $request->jumlah_modal,
            'jumlah' => $request->jumlah_modal
        ]);

        return [
            'status' => 200,
            'message' => 'Modal Berhasil Ditambah',
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
