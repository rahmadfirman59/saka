<?php
namespace App\Helpers;
use App\Models\Barang;
use Illuminate\Support\Carbon;

class App {
    private static $bulan = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );


    public static function get_barang_all(){
        return Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->orwhere('stok', '<=', 10)->orderBy('ed', 'DESC')->get();
    }

    public static function get_barang_expired(){
        return Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->orderBy('ed', 'DESC')->get();
    }

    public static function get_barang_stock(){
        return Barang::where('stok', '<=', 10)->orderBy('ed', 'DESC')->get();
    }

    public static function count_barang_expired(){
        return Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->count();
    }

    public static function count_barang_stock(){
        return Barang::where('stok', '<=', 10)->count();
    }
    
    public static function count_semua(){
        return Barang::where('ed', '<=', Carbon::today()->addDays(30)->format('Y-m-d'))->count() + Barang::where('stok', '<=', 10)->count();
    }

    public static function monthToIndonesian($monthNumber) {
        $month = self::$bulan;

        return $month[$monthNumber];
    }

    public static function tgl_indo($tanggal){
        $bulan = self::$bulan;
        $pecahkan = explode('-', $tanggal);
        
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
    
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}