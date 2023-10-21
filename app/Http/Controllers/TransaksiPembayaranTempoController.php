<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\Pembelian;
use App\Models\Akun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Events\TransactionBeginning;

class TransaksiPembayaranTempoController extends Controller
{
    public function index()
    {
        $data['transaksi'] = MasterTransaksi::whereHas('pembelian', function ($query) {
            $query->where('status', 2);
        })->with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status', 'tgl_tempo')->with('supplier');
        }])->where('type', 1)->get();

        return view('transaksi.pembayaran-tempo', $data);
    }

    public function view_pembayaran_tempo($kode)
    {
        $data['transaksi'] = MasterTransaksi::has('pembelian')->where('kode', $kode)->first();
        $data['pembelian'] = Pembelian::with('supplier')->with('barang')->where('id_transaksi', $data['transaksi']->id)->where('status', 2)->get();
        if ($data['pembelian']->isEmpty()) {
            abort(404);
        }
        return view('transaksi.pembayaran-tempo-add', $data);
    }

    public function store_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'type_pembayaran' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        DB::beginTransaction();
        try {
            $transaksi = MasterTransaksi::has('pembelian')->where('kode', $request->kode)->first();
            if ($request->type_pembayaran == 0) {
                MasterTransaksi::create([
                    'kode_akun' => '211',
                    'keterangan' => 'Pembayaran Hutang Dagang',
                    'debt' => $transaksi->kredit,
                    'type' => 1,
                    'kode' => $request->kode,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $transaksi->potongan
                ]);

                MasterTransaksi::create([
                    'kode_akun' => '111',
                    'keterangan' => 'Kas',
                    'kredit' => $transaksi->kredit,
                    'type' => 3,
                    'kode' => $request->kode,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $transaksi->potongan
                ]);

                $akun = Akun::where('kode_akun', 211)->first();
                $akun->jumlah += $transaksi->kredit - $transaksi->potongan;
                $akun->save();

                $akun2 = Akun::where('kode_akun', 111)->first();
                $akun2->jumlah -= $transaksi->kredit - $transaksi->potongan;
                $akun2->save();
            } else {
                MasterTransaksi::create([
                    'kode_akun' => '211',
                    'keterangan' => 'Pembayaran Hutang Dagang',
                    'debt' => $transaksi->kredit,
                    'type' => 1,
                    'kode' => $request->kode,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $transaksi->potongan
                ]);

                MasterTransaksi::create([
                    'kode_akun' => '112',
                    'keterangan' => 'Pengurangan Kas Bank Untuk Pembayaran Hutang',
                    'kredit' => $transaksi->kredit,
                    'type' => 3,
                    'kode' => $request->kode,
                    'tanggal' => date('Y-m-d'),
                    'potongan' => $transaksi->potongan
                ]);

                $akun = Akun::where('kode_akun', 211)->first();
                $akun->jumlah += $transaksi->kredit - $transaksi->potongan;
                $akun->save();

                $akun2 = Akun::where('kode_akun', 112)->first();
                $akun2->jumlah -= $transaksi->kredit - $transaksi->potongan;
                $akun2->save();
            }

            Pembelian::where('id_transaksi', $transaksi->id)->update(['status' => 3, 'tgl_lunas' => now()]);

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Pelunasan Berhasil Dilakukan',
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
}
