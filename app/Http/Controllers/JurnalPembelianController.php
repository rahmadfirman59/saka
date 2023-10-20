<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Barang;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterTransaksi;
use App\Models\Pembelian;
use App\Models\User;
use App\Models\Akun;
use App\Models\Keranjang;
use App\Models\Log;

class JurnalPembelianController extends Controller
{
    public function index(){
        $data['transaksi'] = MasterTransaksi::has('pembelian')->with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status', 'tgl_faktur')->with('supplier');
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

    public function login_admin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return [
                'status' => 301,
                'message' => $validator->errors()
            ];
        }

        $check = User::where("email", '=', $request->email)->count();
        if($check == 1){
            $user = User::where("email", '=', $request->email)->first();
            if ($request->password == decrypt($user->password)) {
                if($user->level == 'superadmin'){
                    Session::put('admin_temporary', 'active');
                    return [
                        'status' => 200,
                        'message' => "Login Berhasil !"
                    ];
                } else {
                    return [
                        'status' => 300,
                        'message' => "Akun Bukan Merupakan Admin..!"
                    ];
                }
            } else {
                return [
                    'status' => 300,
                    'message' => "Password Salah..!"
                ];
            }
        } else{
            return [
                'status' => 300,
                'message' => "Email Tidak Ditemukan..!"
            ];
        }
    }

    public function clear_session(){
        Session::forget('admin_temporary');
    } 
}
