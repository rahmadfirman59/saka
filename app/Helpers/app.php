<?php
namespace App\Helpers;
use App\Models\Barang;
use Illuminate\Support\Carbon;

class App {
    public static function get_barang_expired(){
        return Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->orderBy('ed', 'DESC')->get();
    }

    public static function count_barang_expired(){
        return Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->count();
    }

    public static function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
    
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}