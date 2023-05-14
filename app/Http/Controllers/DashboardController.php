<?php

namespace App\Http\Controllers;
use App\Models\Akun;
use App\Models\MasterTransaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $data['akuns'] = Akun::whereBetween('kode_akun', [611, 666])->orderBy('kode_akun')->get();
        $data['total'] = $data['akuns']->sum('jumlah');
        $data['hutangDagang'] = MasterTransaksi::with(['pembelian' => function ($query) {
            $query->select('no_faktur', 'id_transaksi', 'id_supplier', 'status');
        }])->where('type', 1)->get();
        return view('dashboard.dashboard', $data);
    }
}
