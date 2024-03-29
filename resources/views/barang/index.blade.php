@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    .swal-text{
        text-align: center;
    }

    .modal {overflow-y: scroll!important;}

    td{
        font-size: .9rem;
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
            <div style="position: absolute;right: 12px;top: 13px;font-size: 13px!important">
                <button type="button" class="btn btn-success mr-2"
                            onclick="$('#modal_restore').modal('show');"><i class="fas fa-history mr-1"></i>
                            Restore Barang</button>
                <button type="button" style="font-size: 13px" class="btn btn-warning"
                            onclick="add()"><i class="fa fa-plus mr-1"></i>
                            Tambah Barang</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>No. Batch</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($barang as $key=> $item )
                        <tr @if($item->stok > 0 && !isset($item->harga_jual)) data-toggle="tooltip" data-placement="top" title="Harga Jual Belum Terisi" class="tr-danger" @endif>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->no_batch }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ isset($item->user_created_by->name) ? $item->user_created_by->name : '-' }}</td>
                            <td>{{ isset($item->user_updated_by->name) ? $item->user_updated_by->name : '-' }}</td>
                            <td class="d-flex">
                                <a style='color: white;' onclick="edit('barang/detail/{{ $item->id }}')">
                                    <button class='btn btn-info btn-sm mr-1'><i class='fa fa-edit'></i></button>
                                </a>
                                <a style='color: white'; onclick="delete_action('barang/delete/{{ $item->id }}', '{{ $item->nama_barang }}')">
                                    <button class='btn btn-danger btn-sm'><i class='bi bi-trash-fill'></i></button>
                                </a>
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
          <form id="form_upload" action="{{ route('barang') }}/store-update" method="POST" autocomplete="off">
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
                    <div class="col-12 col-md-3 col-lg-3">
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
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Satuan PCS</label>
                            <select name="satuan" onchange="change_satuan(this)" class="form-control form-select">
                                <option value='' selected>-Pilih Satuan-</option>
                                <option value='pcs' tipe="1">PCS</option>
                                <option value='Tablet' tipe="1">Tablet</option>
                                <option value='Ampul' tipe="0">Ampul</option>
                                <option value='Tube' tipe="0">Tube</option>
                                <option value='Flabot' tipe="0">Flabot</option>
                                <option value='Botol' tipe="0">Botol</option>
                                <option value='BOX' tipe="1">BOX</option>
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-satuan-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Satuan BOX</label>
                            <select id='satuan_grosir' name="satuan_grosir" class="form-control form-select">
                                <option value='' selected>-Pilih Satuan Grosir-</option>
                                <option value='BOX'>BOX</option>
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-satuan_grosir-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Jumlah PCS Per BOX</label>
                              <input class="form-control" type="text" id="jumlah_grosir" name="jumlah_grosir" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah_grosir-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label>Jumlah Tablet Per PCS</label>
                            <input class="form-control" type="text" disabled id="jumlah_pecahan" name="jumlah_pecahan" >
                            <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah_pecahan-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label>Stok Minim</label>
                              <input class="form-control" type="text" id="stok_minim" name="stok_minim" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-stok_minim-feedback"></span>
                        </div>
                    </div>
                </div>

                <div class="row mt-1 pt-3 container-persediaan-manual">
                    <div class="col-12">
                        <h5 style="font-size: 20px;font-weight: 700;color: #012970;">Tambah Persediaan Manual</h5>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Jumlah Grosir</label>
                            <input type="text" name="jumlah_barang" id="jumlah_barang" class="form-control">
                            <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah_barang-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label>Harga Beli Grosir</label>
                            <input type="text" name="harga_beli_grosir" id="harga_beli_grosir" class="form-control">
                            <span class="d-flex text-danger invalid-feedback" id="invalid-harga_beli_grosir-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 col-lg-5">
                        <div class="form-group">
                            <label>Kadaluarsa</label>
                            <input type="date" name="ed" id="ed_custom" class="form-control">
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

