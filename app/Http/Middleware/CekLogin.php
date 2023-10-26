<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CekLogin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); //jangan lupa berikan name pada route loginnya
        }


        if (auth()->user()->level == 'superadmin' && Auth::check()) {
            return $next($request);
        } else if (auth()->user()->level == 'kasir') {
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['transaksi/penjualan', 'master/pasien', 'master/dokter', 'transaksi/penjualan', 'transaksi/pembelian', 'transaksi/obat-racik', 'transaksi/obat-racik', 'jurnal/jurnal-penjualan', 'laporan/penjualan', '', 'grafik', 'logout', 'jurnal/jurnal-penjualan']; // Replace with your desired prefixes
            $currentPrefix = $request->route()->getPrefix();

            // dd($currentPrefix);
            if (in_array($currentPrefix, $allowedPrefixes)) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else if (auth()->user()->level == 'pembelian' && Auth::check()) {
            // dd('pembelian');
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['master/barang', 'master/supplier', 'master/obat-racik', 'transaksi/pembelian', 'jurnal/jurnal-pembelian', 'laporan/pembelian', 'laporan/persediaan', '', 'grafik', 'logout']; // Replace with your desired prefixes
            $currentPrefix = $request->route()->getPrefix();
            if (in_array($currentPrefix, $allowedPrefixes)) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else if (auth()->user()->level == 'apoteker' && Auth::check()) {

            // dd('apoteker');
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['master/barang', 'master/obat-racik',  '', 'grafik', 'logout']; // Replace with your desired prefixes
            $currentPrefix = $request->route()->getPrefix();
            if (in_array($currentPrefix, $allowedPrefixes)) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else {
            return redirect()->route('login'); //jangan lupa berikan name pada route loginnya
        }
    }
}
