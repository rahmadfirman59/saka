<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\Penjualan;
use App\Models\User;

class JurnalPenjualanController extends Controller
{
    public function index(){
        $transaksi = MasterTransaksi::where('type', 2)->get();
        return view('jurnal.jurnal-penjualan')
            ->with('transaksi', $transaksi);
    }

    public function detail_penjualan($id){
        $data['transaksi'] = MasterTransaksi::where('type', 2)->find($id);
        $data['penjualan'] = Penjualan::with('dokter')->with('barang')->where('id_transaksi', $data['transaksi']->id)->get();
        $data['petugas'] = User::find($data['transaksi']->created_by)->first();
        $data['perusahaan'] = (object) [
            'nm_perusahaan' => 'APOTEK SAKA SASMITRA',
            'email' => 'ichimentei.indo@gmail.com',
            'alamat' => 'JALAN DIPONEGORO SEMARANG',
        ];

        return view('report.penjualanReport')
            ->with($data);
    }

    public function cetak_penjualan($id){
        $data['transaksi'] = MasterTransaksi::where('type', 2)->find($id);
        $data['penjualan'] = Penjualan::with('dokter')->with('barang')->where('id_transaksi', $data['transaksi']->id)->get();
        $data['petugas'] = User::find($data['transaksi']->created_by)->first();
        $data['perusahaan'] = (object) [
            'nm_perusahaan' => 'APOTEK SAKA SASMITRA',
            'email' => 'ichimentei.indo@gmail.com',
            'alamat' => 'JALAN DIPONEGORO SEMARANG',
        ];
        return view('pdf.penjualanPDF')
            ->with($data);
    }
}