<div class="modal fade" role="dialog" id="modal_edit" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload_edit" action="{{ route('barang') }}/store-update" method="POST" autocomplete="off">
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
                            <select id='satuan' disabled class="form-control form-select">
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
                            <label>Stok BOX</label>
                              <input class="form-control" disabled type="text" id="stok_grosir" name="stok_grosir" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-stok_grosir-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Jumlah PCS Per BOX</label>
                              <input class="form-control" readonly type="text" id="jumlah_grosir" name="jumlah_grosir" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah_grosir-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Satuan Grosir</label>
                            <select id='satuan_grosir' name="satuan_grosir" class="form-control form-select">
                                <option value='' selected>-Pilih Satuan Grosir-</option>
                                <option value='BOX'>BOX</option>
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-satuan_grosir-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Stok PCS</label>
                              <input class="form-control" disabled type="text" id="stok" name="stok" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-stok-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Jumlah Tablet Per PCS</label>
                              <input class="form-control" type="number" onchange="change_jumlah_tablet(this.value)" id="jumlah_pecahan_edit" name="jumlah_pecahan" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah_pecahan-feedback"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Sisa Tablet</label>
                              <input class="form-control" type="text" id="sisa_pecahan" name="sisa_pecahan" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-sisa_pecahan-feedback"></span>
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
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Beli Grosir</label>
                            <input type="text" readonly name="harga_beli_grosir_edit" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Jual Grosir</label>
                            <input type="text" name="harga_jual_grosir" id="harga_jual_grosir" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Beli</label>
                            <input type="text" readonly name="harga_beli_edit" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Beli Tablet</label>
                            <input type="text" disabled id="harga_beli_tablet" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Jual Tablet</label>
                            <input type="text" name="harga_jual_tablet" id="harga_jual_tablet" class="form-control">
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

