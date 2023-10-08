<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Akun;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('user_created_by', 'user_updated_by')->where('is_delete', 0)->orderBy('nama_barang', 'asc')->get();
        $barang_deleted = Barang::where('is_delete', 1)->orderBy('nama_barang', 'asc')->get();
        return view('barang.index')
            ->with('barang', $barang)
            ->with('deleted_barang', $barang_deleted);
    }

    public function detail($id){
        return Barang::find($id);
    }

    public function store_update(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required',
            'no_batch' => 'required|unique:barang,no_batch,' . $request->id,
            'jenis' => 'required',
            'satuan_grosir' => 'required',
            'jumlah_grosir' => 'required|numeric',
            'satuan' => 'required_without:id',
            'stok_minim' => 'required|numeric',
            'ed' => 'required_with_all:harga_beli_grosir, jumlah_barang|date',
            'jumlah_barang' => 'numeric|required_with_all:harga_beli_grosir, ed',
            'harga_beli_grosir' => 'numeric|required_with_all:jumlah_barang, ed',
        ],
        [
            'nama_barang.required' => 'Nama Barang Belum Diisi',
            'no_batch.required' => 'No. Batch Belum Diisi',
            'jenis.required' => 'Jenis Belum Diisi',
            'satuan.required' => 'Satuan Belum Diisi',
            'satuan_grosir.required' => 'Satuan Grosir Belum Diisi',
            'jumlah_grosir.required' => 'Jumlah Grosir Belum Diisi',
            'stok_minim.required' => 'Stok Minim Belum Diisi',
            'ed.required' => 'Kadaluarsa Belum Diisi',
            'stok_minim.numeric' => 'Stok Minim Harus Angka'
        ]);

        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }

        DB::beginTransaction();
        
		try{
        
            if(isset($request->jumlah_barang) && isset($request->harga_beli_grosir) && isset($request->ed)){
                $akun_persediaan_barang = Akun::where('kode_akun', 113)->first();
                $akun_persediaan_barang->jumlah += $request->jumlah_barang * $request->harga_beli_grosir;
                $akun_persediaan_barang->save();

                $akun_modal = Akun::where('kode_akun', 311)->first();
                $akun_modal->jumlah += $request->jumlah_barang * $request->harga_beli_grosir;
                $akun_modal->save();
                
                $request->merge(["harga_beli" => $request->harga_beli_grosir / $request->jumlah_grosir]);
                $request->merge(["stok_grosir" => $request->jumlah_barang]);
                $request->merge(["stok" => $request->jumlah_barang * $request->jumlah_grosir]);
            }
            
            if(isset($request->id)){
                $request->request->add(['updated_by' => Session::get('useractive')->id]);
            }
            
            // return $request->all();
            Barang::updateOrCreate(['id' => $request->id],$request->all() );

            DB::commit();

            return [
                'status' => 200,
                'message' => 'Data Berhasil Disimpan',
            ];

        }
		catch(\Exception $e){
            DB::rollback();

			return [
				'status' 	=> 300, // GAGAL
				'message'       => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error'
			];

		}
    }

    public function restore($id){
        $delete = Barang::find($id);

        if($delete <> ""){
            // $delete->delete();
            $delete->update([
                'is_delete' => 0
            ]);
            return [
                'status' => 200,
                'message' => 'Data berhasil Direstore'
            ];
        }

        return [
            'status' => 300,
            'message' => 'Data tidak ditemukan'
        ];
    }

    public function delete($id){
        $delete = Barang::find($id);

        if($delete <> ""){
            // $delete->delete();
            $delete->update([
                'is_delete' => 1
            ]);
            return [
                'status' => 200,
                'message' => 'Data berhasil dihapus'
            ];
        }

        return [
            'status' => 300,
            'message' => 'Data tidak ditemukan'
        ];
    }
}
