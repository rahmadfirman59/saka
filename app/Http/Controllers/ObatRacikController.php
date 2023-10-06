<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\ObatRacik;
use App\Models\RacikBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ObatRacikController extends Controller
{
    public function index()
    {
        $data['obat_racik'] = ObatRacik::with('barangs')->orderBy('created_at', 'desc')->get();
        return view('obat-racik.index', $data);
    }

    public function add(){
        $data['barang'] = Barang::where('is_delete', 0)->where('jumlah_pecahan', '>', 0)->whereNotNull('harga_jual_tablet')->orderBy('nama_barang', 'asc')->get();
        return view('obat-racik.add', $data);
    }

    public function detail($id){
        $data['data'] = ObatRacik::find($id);
        $data['list_barang'] = RacikBarang::with(['barang' => function($query){
            $query->select(['id', 'nama_barang', 'harga_jual_tablet']);
        }])->where('id_racik', $id)->get();
        $data['barang'] = Barang::where('is_delete', 0)->where('jumlah_pecahan', '>', 0)->whereNotNull('harga_jual_tablet')->orderBy('nama_barang', 'asc')->get();
        $harga_final = 0;
        foreach($data['list_barang'] as $key => $item){
            $harga_final += $item->jumlah * $item->barang->harga_jual_tablet;
        }
        $data['harga'] = $harga_final;
        return view('obat-racik.add', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id.*' => 'required',
            'nama_racik' => 'required',
            'jumlah.*' => 'required',
        ], [
            'nama_racik.required' => 'Nama Racik Harus Diisi',
            'jumlah.*.required' => 'Quantity Barang Harus Diisi',
            'id.*.required' => 'Barang Tidak Boleh Kosong',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        
        DB::beginTransaction();
        
        try {
            $ObatRacik = ObatRacik::updateOrCreate(['id' => $request->id],['nama_racik' => $request->nama_racik]);
            
            if(isset($request->id)){
                RacikBarang::where('id_racik', $request->id)->delete();
            }

            foreach($request->id_barang as $key => $item){
                RacikBarang::create([
                    'id_racik' => $ObatRacik->id,
                    'id_barang' => $item,
                    'jumlah' => $request->jumlah[$key]
                ]);
            };
           
            DB::commit();

            return [
                'status' => 200,
                'message' => 'Obat Racik Berhasil Dibuat',
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status'     => 300, // GAGAL
                'message'       => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
            ];
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            ObatRacik::find($id)->delete();
            RacikBarang::where('id_racik', $id)->delete();
            DB::commit();
            return [
                'status' => 200,
                'message' => 'Data berhasil dihapus'
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
