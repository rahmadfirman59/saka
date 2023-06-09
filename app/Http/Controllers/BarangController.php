<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use Illuminate\Support\Facades\Session;

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
            'no_batch' => 'required',
            'jenis' => 'required',
            'satuan_grosir' => 'required',
            'jumlah_grosir' => 'required|numeric',
            'satuan' => 'required',
            'stok_minim' => 'required|numeric',
            'ed' => 'required|date',
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
        
        if(isset($request->id)){
            $request->request->add(['updated_by' => Session::get('useractive')->id]);
        }
        
        Barang::updateOrCreate(['id' => $request->id],$request->all() );

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
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
