<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CekLogin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); //jangan lupa berikan name pada route loginnya
        }

        if (Session::get('useractive')->level == 'superadmin' && Auth::check()) {
            return $next($request);
        } else if (Session::get('useractive')->level == 'kasir') {
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['transaksi/penjualan', 'transaksi/obat-racik', 'master/pasien', 'master/dokter', 'master/obat-racik', '', 'grafik', 'logout', 'jurnal/jurnal-penjualan', 'laporan/penjualan']; // Replace with your desired prefixes
            $currentPrefix = $request->route()->getPrefix();

            // dd($currentPrefix);
            if (in_array($currentPrefix, $allowedPrefixes)) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else if (Session::get('useractive')->level == 'pembelian' && Auth::check()) {
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['master/barang', 'transaksi/obat-racik', 'master/supplier', 'transaksi/pembelian', 'jurnal/jurnal-pembelian', 'laporan/pembelian', 'laporan/persediaan', 'grafik', 'logout']; // Replace with your desired prefixes
            $currentPrefix = $request->route()->getPrefix();
            if (in_array($currentPrefix, $allowedPrefixes)) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else if (Session::get('useractive')->level == 'apoteker' && Auth::check()) {
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['master/barang', 'master/obat-racik', '', 'grafik', 'logout']; // Replace with your desired prefixes
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
