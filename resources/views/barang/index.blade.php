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
        <h1 class="h3 mb-2">Master Barang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active"><a href="#">Barang</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tabel Barang</h6>
            <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
						onclick="add();"><i class="fa fa-plus mr-1"></i>
						Tambah Barang</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th>No. Batch</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($barang as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->no_batch }}</td>
                            <td>{{ "Rp. ".number_format($item->harga_beli, 2 , ',' , '.' ) }}</td>
                            <td>{{ "Rp. ".number_format($item->harga_jual, 2 , ',' , '.' ) }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>
                                <button class='btn btn-info btn-sm mr-1'><a style='color: white;' onclick="edit('barang/detail/{{ $item->id }}')"><i class='fa fa-edit'></i></a></button>
                                <button class='btn btn-danger btn-sm'><a style='color: white'; Onclick="delete_action('barang/delete/{{ $item->id }}', '{{ $item->nama_barang }}')"><i class='bi bi-trash-fill'></i></a></button>
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
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" action="/saka/master/barang/store-update" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-8 col-lg-8">
                        <div class="form-group">
                            <label>Nama Barang</label>
                              <input class="form-control" type="text" id="nama_barang" name="nama_barang" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-nama_barang-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label>No. Batch</label>
                              <input class="form-control" type="text" id="no_batch" name="no_batch" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-no_batch-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Jenis</label>
                            <select id='jenis' name="jenis" class="form-control form-select">
                                <option value='' selected>-Pilih Jenis-</option>
                                <option value='Alkes'>Alkes</option>
                                <option value='Generik'>Generik</option>
                                <option value='Paten'>Paten</option>
                                <option value='Salep'>Salep</option>
                                <option value='Oral'>Oral</option>
                                <option value='Narkotik'>Narkotik</option>
                                <option value='Pisikotropik'>Psikotropik</option>
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-jenis-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Satuan</label>
                            <select id='satuan' name="satuan" class="form-control form-select">
                                <option value='' selected>-Pilih Satuan-</option>
                                <option value='pcs'>PCS</option>
                                <option value='Tablet'>Tablet</option>
                                <option value='Ampul'>Ampul</option>
                                <option value='Tube'>Tube</option>
                                <option value='Flabot'>Flabot</option>
                                <option value='Botol'>Botol</option>
                                <option value='BOX'>BOX</option>
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-satuan-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Stok Minim</label>
                              <input class="form-control" type="text" id="stok_minim" name="stok_minim" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-stok_minim-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Kadaluarsa</label>
                            <input type="date" name="ed" id="ed" class="form-control">
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
					targets: [0, 3, 4, 7, 8, 9]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [8]
				}
			],
		});
	});

    function add(){
        $("#modal").modal('show');
        $(".modal-title").text('Tambah Barang');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }

    function edit(url){
        edit_action(url, 'Edit Barang');
        $("#type").val('update');
    }
</script>
    
</script>
@endsection