@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Master Supplier</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active"><a href="#">Supplier</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tabel Supplier</h6>
            <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
						onclick="add();"><i class="fa fa-plus mr-1"></i>
						Tambah Supplier</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>Kota</th>
                            <th>No. Telp</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($supplier as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_supplier }}</td>
                            <td>{{ $item->kota }}</td>
                            <td>{{ $item->no_telp }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>
                                <button class='btn btn-info btn-sm mr-1'><a style='color: white;' onclick="edit('supplier/detail/{{ $item->id }}')"><i class='fa fa-edit'></i></a></button>
                                <button class='btn btn-danger btn-sm'><a style='color: white'; Onclick="delete_action('supplier/delete/{{ $item->id }}', '{{ $item->nama_supplier }}')"><i class='bi bi-trash-fill'></i></a></button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" role="dialog" id="modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" action="/saka/master/supplier/store-update" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nama Supplier</label>
                              <input class="form-control" type="text" id="nama_supplier" name="nama_supplier" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-nama_supplier-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>No. Telp</label>
                              <input class="form-control" type="text" id="no_telp" name="no_telp" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-no_telp-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Kota</label>
                              <input class="form-control" type="text" id="kota" name="kota" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-kota-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" style="height: 100px" id="alamat" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
             </div>
             <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Simpan</button>
             </div>
          </form>
       </div>
    </div>
 </div>
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            // 'Authorization': '{{session()->get('token_jwt')}}',
        }
    });

	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0, 3, 5]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [5]
				}
			],
		});
	});

    function add(){
        $("#modal").modal('show');
        $(".modal-title").text('Tambah Supplier');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }

    function edit(url){
        edit_action(url, 'Edit Supplier');
        $("#type").val('update');
    }
</script>
    
</script>
@endsection