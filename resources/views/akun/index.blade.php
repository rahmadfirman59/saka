@extends('layouts.layouts')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Master Akun</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active"><a href="#">Akun</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tabel Akun</h6>
            <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
						onclick="add();"><i class="fa fa-plus mr-1"></i>
						Tambah Akun</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Akun</th>
                            <th class="text-center">Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($akun as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->kode_akun }}</td>
                            <td>{{ $item->nama_akun }}</td>
                            <td>{{ "Rp. ".number_format($item->jumlah, 2 , ',' , '.' ) }}</td>
                            <td>
                                @if($item->kode_akun == 311)
                                {{-- <a style='color: white;' onclick="tambah_modal()">
                                    <button class='btn btn-warning btn-sm mr-1'><i class='fa fa-plus'></i></button>
                                </a> --}}
                                @endif
                                <a style='color: white;' onclick="edit('akun/detail/{{ $item->id }}')">
                                    <button class='btn btn-info btn-sm mr-1'><i class='fa fa-edit'></i></button>
                                </a>
                                <a style='color: white'; Onclick="delete_action('akun/delete/{{ $item->id }}', '{{ $item->nama_akun }}')">
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
          <form id="form_upload" action="{{ route('akun') }}/store-update" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Kode Akun</label>
                              <input class="form-control" type="text" id="kode_akun" name="kode_akun" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-kode_akun-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Jenis Akun</label>
                            <select id="jenis_akun_id" name="jenis_akun_id" class="form-control form-select">
                                <option value="" selected="">Pilih</option>
                                <option value="1">Debit</option>
                                <option value="2">Kredit</option>
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-jenis_akun_id-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Nama Akun</label>
                            <input class="form-control" type="text" id="nama_akun" name="nama_akun" >
                            <span class="d-flex text-danger invalid-feedback" id="invalid-nama_akun-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input class="form-control" type="text" id="jumlah" name="jumlah" >
                            <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah-feedback"></span>
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

<div class="modal fade" role="dialog" id="modal_tambah" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title">Tambah Modal</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_tambah_modal" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Modal</label>
                              <input class="form-control" type="number" id="jumlah_modal" name="jumlah_modal" >
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
            pageLength : 15,
            lengthMenu: [[15, 25, 50, 100], [15, 25, 50, 100]],
			columnDefs: [{
					className: 'text-center',
					targets: [0, 1, 4]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [4]
				}
			],
		});
	});

    function add(){
        $("#modal").modal('show');
        $(".modal-title").text('Tambah Akun');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }

    function edit(url){
        edit_action(url, 'Edit Akun');
        $("#type").val('update');
    }

    function tambah_modal(){
        $('#modal_tambah').modal('show');
        $("#form_tambah_modal")[0].reset();
    }

    $('#form_tambah_modal').submit(function(e){
        e.preventDefault();
        $('#modal_loading').modal('show');
        $.ajax({
            url: "{{ route('akun') }}/tambah-modal",
            type: "POST",
            data: $('#form_tambah_modal').serialize(),
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
    })
</script>
    
</script>
@endsection