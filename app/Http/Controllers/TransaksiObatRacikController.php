<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\ObatRacik;
use App\Models\HistoryPasien;
use App\Models\Penjualan;
use App\Models\MasterTransaksi;
use App\Models\Barang;
use App\Models\Akun;
use App\Models\Log;
use App\Models\RacikBarang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiObatRacikController extends Controller
{
    public function index()
    {
        $data['dokters'] = Dokter::all();
        $data['pasiens'] = Pasien::all();
        $data['count_obat_racik'] = ObatRacik::count() + 1;
        $data['obat_racik'] = ObatRacik::with('barangs')->orderBy('created_at', 'desc')->get();
        $data['keranjang'] = Keranjang::with('obat_racik.barangs')->where('created_by', Session::get('useractive')->id)->where('type', 3)->get();
        // return $data['keranjang'];
        return view('transaksi.obat-racik', $data);
    }

    public function add_keranjang(Request $request){
        $validator = Validator::make($request->all(), [
            'id_racik' => 'required',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        $keranjang = Keranjang::where('type', 3)->where('id_racik', $request->id_racik)->count();
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

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'uang' => 'required',
            'nm_dokter' => 'required',
            'nm_pasien' => 'required',
            'idbarang' => 'required',
            'qty' => 'required',
            'kode_transaksi' => 'required',
            'total_belanja' => 'required',
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
        
        DB::beginTransaction();

		try{
        //!! VALIDATION FOR STOCK RACIK
        //     foreach($request->idbarang as $key => $item){
        //         if($request->tipe[$key] == 0){
        //             if($barang->firstWhere('id', $item)->stok < $request->qty[$key]){
        //                 return [
        //                     'status' => 300,
        //                     'message' => "Transaksi Tidak Bisa Dilakukan Karena Stok " . $barang->firstWhere('id', $item)->nama_barang . " Telah Habis"
        //                 ];
        //             }
        //         } else {
        //             if($barang->firstWhere('id', $item)->stok_grosir < $request->qty[$key]){
        //                 return [
        //                     'status' => 300,
        //                     'message' => "Transaksi Tidak Bisa Dilakukan Karena Stok Grosir" . $barang->firstWhere('id', $item)->nama_barang . " Telah Habis"
        //                 ];
        //             }
        //         }

        //         if($barang->firstWhere('id', $item)->ed <= Carbon::today()->addDays(30)->format('Y-m-d')){
        //             return [
        //                 'status' => 300,
        //                 'message' => "Transaksi Tidak Bisa Dilakukan Karena Barang " . $barang->firstWhere('id', $item)->nama_barang . " Telah Expired"
        //             ];
        //         }
        //     }
        //!! END VALIDATION FOR STOCK RACIK

            MasterTransaksi::create([
                'kode_akun' => '111',
                'keterangan' => 'Kas',
                'debt' => $request->total_belanja,
                'type' => 3,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            $transaksi = MasterTransaksi::create([
                'kode_akun' => '411',
                'keterangan' => 'Penjualan Obat Racik',
                'kredit' => $request->total_belanja,
                'type' => 2,
                'tanggal' => date('Y-m-d'),
                'kode' => $request->kode_transaksi,
                'potongan' => $request->potongan
            ]);

            $hpp = 0;
            $barang = Barang::all();
            foreach($request->idbarang as $keys => $item){
                // ?? Ubah Stok Barang
                $sub_total = 0;
                $loop = RacikBarang::with(['barang' => function($query){
                    $query->select(['id', 'nama_barang', 'harga_jual_tablet']);
                }])->where('id_racik', $item)->get();
                $harga = 0;

                foreach($loop as $key => $id_barang){
                    $barang_single = $barang->find($id_barang->id_barang);
                    $hpp += round($barang_single->harga_beli / $barang_single->jumlah_pecahan) * $id_barang->jumlah;
                    $stok_pecahan = ($barang_single->stok * $barang_single->jumlah_pecahan + $barang_single->sisa_pecahan) - ($id_barang->jumlah * $request->qty[$keys]);
                    $stok = floor($stok_pecahan / $barang_single->jumlah_pecahan);
                    $stok_grosir = floor($stok / $barang_single -> jumlah_grosir);
                    $sisa_pecahan = $stok_pecahan % $barang_single->jumlah_pecahan;
                    $barang_single->stok = $stok;
                    $barang_single->stok_grosir = $stok_grosir;
                    $barang_single->sisa_pecahan = $sisa_pecahan;
                    $barang_single->save();
                    $harga += $id_barang->jumlah * $id_barang->barang->harga_jual_tablet;
                };

                $sub_total += $harga * $request->qty[$keys];

                Penjualan::create([
                    'id_racik' => $item,
                    'jumlah' => $request->qty[$keys],
                    'harga' => $harga,
                    'subtotal' => $sub_total,
                    'dokter_id' => $request->nm_dokter,
                    'kode_penjualan' => $request->kode_penjualan,
                    'tipe' => 2,
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
                'kode_akun' => '111',
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
            
            $akun = Akun::where('kode_akun', 111)->first();
            $akun->jumlah += $request->total_belanja;
            $akun->save();
            
            $akun1 = Akun::where('kode_akun', 411)->first();
            $akun1->jumlah += $request->total_belanja;
            $akun1->save();

            $akun2 = Akun::where('kode_akun', 119)->first();
            $akun2->jumlah += $hpp;
            $akun2->save();

            $akun2 = Akun::where('kode_akun', 113)->first();
            $akun2->jumlah -= $hpp;
            $akun2->save();
            
            Log::create([
                'user_id' => Session::get('useractive')->id,
                'nomor_transaksi' => $request->kode_transaksi,
                'waktu' => date('Y-m-d H:i:s'),
                'keterangan' => 'Penjualan Obat Racik Dari Transaksi ' . $request->kode_transaksi,
                'jumlah' => $request->total_belanja
            ]);
            
            Keranjang::where('created_by', Session::get('useractive')->id)->where('type', 3)->whereIn('id_racik', $request->idbarang)->delete();
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
