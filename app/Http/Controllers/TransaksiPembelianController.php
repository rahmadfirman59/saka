<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\MasterTransaksi;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian;
use App\Models\Akun;
use App\Models\Supplier;
use App\Models\Log;
use Carbon\Carbon;

class TransaksiPembelianController extends Controller
{
    
    public function index(){
        $data['suppliers'] = Supplier::all();
        $data['count_pembelian'] = Pembelian::count() + 1;
        $data['barang'] = Barang::orderBy('nama_barang', 'asc')->get();
        $data['keranjang'] = Keranjang::with('barang')->where('created_by', Session::get('useractive')->id)->where('type', 1)->get();
        return view('transaksi.pembelian', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'idbarang.*' => 'required',
            'qty.*' => 'required',
            'harga.*' => 'required',
            'ed.*' => 'required',
            'nm_supplier' => 'required',
            'no_faktur' => 'required',
            'tgl_faktur' => 'required',
            'status' => 'required',
            'kode_transaksi' => 'required',
        ], [
            'harga.*.required' => 'Harga Barang Harus Diisi',
            'ed.*.required' => 'Kadaluarsa Harus Diisi',
            'qty.*.required' => 'Quantity Barang Harus Diisi',
            'idbarang.*.required' => 'Barang Tidak Boleh Kosong',
        ]);

        
        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }
        
        if($request->status == 2 && $request->tgl_tempo == null){
            return [
                'status' => 300,
                'message' => "Tanggal Jatuh Tempo Belum Diisi"
            ];
        }

        $sub_total = 0;
        $barang = Barang::all();

        if($request->status == 1){
            $transaksi = MasterTransaksi::create([
                'kode_akun' => '113',
                'keterangan' => 'Pembelian Barang',
                'debt' => $request->total_belanja,
                'type' => 1,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            MasterTransaksi::create([
                'kode_akun' => '111',
                'keterangan' => 'Kas',
                'kredit' => $request->total_belanja,
                'type' => 3,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            Log::create([
                'user_id' => Session::get('useractive')->id,
                'nomor_transaksi' => $request->kode_transaksi,
                'waktu' => date('Y-m-d H:i:s'),
                'keterangan' => 'Pembelian Barang Dari Transaksi ' . $request->kode_transaksi,
                'jumlah' => $request->total_belanja
            ]);

            $akun = Akun::where('kode_akun', 112)->first();
            $akun->jumlah += $request->total_belanja;
            $akun->save();

            $akun2 = Akun::where('kode_akun', 111)->first();
            $akun2->jumlah -= $request->total_belanja;
            $akun2->save();

        } else {
            $transaksi = MasterTransaksi::create([
                'kode_akun' => '211',
                'keterangan' => 'Hutang Dagang',
                'kredit' => $request->total_belanja,
                'type' => 1,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            MasterTransaksi::create([
                'kode_akun' => '111',
                'keterangan' => 'Pembelian Barang Kas',
                'debt' => $request->total_belanja,
                'type' => 3,
                'kode' => $request->kode_transaksi,
                'tanggal' => date('Y-m-d'),
                'potongan' => $request->potongan
            ]);

            $akun = Akun::where('kode_akun', 211)->first();
            $akun->jumlah += $request->total_belanja;
            $akun->save();
        }

        Keranjang::where('created_by', Session::get('useractive')->id)->where('type', 1)->whereIn('id_barang', $request->idbarang)->delete();
        
        DB::beginTransaction();

		try{

            foreach($request->idbarang as $key => $item){

                //Ubah Stok Barang
                $barang_single = $barang->firstWhere('id', $item);
                $stok = $barang_single->stok + $request->qty[$key];
                $barang_single->stok = $stok;
                $barang_single->harga_beli = $request->harga[$key];
                $barang_single->ed = $request->ed[$key];
                if(isset($request->no_batch[$key])){
                    $barang_single->no_batch = $request->no_batch[$key];
                }
                $barang_single->save();

                $sub_total = 0;

                $sub_total += $request->harga[$key] * $request->qty[$key];

                Pembelian::create([
                    'id_barang' => $item,
                    'id_transaksi' => $transaksi->id,
                    'id_supplier' => $request->nm_supplier,
                    'no_faktur' => $request->no_faktur,
                    'tgl_faktur' => $request->tgl_faktur,
                    'status' => $request->status,
                    'jumlah' => $request->qty[$key],
                    'harga' => $request->harga[$key],
                    'total' => $sub_total,
                    'tgl_tempo' => $request->tgl_tempo,
                ]);  
            }
            DB::commit();
            
            // MasterTransaksi::create([
            //     'kode_akun' => '111',
            //     'keterangan' => 'Kas',
            //     'debt' => $request->total_belanja,
            //     'type' => 3
            // ]);
            
            
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
