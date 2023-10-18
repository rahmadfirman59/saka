<?php

namespace App\Http\Controllers;

use App\Models\MasterTransaksi;
use App\Models\Akun;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class JurnalUmumController extends Controller
{
    
    
    public function index()
    {
        $data['transaksi'] = MasterTransaksi::all();
        $data['akun'] = Akun::orderBy('kode_akun', 'ASC')->get();
        return view('jurnal.jurnal-umum', $data);
    }

    public function change_priode(Request $request){
        $tanggal1=$request->thn_1.'-'.$request->bln_1.'-'.$request->tgl_1;
		$tanggal2=$request->thn_2.'-'.$request->bln_2.'-'.$request->tgl_2;
        return MasterTransaksi::whereBetween('tanggal', [$tanggal1, $tanggal2])->get();
    }

    public function reset_priode(){
        return MasterTransaksi::all();
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'pilih_akun_debit' => 'required|numeric',
            'pilih_akun_kredit' => 'required|numeric',
            'nominal_debit' => 'required|numeric',
            'nominal_kredit' => 'required|numeric',
            'uraian_debit' => 'required',
            'uraian_kredit' => 'required',
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        DB::beginTransaction();

        try{
            $akun_debit = Akun::where('kode_akun', $request->pilih_akun_debit)->first();
            $akun_debit->jumlah += $request->total_belanja;
            $akun_debit->save();

            $akun_kredit = Akun::where('kode_akun', $request->pilih_akun_kredit)->first();
            $akun_kredit->jumlah -= $request->total_belanja;
            $akun_kredit->save();

            $count_manual = MasterTransaksi::where('type', 5)->count();

            MasterTransaksi::create([
                'kode_akun' => $request->pilih_akun_debit,
                'keterangan' => $request->uraian_debit,
                'debt' => $request->nominal_debit,
                'type' => 6,
                'kode' => "MNL" . $count_manual . date('Ymd'),
                'tanggal' => date('Y-m-d'),
            ]);

            MasterTransaksi::create([
                'kode_akun' => $request->pilih_akun_kredit,
                'keterangan' => $request->uraian_kredit,
                'kredit' => $request->nominal_kredit,
                'type' => 6,
                'kode' => "MNL" . $count_manual . date('Ymd'),
                'tanggal' => date('Y-m-d'),
            ]);

            DB::commit();

            return [
                'status' => 200,
                'message' => 'Transaksi Manual Berhasil Ditambahkan',
            ];

        } catch (\Exception $e){

            DB::rollBack();
            return [
                'status' => 300,
                'message' => (env('APP_ENV', 'true') == true ? $e->getMessage() : "Operation Error")
            ];
        }

        
    }
}
