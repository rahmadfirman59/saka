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

    public function store_update(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_biaya' => 'required',
            'kode_akun' => 'required',
            'nama_rekening' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
        ]);


        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }
        
        Biaya::create([
            'kode_akun' => $request->kode_akun,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'status' => $request->jenis,
        ]);

        MasterTransaksi::create([
            'kode' => $request->kode_biaya,
            'tanggal' => $request->tanggal,
            'kode_akun' => 111,
            'keterangan' => "Kas",
            'debet' => $request->jumlah,
            'type' => 4
        ]);

        MasterTransaksi::create([
            'kode' => $request->kode_biaya,
            'tanggal' => $request->tanggal,
            'kode_akun' => $request->kode_akun,
            'keterangan' => "Kas",
            'kredit' => $request->jumlah,
            'type' => 3
        ]);

        Akun::where('kode_akun', $request->kode_akun)->update([
            'nama_akun' => $request->nama_rekening,
            'jenis_akun_id' => $request->jenis,
            'jumlah' => $request->jumlah,
        ]);

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }
}
