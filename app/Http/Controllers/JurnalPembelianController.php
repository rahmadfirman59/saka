<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\Pembelian;
use App\Models\User;

class JurnalPembelianController extends Controller
{
    public function index(){
        $data['transaksi'] = MasterTransaksi::with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status')->with('supplier');
        }])->where('type', 1)->get();
        return view('jurnal.jurnal-pembelian', $data);
    }

    public function detail_pembelian ($id){
        $data['transaksi'] = MasterTransaksi::where('type', 1)->find($id);
        $data['pembelian'] = Pembelian::with('supplier')->with('barang')->where('id_transaksi', $data['transaksi']->id)->get();
        $data['petugas'] = User::find($data['transaksi']->created_by)->first();
        $data['perusahaan'] = (object) [
            'nm_perusahaan' => 'APOTEK SAKA SASMITRA',
            'email' => 'ichimentei.indo@gmail.com',
            'alamat' => 'JALAN DIPONEGORO SEMARANG',
        ];
        return view('report.pembelianReport')
            ->with($data);
    }

    public function cetak_penjualan($id){
        $data['transaksi'] = MasterTransaksi::where('type', 2)->find($id);
        $data['penjualan'] = Pembelian::with('dokter')->with('barang')->where('id_transaksi', $data['transaksi']->id)->get();
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
