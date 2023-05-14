<?php

namespace App\Http\Controllers;

use App\Models\MasterTransaksi;
use Illuminate\Http\Request;

class JurnalUmumController extends Controller
{
    
    
    public function index()
    {
        $transaksi = MasterTransaksi::all();
        return view('jurnal.jurnal-umum')
            ->with('transaksi', $transaksi);
    }

    public function change_priode(Request $request){
        $tanggal1=$request->thn_1.'-'.$request->bln_1.'-'.$request->tgl_1;
		$tanggal2=$request->thn_2.'-'.$request->bln_2.'-'.$request->tgl_2;
        return MasterTransaksi::whereBetween('tanggal', [$tanggal1, $tanggal2])->get();
    }
}
