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
            $allowedPrefixes = ['transaksi/penjualan', 'master/pasien', 'master/dokter', '', 'grafik', 'logout', 'jurnal/jurnal-penjualan']; // Replace with your desired prefixes
            $currentPrefix = $request->route()->getPrefix();
            
            // dd($currentPrefix);
            if (in_array($currentPrefix, $allowedPrefixes)) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else if (auth()->user()->level == 'pembelian' && Auth::check()){
            // User with status 1 can access routes within the specified prefixes
            $allowedPrefixes = ['master/barang', 'transaksi/pembelian', '', 'grafik', 'logout']; // Replace with your desired prefixes
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
