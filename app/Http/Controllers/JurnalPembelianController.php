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
        $data['transaksi'] = MasterTransaksi::with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status')->with('supplier');
        }])->where('type', 1)->get();
        return view('jurnal.jurnal-pembelian', $data);
    }

    public function pembayaran_tempo(){
        $data['transaksi'] = MasterTransaksi::whereHas('pembelian', function ($query) {
            $query->where('status', 2);
        })->with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status', 'tgl_tempo')->with('supplier');
        }])->where('type', 1)->get();

        return view('transaksi.pembayaran-tempo', $data);
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

    // public function batal_penjualan(Request $request){
    //     $transaksi = MasterTransaksi::find($request->id);
    //     $barang = Barang::all();
        
    //     DB::beginTransaction();
        
	// 	try{
    //         //hapus transaksi kas dan kurangi jumlah akun kas dan pendapatan
    //         MasterTransaksi::where('type', 3)->where('kode', $transaksi->kode)->delete();
    //         $akun = Akun::where('kode_akun', 113)->first();
    //         $akun->jumlah -= $transaksi->kredit;
    //         $akun->save();

    //         $akun1 = Akun::where('kode_akun', 411)->first();
    //         $akun1->jumlah -= $transaksi->kredit;
    //         $akun1->save();

    //         $penjualan = Penjualan::where('id_transaksi', $transaksi->id)->get();

    //         Keranjang::query()->truncate();
            
    //         foreach($penjualan as $item){
    //             $barang_single = $barang->firstWhere('id', $item->id_barang);
    //             $stok = $barang_single->stok + $item->jumlah;
    //             $barang_single->stok = $stok;
    //             $barang_single->save();

    //             Keranjang::create([
    //                 'id_barang' => $item->id_barang,
    //                 'type' => 2,
    //                 'created_by' => Session::get('useractive')->id,
    //                 'created_at' => Carbon::now()->toDateTimeString()
    //             ]);

    //             $item->delete();
    //         }
            
    //         Log::create([
    //             'user_id' => Session::get('useractive')->id,
    //             'nomor_transaksi' => $transaksi->kode,
    //             'waktu' => date('Y-m-d H:i:s'),
    //             'keterangan' => 'Pembatalan Transaksi ' . $transaksi->kode,
    //             'jumlah' => $transaksi->kredit
    //         ]);
            
    //         HistoryPasien::where('id_transaksi', $transaksi->id)->delete();

    //         $transaksi->delete();

    //         DB::commit();

    //         Session::forget('admin_temporary');
            
    //         return [
    //             'status' => 200,
    //             'message' => 'Transaksi Berhasil Dihapus'
    //         ];
    //     }catch(\Exception $e){
    //         DB::rollback();
    //         Session::forget('admin_temporary');

	// 		return [
	// 			'status' 	=> 300, // GAGAL
	// 			'message'       => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error'
	// 		];

	// 	}
    // }

    public function clear_session(){
        Session::forget('admin_temporary');
    } 
}
