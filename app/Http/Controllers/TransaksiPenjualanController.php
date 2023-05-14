<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Dokter;
use App\Models\Keranjang;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\Akun;
use App\Models\Log;
use Carbon\Carbon;


class TransaksiPenjualanController extends Controller
{
    public function index(){
        $data['dokters'] = Dokter::all();
        $data['count_penjualan'] = Penjualan::count() + 1;
        $data['barang'] = Barang::orderBy('nama_barang', 'asc')->get();
        $data['keranjang'] = Keranjang::with('barang')->where('created_by', Session::get('useractive')->id)->where('type', 2)->get();
        return view('transaksi.penjualan', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'uang' => 'required',
            'nm_dokter' => 'required',
            'idbarang' => 'required',
            'qty' => 'required',
            'kode_transaksi' => 'required',
            'total_belanja' => 'required',
        ],[
            'uang.required' => 'Uang Belum Diisi',
            'nm_dokter.required' => 'Dokter Belum Dipilih',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        if($request->uang < $request->total_belanja){
            return [
                'status' => 300,
                'message' => "Uang Tidak Cukup"
            ];
        }

        $sub_total = 0;
        $barang = Barang::all();

        $transaksi = MasterTransaksi::create([
            'kode_akun' => '411',
            'keterangan' => 'Penjualan Barang',
            'kredit' => $request->total_belanja,
            'type' => 2,
            'tanggal' => date('Y-m-d'),
            'kode' => $request->kode_transaksi
        ]);

        $akun = Akun::where('kode_akun', 111)->first();
        $akun->jumlah += $request->total_belanja;
        $akun->save();

        $akun1 = Akun::where('kode_akun', 411)->first();
        $akun1->jumlah += $request->total_belanja;
        $akun1->save();

        Log::create([
            'user_id' => Session::get('useractive')->id,
            'nomor_transaksi' => $request->kode_transaksi,
            'waktu' => date('Y-m-d H:i:s'),
            'keterangan' => 'Penjualan Barang Dari Transaksi ' . $request->kode_transaksi,
            'jumlah' => $request->total_belanja
        ]);

        Keranjang::where('created_by', Session::get('useractive')->id)->where('type', 2)->whereIn('id_barang', $request->idbarang)->delete();
        
        DB::beginTransaction();

		try{

            foreach($request->idbarang as $key => $item){
                if($barang->firstWhere('id', $item)->stok < $request->qty[$key]){
                    return [
                        'status' => 300,
                        'message' => "Transaksi Tidak Bisa Dilakukan Karena Stok Habis"
                    ];
                }

                //Ubah Stok Barang
                $barang_single = $barang->firstWhere('id', $item);
                $stok = $barang_single->stok - $request->qty[$key];
                $barang_single->stok = $stok;
                $barang_single->save();

                $sub_total = 0;
                $sub_total += $barang->firstWhere('id', $item)->harga_jual * $request->qty[$key];

                Penjualan::create([
                    'id_barang' => $item,
                    'jumlah' => $request->qty[$key],
                    'harga' => $barang->firstWhere('id', $item)->harga_jual,
                    'subtotal' => $barang->firstWhere('id', $item)->harga_jual * $request->qty[$key],
                    'dokter_id' => $request->nm_dokter,
                    'kode_penjualan' => $request->kode_penjualan,
                    'id_transaksi' => $transaksi->id
                ]);  
            }

            DB::commit();
            
            MasterTransaksi::create([
                'kode_akun' => '111',
                'keterangan' => 'Kas',
                'debt' => $request->total_belanja,
                'type' => 3,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
            ]);
            
            
            return [
                'status' => 200,
                'message' => 'Transaksi Berhasil Dilakukan'
            ];
        }

		catch(\Exception $e){
            DB::rollback();

			return [
				'status' 	=> 300, // GAGAL
				'message'       => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error'
			];

		}

    }

    public function add_keranjang(Request $request){
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        $request->request->add(['created_by' => Session::get('useractive')->id]);
        $request->request->add(['created_at' => Carbon::now()->toDateTimeString()]);

        Keranjang::create($request->all());
        
        return [
            'status' => 200
        ];
    }

    public function delete_keranjang($id){

        $delete = Keranjang::find($id);

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
