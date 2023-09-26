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
        <h1 class="h3 mb-2">Jurnal Penyesuaian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Jurnal</a></li>
                <li class="breadcrumb-item active"><a href="#">Jurnal Penyesuaian</a></li>
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 card-title">Data Jurnal Penyesuaian</h6>
                    <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
						onclick="add();"><i class="fa fa-plus mr-1"></i>
						Tambah Biaya</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Rekening</th>
                                    <th class="text-center">Keterangan</th>
                                    <th>Masa</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
        
                            <tbody id="tbody-jquery">
                                @foreach ($penyesuaian as $key=> $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->kode_akun }}</td>
                                    <td>{{ $item->nama_akun }}</td>
                                    <td>1 BULAN</td>
                                    <td align="right" style="text-align:end">{{ "Rp. ".number_format($item->jumlah, 2 , ',' , '.' ) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal')
<div class="modal fade" role="dialog" id="modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" action="/saka/jurnal/jurnal-penyesuaian/store-update" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" value="{{ date("Y-m-d") }}" name="tanggal" id="tanggal">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Kode Transaksi Biaya</label>
                              <input class="form-control" type="text" value="BYA{{ $biaya_id->id ?? 0 . date('Ymd') }}" readonly id="kode_biaya" name="kode_biaya" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-kode_biaya-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Pilih Biaya</label>
                            <select id="pilih_biaya" onchange="change_biaya(this.value)" name="pilih_biaya" class="form-control select2">
                                <option value="disabled">-- Pilih Biaya --</option>
                                @foreach ($penyesuaian as $item)
                                <option value="{{ $item->kode_akun }}">{{ $item->nama_akun }}</option>
                                @endforeach
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-pilih_biaya-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Kode Akun</label>
                              <input class="form-control" disabled type="text" id="kode_akun">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Jenis</label>
                              <select id="jenis" disabled class="form-control">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="1">Debit</option>
                                <option value="2">Kredit</option>
                              </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Nama Akun</label>
                              <input class="form-control" disabled type="text" id="nama_akun" >
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Jumlah</label>
                              <input class="form-control" type="text" id="jumlah" name="jumlah" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-jumlah-feedback"></span>
                        </div>
                    </div>
                    <p class="text-danger mx-2" style="font-family: 'Open Sans'; font-size: .9rem">*biaya yang sudah di posting di jurnal umum tidak bisa diubah</p>
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
		$tb = $('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,3]
				}
			],
		});
	});

    function change_biaya(value){
        $("#modal_loading").modal('show');
        $.ajax({
            url: '/saka/jurnal/jurnal-penyesuaian/detail/' + value,
            type: "GET",
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                $('#kode_akun').val(response.kode_akun);
                $('#jenis').val(response.jenis_akun_id);
                $('#jenis').select2().trigger('change');
                $('#nama_akun').val(response.nama_akun);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                swal("Oops! Terjadi kesalahan", {
                    icon: 'error',
                });
            }
        })
    }

    function add(){
        $("#modal").modal('show');
        $(".modal-title").text('Tambah Biaya');
        $("#form_upload")[0].reset();
        reset_all_select();
        $('.invalid-feedback').text('');
        $('input, select').removeClass('is-invalid');
    }
</script>
    
</script>
@endsection