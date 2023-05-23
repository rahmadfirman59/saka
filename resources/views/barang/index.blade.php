@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    .swal-text{
        text-align: center;
    }

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
                            onclick="add();"><i class="fa fa-plus mr-1"></i>
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
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th>No. Batch</th>
                            <th style="white-space: nowrap">Harga Beli</th>
                            <th style="white-space: nowrap">Harga Jual</th>
                            <th>Stok</th>
                            <th>Dibuat</th>
                            <th style="width: 10%">Aksi</th>
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
                            <td>{{ "Rp. ".number_format($item->harga_beli, 0 , ',' , '.' ) }}</td>
                            <td>{{ "Rp. ".number_format($item->harga_jual, 0 , ',' , '.' ) }}</td>
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

<div class="modal fade" role="dialog" id="modal_edit" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload_edit" action="/saka/master/barang/store-update" method="POST" autocomplete="off">
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
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Beli</label>
                            <input type="text" readonly name="harga_beli" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control">
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
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Satuan</th>
                                <th>No. Batch</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Restore</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(!empty($deleted_barang))
                            @foreach ($deleted_barang as $key=> $item )
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ $item->no_batch }}</td>
                                <td>{{ "Rp. ".number_format($item->harga_beli, 2 , ',' , '.' ) }}</td>
                                <td>{{ "Rp. ".number_format($item->harga_jual, 2 , ',' , '.' ) }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>
                                    <button class='btn btn-info btn-sm'><a style='color: white'; Onclick="restore_barang('barang/restore/{{ $item->id }}', '{{ $item->nama_barang }}')"><i class='fas fa-history'></i></a></button>
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
					targets: [0, 3, 4, 7, 8, 9]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [9]
				},
			],
            search: {
                search: searchInput,
                
            }
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
        $(".modal-title").text('Tambah Barang');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }

    function edit(url){
       save_method = 'edit';
       $("#modal_edit").modal('show');
       $(".modal-title").text('Edit Barang');
       $("#modal_loading").modal('show');
       $.ajax({
          url : url,
          type: "GET",
          dataType: "JSON",
          success: function(response){
             setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
             Object.keys(response).forEach(function (key) {
                 var elem_name = $('[name=' + key + ']');
                 console.log(elem_name.val(response[key]));
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
                   elem_name.val(response[key]);
                }
             });
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
                url: '/saka/master/' + url,
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