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
use App\Models\HistoryPasien;
use App\Models\Log;
use App\Models\Pasien;
use Carbon\Carbon;


class TransaksiPenjualanController extends Controller
{
    public function index(){
        $data['dokters'] = Dokter::all();
        $data['pasiens'] = Pasien::all();
        $data['count_penjualan'] = Penjualan::count() + 1;
        $data['barang'] = Barang::where('is_delete', 0)->orderBy('nama_barang', 'asc')->get();
        $data['keranjang'] = Keranjang::with('barang')->where('created_by', Session::get('useractive')->id)->where('type', 2)->get();
        return view('transaksi.penjualan', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'uang' => 'required',
            'nm_dokter' => 'required',
            'nm_pasien' => 'required',
            'idbarang' => 'required',
            'qty' => 'required',
            'kode_transaksi' => 'required',
            'total_belanja' => 'required',
            'tipe_pembayaran' => 'required'
        ],[
            'uang.required' => 'Uang Belum Diisi',
            'nm_dokter.required' => 'Dokter Belum Dipilih',
            'nm_pasien.required' => 'Pasien Belum Dipilih',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        if($request->uang + $request->potongan < $request->total_belanja){
            return [
                'status' => 300,
                'message' => "Uang Tidak Cukup"
            ];
        }

        $sub_total = 0;
        $barang = Barang::all();
        
        DB::beginTransaction();

		try{

            foreach($request->idbarang as $key => $item){
                if($request->tipe[$key] == 0){
                    if($barang->firstWhere('id', $item)->stok < $request->qty[$key]){
                        return [
                            'status' => 300,
                            'message' => "Transaksi Tidak Bisa Dilakukan Karena Stok " . $barang->firstWhere('id', $item)->nama_barang . " Telah Habis"
                        ];
                    }
                } else {
                    if($barang->firstWhere('id', $item)->stok_grosir < $request->qty[$key]){
                        return [
                            'status' => 300,
                            'message' => "Transaksi Tidak Bisa Dilakukan Karena Stok Grosir" . $barang->firstWhere('id', $item)->nama_barang . " Telah Habis"
                        ];
                    }
                }

                if($barang->firstWhere('id', $item)->ed <= Carbon::today()->addDays(30)->format('Y-m-d')){
                    return [
                        'status' => 300,
                        'message' => "Transaksi Tidak Bisa Dilakukan Karena Barang " . $barang->firstWhere('id', $item)->nama_barang . " Telah Expired"
                    ];
                }
            }

            if($request->tipe_pembayaran == 1){
                MasterTransaksi::create([
                    'kode_akun' => '111',
                    'keterangan' => 'Kas',
                    'debt' => $request->total_belanja,
                    'type' => 3,
                    'kode' => $request->kode_transaksi,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $request->potongan
                ]);

                $akun = Akun::where('kode_akun', 111)->first();
            } else {
                MasterTransaksi::create([
                    'kode_akun' => '112',
                    'keterangan' => 'Kas Bank',
                    'debt' => $request->total_belanja,
                    'type' => 3,
                    'kode' => $request->kode_transaksi,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $request->potongan
                ]);
                
                $akun = Akun::where('kode_akun', 112)->first();
            }
            
            $akun->jumlah += $request->total_belanja;
            $akun->save();

            $transaksi = MasterTransaksi::create([
                'kode_akun' => '411',
                'keterangan' => 'Penjualan Barang',
                'kredit' => $request->total_belanja,
                'type' => 2,
                'tanggal' => date('Y-m-d'),
                'kode' => $request->kode_transaksi,
                'potongan' => $request->potongan
            ]);

            $hpp = 0;
            foreach($request->idbarang as $key => $item){
                //Ubah Stok Barang
                
                $sub_total = 0;
                $barang_single = $barang->firstWhere('id', $item);
                if(!isset($barang_single->jumlah_grosir) || $barang_single->jumlah_grosir < 1){
                    return [
                        'status' 	=> 300, // GAGAL
                        'message'       => 'Jumlah Grosir Barang ' . $barang_single->nama_barang . ' Belum Diisi'
                    ];
                }
                if($request->tipe[$key] == 0){
                    $stok = $barang_single->stok - $request->qty[$key];
                    $stok_grosir = floor($stok / $barang_single->jumlah_grosir);
                    $sub_total += $barang->firstWhere('id', $item)->harga_jual * $request->qty[$key];
                    $harga = $barang->firstWhere('id', $item)->harga_jual;
                    $hpp += $barang_single->harga_beli * $request->qty[$key];
                } else{
                    $stok = $barang_single->stok - ($request->qty[$key] * $barang_single->jumlah_grosir);
                    $stok_grosir = $barang_single->stok_grosir - $request->qty[$key];
                    $sub_total += $barang->firstWhere('id', $item)->harga_jual_grosir * $request->qty[$key];
                    $harga = $barang->firstWhere('id', $item)->harga_jual_grosir;
                    $hpp += $barang_single->harga_beli_grosir * $request->qty[$key];
                }

                $barang_single->stok = $stok;
                $barang_single->stok_grosir = $stok_grosir;
                $barang_single->save();


                Penjualan::create([
                    'id_barang' => $item,
                    'jumlah' => $request->qty[$key],
                    'harga' => $harga,
                    'subtotal' => $sub_total,
                    'dokter_id' => $request->nm_dokter,
                    'kode_penjualan' => $request->kode_penjualan,
                    'tipe' => $request->tipe[$key],
                    'id_transaksi' => $transaksi->id
                ]);  
            }

            MasterTransaksi::create([
                'kode_akun' => '119',
                'keterangan' => 'HPP',
                'debt' => $hpp,
                'type' => 5,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            MasterTransaksi::create([
                'kode_akun' => '113',
                'keterangan' => 'Persediaan Barang',
                'kredit' => $hpp,
                'type' => 5,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            HistoryPasien::create([
                'id_pasien' => $request->nm_pasien,
                'id_transaksi' => $transaksi->id,
                'tanggal' => date('Y-m-d'),
            ]);
            
            $akun1 = Akun::where('kode_akun', 411)->first();
            $akun1->jumlah += $request->total_belanja;
            $akun1->save();

            $akun2 = Akun::where('kode_akun', 119)->first();
            $akun2->jumlah += $hpp;
            $akun2->save();

            $akun3 = Akun::where('kode_akun', 113)->first();
            $akun3->jumlah -= $hpp;
            $akun3->save();
            
            Log::create([
                'user_id' => Session::get('useractive')->id,
                'nomor_transaksi' => $request->kode_transaksi,
                'waktu' => date('Y-m-d H:i:s'),
                'keterangan' => 'Penjualan Barang Dari Transaksi ' . $request->kode_transaksi,
                'jumlah' => $request->total_belanja
            ]);
            
            Keranjang::where('created_by', Session::get('useractive')->id)->where('type', 2)->whereIn('id_barang', $request->idbarang)->delete();
            
            DB::commit();

            return [
                'status' => 200,
                'message' => 'Transaksi Berhasil Dilakukan',
                'id' => $transaksi->id
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

        $keranjang = Keranjang::where('type', 2)->where('id_barang', $request->id_barang)->count();
        if($keranjang > 0){
            return [
                'status' => 300,
                'message' => "Barang Sudah Ada Dalam Keranjang"
            ];
        }

        $request->request->add(['created_by' => Session::get('useractive')->id]);
        $request->request->add(['created_at' => Carbon::now()->toDateTimeString()]);

        Keranjang::create($request->all());
        
        return [
            'status' => 200
        ];
    }

    public function get_keranjang($id){
        $data = Keranjang::where('id', $id)->with('barang')->get();
        return $data;
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
