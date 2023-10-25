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
use Illuminate\Validation\Rule;

class TransaksiPembelianController extends Controller
{

    public function index()
    {
        $data['suppliers'] = Supplier::all();
        $data['count_pembelian'] = Pembelian::count() + 1;
        $data['barang'] = Barang::where('is_delete', 0)->orderBy('nama_barang', 'asc')->get();
        $data['keranjang'] = Keranjang::with('barang')->where('created_by', Session::get('useractive')->id)->where('type', 1)->get();
        return view('transaksi.pembelian', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'idbarang.*' => 'required',
            'qty.*' => 'required',
            'harga.*' => 'required',
            'ed.*' => 'required',
            'nm_supplier' => 'required',
            'no_faktur' => 'required||unique:pembelian,no_faktur',
            'tgl_faktur' => 'required',
            'status' => 'required',
            'kode_transaksi' => 'required',
            'tgl_tempo' => 'required_if:status,2'
        ];
        
        if(isset($request->idbarang)){
            foreach ($request->idbarang as $index => $idbarang) {
                $same_batch_data =  DB::table('barang')
                    ->where('no_batch', 'LIKE', substr(Barang::where('id', $idbarang)->pluck('no_batch')->first(), 0, 4) . '%')
                    ->pluck('id')
                    ->toArray();
                $rules['no_batch.' . $index] = ['required', Rule::unique('barang', 'no_batch')->where(function ($query) use ($same_batch_data) {
                    $query->whereNotIn('id', $same_batch_data);
                    return $query;
                })];
            }
        } else {
            return [
                'status' => 300,
                'message' => "Keranjang Masih Kosong..!"
            ];
        }

        $validator = Validator::make($request->all(), $rules, 
        [
            'harga.*.required' => 'Harga Barang Harus Diisi',
            'ed.*.required' => 'Kadaluarsa Harus Diisi',
            'qty.*.required' => 'Quantity Barang Harus Diisi',
            'idbarang.*.required' => 'Barang Tidak Boleh Kosong',
            'nm_supplier.required' => 'Supplier Harus Diisi',
        ]);


        if ($validator->fails()) {
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        if ($request->status == 2 && $request->tgl_tempo == null) {
            return [
                'status' => 300,
                'message' => "Tanggal Jatuh Tempo Belum Diisi"
            ];
        }

        DB::beginTransaction();

        try {

            $sub_total = 0;
            $barang = Barang::all();

            if ($request->status == 1) {
                $transaksi = MasterTransaksi::create([
                    'kode_akun' => '113',
                    'keterangan' => 'Persediaan Barang',
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

                $akun = Akun::where('kode_akun', 113)->first();
                $akun->jumlah += $request->total_belanja;
                $akun->save();

                $akun2 = Akun::where('kode_akun', 111)->first();
                $akun2->jumlah -= $request->total_belanja;
                $akun2->save();

            } else if($request->status == 2){
                MasterTransaksi::create([
                    'kode_akun' => '113',
                    'keterangan' => 'Persediaan Barang',
                    'debt' => $request->total_belanja,
                    'type' => 1,
                    'kode' => $request->kode_transaksi,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $request->potongan
                ]);

                $transaksi = MasterTransaksi::create([
                    'kode_akun' => '211',
                    'keterangan' => 'Hutang Dagang',
                    'kredit' => $request->total_belanja,
                    'type' => 1,
                    'kode' => $request->kode_transaksi,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $request->potongan
                ]);

                $akun = Akun::where('kode_akun', 113)->first();
                $akun->jumlah += $request->total_belanja;
                $akun->save();

                $akun2 = Akun::where('kode_akun', 211)->first();
                $akun2->jumlah += $request->total_belanja;
                $akun2->save();
            } 
            else if($request->status == 0) {
                $transaksi = MasterTransaksi::create([
                    'kode_akun' => '113',
                    'keterangan' => 'Persediaan Barang',
                    'debt' => $request->total_belanja,
                    'type' => 1,
                    'kode' => $request->kode_transaksi,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $request->potongan
                ]);

                MasterTransaksi::create([
                    'kode_akun' => '112',
                    'keterangan' => 'Kas Bank',
                    'kredit' => $request->total_belanja,
                    'type' => 3,
                    'kode' => $request->kode_transaksi,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $request->potongan
                ]);

                $akun = Akun::where('kode_akun', 113)->first();
                $akun->jumlah += $request->total_belanja;
                $akun->save();

                $akun2 = Akun::where('kode_akun', 112)->first();
                $akun2->jumlah -= $request->total_belanja;
                $akun2->save();
            } else {
                return [
                    'status' => 300,
                    'message' => "Tipe Pembayaran Tidak Valid (Refresh Halaman dan coba lagi...!)"
                ];
            }

            Log::create([
                'user_id' => Session::get('useractive')->id,
                'nomor_transaksi' => $request->kode_transaksi,
                'waktu' => date('Y-m-d H:i:s'),
                'keterangan' => 'Pembelian Barang Dari Transaksi ' . $request->kode_transaksi,
                'jumlah' => $request->total_belanja
            ]);

            Keranjang::where('created_by', Session::get('useractive')->id)->where('type', 1)->whereIn('id_barang', $request->idbarang)->delete();

            foreach ($request->idbarang as $key => $item) {

                //Ubah Stok Barang
                $barang_single = $barang->firstWhere('id', $item);
                if ($barang_single->no_batch == $request->no_batch[$key]) {
                    $stok = $barang_single->stok_grosir + $request->qty[$key];
                    $barang_single->stok = $barang_single->stok + $request->qty[$key] * $request->qty_grosir[$key];
                    $barang_single->harga_beli_grosir = $request->harga[$key];
                    $barang_single->harga_beli = $request->harga[$key] / $request->qty_grosir[$key];
                    $barang_single->stok_grosir = $stok;
                    $barang_single->ed = $request->ed[$key];
                    $barang_single->jumlah_grosir = $request->qty_grosir[$key];
                    $barang_single->save();
                    $barang_id = $item;
                } else {
                    $new_barang = $barang_single->replicate();
                    $new_barang->created_at = Carbon::now();
                    $new_barang->created_by = Session::get('useractive')->id;
                    $new_barang->no_batch = $request->no_batch[$key];
                    $new_barang->stok_grosir = $request->qty[$key];
                    $new_barang->stok = $request->qty[$key] * $request->qty_grosir[$key];
                    $new_barang->harga_beli_grosir = $request->harga[$key];
                    $new_barang->harga_beli = $request->harga[$key] / $request->qty_grosir[$key];
                    $new_barang->jumlah_grosir = $request->qty_grosir[$key];
                    $new_barang->ed = $request->ed[$key];
                    $new_barang->save();
                    $barang_id = $new_barang->id;
                }

                $sub_total = 0;

                $sub_total += $request->harga[$key] * $request->qty[$key];

                Pembelian::create([
                    'id_barang' => $barang_id,
                    'id_transaksi' => $transaksi->id,
                    'id_supplier' => $request->nm_supplier,
                    'no_faktur' => $request->no_faktur,
                    'tgl_faktur' => $request->tgl_faktur,
                    'status' => $request->status,
                    'jumlah' => $request->qty[$key] * $barang_single->jumlah_grosir,
                    'jumlah_grosir' => $request->qty[$key],
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
                'message' => 'Transaksi Berhasil Dilakukan',
                'id' => $transaksi->id
            ];

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status'     => 300, // GAGAL
                'message'       => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
            ];
        }
    }

    public function add_keranjang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        // $keranjang = Keranjang::where('type', 1)->where('id_barang', $request->id_barang)->count();
        // if($keranjang > 0){
        //     return [
        //         'status' => 300,
        //         'message' => "Barang Sudah Ada Dalam Keranjang"
        //     ];
        // }

        $request->request->add(['created_by' => Session::get('useractive')->id]);
        $request->request->add(['created_at' => Carbon::now()->toDateTimeString()]);

        Keranjang::create($request->all());

        return [
            'status' => 200
        ];
    }

    public function delete_keranjang($id)
    {

        $delete = Keranjang::find($id);

        if ($delete <> "") {
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