<div class="modal fade" role="dialog" id="modal_restore" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
            <div class="modal-header br">
                <h5 class="modal-title">Restore Deleted Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table datatable-primary table-striped table-hover datatable-jquery2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Satuan</th>
                                <th>No. Batch</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Restore</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(!empty($deleted_barang))
                            @foreach ($deleted_barang as $key=> $item )
                            <tr>
                                <td align="center">{{ $key + 1 }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ $item->no_batch }}</td>
                                <td>{{ "Rp. ".number_format($item->harga_beli, 2 , ',' , '.' ) }}</td>
                                <td>{{ "Rp. ".number_format($item->harga_jual, 2 , ',' , '.' ) }}</td>
                                <td align="center">{{ $item->stok }}</td>
                                <td align="center">
                                    <a style='color: white'; Onclick="restore_barang('barang/restore/{{ $item->id }}', '{{ $item->nama_barang }}')">
                                        <button class='btn btn-info btn-sm'><i class='fas fa-history'></i></button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="text-center">Data Kosong</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
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
        const urlParams = new URLSearchParams(window.location.search);
        let searchInput = urlParams.get('search');
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,2,3,4,5,6,7]
				},
                {
                    className: 'justify-content-center text-center', // Add this className
                    targets: [8] // Add target 8 here
                },
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [8]
				},
			],
		});

        $('.datatable-jquery2').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0, 3, 4, 7, 8]
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
            $('[data-toggle="tooltip"]').tooltip();
	});

     $('#form_upload_edit').submit(function(e){
       e.preventDefault();

       swal({
             title: 'Yakin?',
             text: 'Apakah anda yakin akan menyimpan data ini?',
             icon: 'warning',
             buttons: true,
             dangerMode: true,
       })
       .then((willDelete) => {
             if (willDelete) {
                $("#modal_loading").modal('show');
                $.ajax({
                   url:  $('#form_upload_edit').attr('action'),
                   type: $('#form_upload_edit').attr('method'),
                   enctype: 'multipart/form-data',
                   data: new FormData($('#form_upload_edit')[0]),
                   cache: false,
                   contentType: false,
                   processData: false,
                   success: function(response) {
                      setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
                      if(response.status == 200){
                         $("#form_upload_edit")[0].reset();
                         $("#path_file_text").text("");
                         $("#modal").modal('hide');
                         swal(response.message, { icon: 'success', }).then(function() {
                           location.reload();
                         });
                        //  tb.ajax.reload(null, false);
                      }else {
                        Object.keys(response.message).forEach(function (key) {
                              var elem_name = $('[name=' + key + ']');
                              var elem_feedback = $('[id=invalid-' + key + '-feedback' + ']');
                              elem_name.addClass('is-invalid');
                              elem_feedback.text(response.message[key]);
                        });
                     }
                   },error: function (jqXHR, textStatus, errorThrown){
                      setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
                      swal("Oops! Terjadi kesalahan segera hubungi tim IT (" + errorThrown + ")", {  icon: 'error', });
                   }
                });
             }
       });
   })

    function add(){
        $("#modal").modal('show');
        $("#modal .modal-title").text('Tambah Barang');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
        
        // Get the current date
        const currentDate = new Date();

        // Add 2 years to the current date
        currentDate.setFullYear(currentDate.getFullYear() + 2);

        // Format the date as "YYYY-MM-DD"
        const formattedDate = currentDate.toISOString().split('T')[0];

        // Set the default value of the date input field using JavaScript
        document.getElementById("ed_custom").value = formattedDate;
    }

    function change_satuan(element){
        let tipe = $(element).find("option:selected").attr('tipe');
        if(tipe == 1){
            $('#jumlah_pecahan').prop("disabled", false);
        } else {
            $('#jumlah_pecahan').prop("disabled", true);
            $('#jumlah_pecahan').val("");
        }
    }

    function change_jumlah_tablet(value){
        if(value < 1){
            $('#jumlah_pecahan_edit').val(1);
            return;
        }
        let harga_beli_tablet = parseInt($('[name=harga_beli_edit]').val()) / parseInt(value);
        $('#harga_beli_tablet').val(harga_beli_tablet.toFixed());
    }

    function edit(url){
       save_method = 'edit';
       $("#modal_edit").modal('show');
       $(".modal-title").text('Edit Barang');
       $("#modal_loading").modal('show');
       $('.invalid-feedback').text('');
       $('input, select').removeClass('is-invalid');
       $('#harga_beli_tablet').parent().show()
       $('#harga_jual_tablet').parent().show()
       $('#jumlah_pecahan_edit').parent().parent().show()
       $.ajax({
          url : url,
          type: "GET",
          dataType: "JSON",
          success: function(response){
             setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
             Object.keys(response).forEach(function (key) {
                var elem_name = $('[name=' + key + ']');
                if (elem_name.hasClass('selectric')) {
                   elem_name.val(response[key]).change().selectric('refresh');
                }else if(elem_name.hasClass('select2')){
                   elem_name.select2("trigger", "select", { data: { id: response[key] } });
                }else if(elem_name.hasClass('selectgroup-input')){
                   $("input[name="+key+"][value=" + response[key] + "]").prop('checked', true);
                }else if(elem_name.hasClass('my-ckeditor')){
                   CKEDITOR.instances[key].setData(response[key]);
                }else if(elem_name.hasClass('summernote')){
                  elem_name.summernote('code', response[key]);
                }else if(elem_name.hasClass('custom-control-input')){
                   $("input[name="+key+"][value=" + response[key] + "]").prop('checked', true);
                }else if(elem_name.hasClass('time-format')){
                   elem_name.val(response[key].substr(0, 5));
                }else if(elem_name.hasClass('format-rp')){
                   var nominal = response[key].toString();
                   elem_name.val(nominal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
                }else{
                    if(key == 'harga_beli'){
                        $('[name=harga_beli_edit]').val(response[key]);
                    } else if (key == 'harga_beli_grosir'){
                        $('[name=harga_beli_grosir_edit]').val(response[key]);
                    }else if (key == 'satuan'){
                        $('#satuan').val(response[key]);
                    }else {
                        elem_name.val(response[key]);
                    }
                }
             });
             const ValidationSatuan = ["Ampul", "Tube", "Flatbot", "Botol"];
             if (ValidationSatuan.includes(response['satuan'])) {
                $('#harga_beli_tablet').parent().hide()
                $('#harga_jual_tablet').parent().hide()
                $('#jumlah_pecahan_edit').parent().parent().hide()
             }
             $('#harga_beli_tablet').val((response['harga_beli'] / response['jumlah_pecahan']).toFixed());
          },error: function (jqXHR, textStatus, errorThrown){
             setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
             swal("Oops! Terjadi kesalahan segera hubungi tim IT (" + errorThrown + ")", {  icon: 'error', });
          }
       });
    }

    function restore_barang(url, nama){
        swal({
             title: 'Yakin?',
             text: `Apakah Anda Yakin Ingin Mengembalikan Data ${nama} ke Tabel Barang ?`,
             icon: 'warning',
             buttons: true,
             dangerMode: true,
       })
       .then((willDelete) => {
            if (willDelete) {
            $("#modal_loading").modal('show');
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    setTimeout(function () {
                        $('#modal_loading').modal('hide');
                    }, 500);
                    if (response.status === 200) {
                        swal(response.message, { icon: 'success', }).then(function() {
                           location.reload();
                        });
                    } else {
                        swal(response.message, {
                            icon: 'error',
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    setTimeout(function () {
                        $('#modal_loading').modal('hide');
                    }, 500);
                    swal("Oops! Terjadi kesalahan (" + errorThrown + ")", {
                        icon: 'error',
                    });
                }
            })
            }
       });
    }
</script>
    
</script>
@endsection