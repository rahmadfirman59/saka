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
        <h1 class="h3 mb-2">Master Pasien</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active"><a href="#">Pasien</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tabel Pasien</h6>
            <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
						onclick="add();"><i class="fa fa-plus mr-1"></i>
						Tambah Pasien</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>No. Telp</th>
                            <th>Dibuat Oleh</th>
                            <th>Diubah Oleh</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pasien as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_pasien }}</td>
                            <td>{{ $item->no_telp }}</td>
                            <td>{{ isset($item->user_created_by->name) ? $item->user_created_by->name : '-' }}</td>
                            <td>{{ isset($item->user_updated_by->name) ? $item->user_updated_by->name : '-' }}</td>
                            <td>{{ App\Helpers\App::tgl_indo($item->created_at->format('Y-m-d')) }}</td>
                            <td>
                                <a style='color: white'; Onclick="check_history('{{ $item->id }}')">
                                    <button class='btn btn-warning btn-sm'><i class="bi bi-file-text-fill"></i></button>
                                </a>
                                <a style='color: white;' onclick="edit('pasien/detail/{{ $item->id }}')">
                                    <button class='btn btn-info btn-sm mr-1'><i class='fa fa-edit'></i></button>
                                </a>
                                <a style='color: white'; Onclick="delete_action('pasien/delete/{{ $item->id }}', '{{ $item->nama_pasien }}')">
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
    <div class="modal-dialog modal-xs" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" action="{{ route('pasien') }}/store-update" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Nama Pasien</label>
                              <input class="form-control" type="text" id="nama_pasien" name="nama_pasien" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-nama_pasien-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>No. Telp</label>
                              <input class="form-control" type="text" id="no_telp" name="no_telp" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-no_telp-feedback"></span>
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

<div class="modal fade" role="dialog" id="modal_history" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title">Histroy Pasien</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
             <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-history-jquery" id="table-history" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Potongan</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
             </div>
             <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Simpan</button>
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
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0, 2,3,4,5,6]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [6]
				}
			],
		});

	});
    
    let Datatable_history = $('.datatable-history-jquery').dataTable({
        sDom: 'lBfrtip',
        columnDefs: [{
                className: 'text-center',
                targets: [0,1,2,3,4]
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
    function add(){
        $("#modal").modal('show');
        $(".modal-title").text('Tambah Pasien');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }

    function check_history(id){
        $("#modal_loading").modal('show');
        $.ajax({
            url: "{{ route('pasien') }}/check-history/" + id,
            type: "GET",
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                $('#modal_history').modal('show');
                Datatable_history.fnClearTable();
                Datatable_history.fnDestroy();
                $.each(response, function(i, response_single){
                    if(response_single['transaksi']['potongan'] === null) {
                        response_single['transaksi']['potongan'] = 0;
                    }
                    $('#table-history tbody').append(`<tr><td>${i + 1}</td><td>${response_single['tanggal']}</td><td>${fungsiRupiah(response_single['transaksi']['potongan'])}</td><td>${fungsiRupiah(response_single['transaksi']['kredit'])}</td><td><a style='color: white'; Onclick="window.location.href = '{{ route('jurnal.penjualan') }}/detail-penjualan/${response_single['id_transaksi']}'"><button class='btn btn-info btn-sm'><i class='bi bi-file-text-fill'></i></button></a></td></tr>`);
                })
                $('.datatable-history-jquery').dataTable({
                    sDom: 'lBfrtip',
                    columnDefs: [{
                            className: 'text-center',
                            targets: [0,1,2,3,4]
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

    function edit(url){
        edit_action(url, 'Edit Pasien');
        $("#type").val('update');
    }
</script>
    
</script>
@endsection