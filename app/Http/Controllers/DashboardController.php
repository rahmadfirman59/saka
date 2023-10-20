<?php

namespace App\Http\Controllers;
use App\Models\Akun;
use App\Models\Barang;
use Carbon\Carbon;
use App\Models\MasterTransaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $data['akuns'] = Akun::whereBetween('kode_akun', [611, 666])->orderBy('kode_akun')->get();
        $data['total'] = $data['akuns']->sum('jumlah');
        $data['hutangDagang'] = MasterTransaksi::whereHas('pembelian', function ($query) {
            $query->where('status', 2);
        })->with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status', 'tgl_tempo')->with('supplier');
        }])->where('type', 1)->get();
        $data['barang_ed'] = Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->orderBy('ed', 'DESC')->get();
        $data['barang_stok'] = Barang::where('stok', '<=', 10)->orderBy('ed', 'DESC')->get();
        return view('dashboard.dashboard', $data);
    }
}
