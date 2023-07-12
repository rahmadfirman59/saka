<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier = Supplier::with('user_created_by', 'user_updated_by')->get();
        return view('supplier.index')
            ->with('supplier', $supplier);
    }

    public function detail($id){
        return Supplier::find($id);
    }

    public function store_update(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required',
            'no_telp' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
        ],
        [
            'nama_supplier.required' => 'Nama Supplier Belum Diisi',
            'no_telp.required' => 'No Telepon Belum Diisi',
            'kota.required' => 'kota Belum Diisi',
            'alamat.required' => 'Alamat Belum Diisi',
        ]);

        if(isset($request->id)){
            $request->request->add(['updated_by' => Session::get('useractive')->id]);
        }


        if($validator->fails()){
            return [
                'status' => 300,
                'message' => $validator->errors()
            ];
        }
        
        Supplier::updateOrCreate(['id' => $request->id],$request->all() );

        return [
            'status' => 200,
            'message' => 'Data Berhasil Disimpan',
        ];
    }

    public function delete($id){
        $delete = Supplier::find($id);

        if($delete <> ""){
            $delete->delete();
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
