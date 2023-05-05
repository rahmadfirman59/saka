@extends('layouts.layouts')
@section('content')
        <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Akun</h1>
                    

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Akun</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach ($akun as $key=> $item )
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->kode_akun }}</td>
                                            <td>{{ $item->nama_akun }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td><a href="{{ route('akun.edit',['id'=>$item->id]) }}" class="btn btn-primary btn-rounded">Ubah</a></td>
                                            
                                            
                                        </tr>    
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

@endsection