<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\HistoryPasien;
use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\Pasien;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\Log;
use App\Models\Penjualan;
use App\Models\User;

class JurnalPenjualanController extends Controller
{

    private $perusahaan;
    public function __construct()
    {
        $this->perusahaan = (object) [
            'nm_perusahaan' => 'APOTEK SAKA SASMITRA',
            'email' => 'ichimentei.indo@gmail.com',
            'alamat' => 'JALAN DIPONEGORO SEMARANG',
        ];
    }

    public function index(){
        $transaksi = MasterTransaksi::where('type', 2)->orderBy('created_at', 'desc')->get();
        return view('jurnal.jurnal-penjualan')
            ->with('transaksi', $transaksi);
    }

    public function detail_penjualan($id){
        $data['transaksi'] = MasterTransaksi::where('type', 2)->find($id);
        $data['type_penjualan'] = Penjualan::where('id_transaksi', $data['transaksi']->id)->select('tipe')->first();
        if($data['type_penjualan']->tipe == 2){
            $data['penjualan'] = Penjualan::with('dokter')->with('obat_racik')->where('id_transaksi', $data['transaksi']->id)->get();
        } else {
            $data['penjualan'] = Penjualan::with('dokter')->with('barang')->where('id_transaksi', $data['transaksi']->id)->get();
        }
        $data['petugas'] = User::find($data['transaksi']->created_by)->first();
        $data['pasien'] = HistoryPasien::where('id_transaksi', $data['transaksi']->id)->with('pasien')->first();
        $data['perusahaan'] = $this->perusahaan;
        return view('report.penjualanReport')
            ->with($data);
    }

    public function cetak_penjualan($id){
        $data['transaksi'] = MasterTransaksi::where('type', 2)->find($id);
        $data['type_penjualan'] = Penjualan::where('id_transaksi', $data['transaksi']->id)->select('tipe')->first();
        if($data['type_penjualan']->tipe == 2){
            $data['penjualan'] = Penjualan::with('dokter')->with('obat_racik')->where('id_transaksi', $data['transaksi']->id)->get();
        } else {
            $data['penjualan'] = Penjualan::with('dokter')->with('barang')->where('id_transaksi', $data['transaksi']->id)->get();
        }
        $data['petugas'] = User::find($data['transaksi']->created_by)->first();
        $data['perusahaan'] = $this->perusahaan;
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

    public function batal_penjualan(Request $request){
        $transaksi = MasterTransaksi::find($request->id);
        $barang = Barang::all();
        
        DB::beginTransaction();
        
		try{
            //hapus transaksi kas dan kurangi jumlah akun kas dan pendapatan
            MasterTransaksi::where('type', 3)->where('kode', $transaksi->kode)->delete();
            $akun = Akun::where('kode_akun', 111)->first();
            $akun->jumlah -= $transaksi->kredit;
            $akun->save();

            $akun1 = Akun::where('kode_akun', 411)->first();
            $akun1->jumlah -= $transaksi->kredit;
            $akun1->save();

            $penjualan = Penjualan::where('id_transaksi', $transaksi->id)->get();

            Keranjang::query()->truncate();
            
            foreach($penjualan as $item){
                $barang_single = $barang->firstWhere('id', $item->id_barang);
                if($item->tipe == 0){
                    $stok = $barang_single->stok + $item->jumlah;
                    $stok_grosir = floor($stok / $barang_single->jumlah_grosir);
                    $barang_single->stok = $stok;
                    $barang_single->stok_grosir = $stok_grosir;
                } else {
                    $stok_grosir = $barang_single->stok_grosir + $item->jumlah;
                    $stok = $barang_single->stok + $item->jumlah * $barang_single->jumlah_grosir;
                    $barang_single->stok = $stok;
                    $barang_single->stok_grosir = $stok_grosir;
                }
                $barang_single->save();

                Keranjang::create([
                    'id_barang' => $item->id_barang,
                    'type' => 2,
                    'created_by' => Session::get('useractive')->id,
                    'created_at' => Carbon::now()->toDateTimeString()
                ]);

                $item->delete();
            }
            
            Log::create([
                'user_id' => Session::get('useractive')->id,
                'nomor_transaksi' => $transaksi->kode,
                'waktu' => date('Y-m-d H:i:s'),
                'keterangan' => 'Pembatalan Transaksi ' . $transaksi->kode,
                'jumlah' => $transaksi->kredit
            ]);
            
            HistoryPasien::where('id_transaksi', $transaksi->id)->delete();

            $transaksi->delete();

            DB::commit();

            Session::forget('admin_temporary');
            
            return [
                'status' => 200,
                'message' => 'Transaksi Berhasil Dihapus'
            ];
        }catch(\Exception $e){
            DB::rollback();
            Session::forget('admin_temporary');

			return [
				'status' 	=> 300, // GAGAL
				'message'       => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error'
			];

		}
    }

    public function clear_session(){
        Session::forget('admin_temporary');
    } 
}
