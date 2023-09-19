<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Biaya;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JurnalPenyesuaianController extends Controller
{
    public function index(){
        $data['penyesuaian'] = Akun::whereBetween('kode_akun', [611, 666])->get();
        $data['biaya_id'] = Biaya::select('id')->orderBy('id', 'desc')->first();
        return view('jurnal.jurnal-penyesuaian', $data);
    }

    public function detail($id){
        return Akun::where('kode_akun', $id)->first();
    }

    public function store_update(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_biaya' => 'required',
            'pilih_biaya' => 'required|not_in:disabled',
            'jumlah' => 'required',
        ], [
            "pilih_biaya.not_in" => "The Pilih Biaya field is required"
        ]);
        
        
        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        $biaya = Akun::where('kode_akun', $request->pilih_biaya)->first();
        
        Biaya::create([
            'kode_akun' => $biaya->kode_akun,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'status' => $biaya->jenis_akun_id,
        ]);

        MasterTransaksi::create([
            'kode' => $request->kode_biaya,
            'tanggal' => $request->tanggal,
            'kode_akun' => 111,
            'keterangan' => "Kas",
            'debt' => $request->jumlah,
            'type' => 4
        ]);

        MasterTransaksi::create([
            'kode' => $request->kode_biaya,
            'tanggal' => $request->tanggal,
            'kode_akun' => $biaya->kode_akun,
            'keterangan' => $biaya->nama_akun,
            'kredit' => $request->jumlah,
            'type' => 3
        ]);

        $jumlah_akun = (int)$biaya->jumlah + (int)$request->jumlah;
        Akun::where('kode_akun', $biaya->kode_akun)->update([
            'jumlah' => $jumlah_akun,
        ]);

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }
}
