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
        <h1 class="h3 mb-2">Master User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active"><a href="#">User</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tabel User</h6>
            <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
						onclick="add();"><i class="fa fa-plus mr-1"></i>
						Tambah User</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Nama</th>
                            <th>Level</th>
                            <th class="text-center">Email</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                            <th>Created At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->level }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ isset($item->user_created_by->name) ? $item->user_created_by->name : '-' }}</td>
                            <td>{{ isset($item->user_updated_by->name) ? $item->user_updated_by->name : '-' }}</td>
                            <td>{{ $item->created_at_desc }}</td>
                            <td>
                                <a style='color: white;' onclick="edit('user/detail/{{ $item->id }}')">
                                    <button class='btn btn-info btn-sm mr-1'><i class='fa fa-edit'></i></button>
                                </a>
                                <a style='color: white'; Onclick="delete_action('user/delete/{{ $item->id }}', '{{ $item->name }}')">
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
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" action="{{ route('user') }}/store" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nama user</label>
                              <input class="form-control" type="text" id="nama_user" name="nama_user" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-nama_user-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" id="level" class="form-control">
                                <option value="superadmin">Superadmin</option>
                                <option value="kasir">Kasir</option>
                                <option value="pembelian">Pembelian</option>
                                <option value="apoteker">Apoteke/Dokter</option>
                            </select>
                              <span class="d-flex text-danger invalid-feedback" id="invalid-level-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Email</label>
                              <input class="form-control" type="text" id="email" name="email" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-email-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Password</label>
                                <input class="form-control" type="password" id="password" name="password" >
                                <span class="d-flex text-danger invalid-feedback" id="invalid-password-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Re-Type Password</label>
                                <input class="form-control" type="password" id="password_retype" name="password_retype" >
                                <span class="d-flex text-danger invalid-feedback" id="invalid-password_retype-feedback"></span>
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

 <div class="modal fade" role="dialog" id="modal_update" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_update" action="{{ route('user') }}/update" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nama user</label>
                              <input class="form-control" type="text" id="name" name="name" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-name-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" id="level" class="form-control">
                                <option value="superadmin">Superadmin</option>
                                <option value="kasir">Kasir</option>
                                <option value="pembelian">Pembelian</option>
                                <option value="apoteker">Apoteke/Dokter</option>
                            </select>
                              <span class="d-flex text-danger invalid-feedback" id="invalid-level-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Email</label>
                              <input class="form-control" type="text" id="email" name="email" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-email-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                           <div class="d-flex justify-content-between align-items-center">
                               <label>Change Password</label>
                                <span class="text-danger float-right d-block" style="text-align: end; font-size: .7rem">*Tidak Perlu Diisi Bila Password Tidak Diubah</span>
                            </div>
                            <input class="form-control" type="password" id="change_password" name="change_password" >
                            <span class="d-flex text-danger invalid-feedback" id="invalid-change_password-feedback"></span>
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
					targets: [0, 2, 4, 5, 6, 7]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [6, 7]
				}
			],
		});
	});

    function add(){
        $("#modal").modal('show');
        $(".modal-title").text('Tambah Dokter');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }

    function edit_action(url, modal_text){
       save_method = 'edit';
       $("#modal_update").modal('show');
       $(".modal-title").text(modal_text);
       $("#modal_loading").modal('show');
       $('.invalid-feedback').text('');
       $('input, select').removeClass('is-invalid');
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
                   elem_name.val(response[key]);
                }
             });
          },error: function (jqXHR, textStatus, errorThrown){
             setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
             swal("Oops! Terjadi kesalahan segera hubungi tim IT (" + errorThrown + ")", {  icon: 'error', });
          }
       });
    }

    $('#form_update').submit(function(e){
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
                   url:  $('#form_update').attr('action'),
                   type: $('#form_update').attr('method'),
                   enctype: 'multipart/form-data',
                   data: new FormData($('#form_update')[0]),
                   cache: false,
                   contentType: false,
                   processData: false,
                   success: function(response) {
                      setTimeout(function () {  $('#modal_loading').modal('hide'); }, 500);
                      if(response.status == 200){
                         $("#form_update")[0].reset();
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

    function edit(url){
        edit_action(url, 'Edit Dokter');
        $("#type").val('update');
    }
</script>
    
</script>
@endsection