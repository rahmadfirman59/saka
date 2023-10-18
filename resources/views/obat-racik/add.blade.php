@extends('layouts.layouts')
@section('css')
<style>
    .modal-body {
        padding: 1.4em;
    }

</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Tambah Obat Racik</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item"><a href="{{ route('obat-racik') }}">Obat Racik</a></li>
                <li class="breadcrumb-item active"><a href="#">Tambah Obat Racik</a></li>

            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tambah Obat Racik</h6>
        </div>
        <form id="form_obat_racik">
            @if(isset($data))
            <input type="text" hidden value="{{ $data->id }}" name="id">
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nama Obat Racik</label>
                            <input class="form-control" type="text" id="nama_racik" name="nama_racik" value="@if(isset($data)){{$data->nama_racik}}@endif">
                            <span class="d-flex text-danger invalid-feedback" id="invalid-nama_racik-feedback"></span>
                        </div>
                    </div>
    
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Harga</label>
                            <input class="form-control" type="text" id="harga" price="@if(isset($data)){{ $harga }}@else{{ 0 }}@endif" disabled value="@if(isset($data)){{ "Rp. ".number_format($harga, 2, ',' , '.' ) }}@else{{ "Rp. ".number_format(0, 2 , ',' , '.' ) }}@endif">
                            <span class="d-flex text-danger invalid-feedback" id="invalid-harga_racik-feedback"></span>
                        </div>
                    </div>
    
                    <div class="col-12 list-barang-container">
                        <label>List Barang</label>
                    </div>

                    @if(isset($data) && isset($list_barang))
                        @foreach ($list_barang as $item)
                        <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center list-barang-container" style="gap: 10px">
                            <div class="form-group" style="flex: 12">
                                <div class="d-flex" style="gap: 15px">
                                    <input hidden class="id_list_barang" name="id_barang[]" value="{{ $item->id_barang }}">
                                    <input class="form-control" style="flex: 2" type="text" readonly value="{{ $item->barang->nama_barang }}" >
                                    <input class="form-control harga-racik" style="flex: 1" price="{{ $item->barang->harga_jual_tablet }}" type="text" readonly value="{{ "Rp. ".number_format($item->barang->harga_jual_tablet, 0, ',' , '.' ) }}" >
                                    <input class="form-control" style="flex: 1" type="number" placeholder="Qty" onchange="changeQtyRacik(this)" value="{{ $item->jumlah }}" name="jumlah[]" >
                                </div>
                            </div>
                            <div style="flex: 1; margin-bottom: 1rem" class="d-flex justify-content-center">
                                <button type="button" class="btn btn-danger hapus-list-barang-button" onclick="hapus_list_barang(this)"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
                    @endif
    
                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-success" onclick="add_barang()"><i class="fa fa-plus mr-2"></i> Tambah Barang</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('obat-racik') }}">
                    <button type="button" class="btn btn-secondary"><i class="fas fa-chevron-left mr-2"></i> Back</button>
                </a>
                <button type="submit" class="btn btn-success"><i class="fa fa-save mr-2"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('modal')
<div class="modal fade" role="dialog" id="modal_barang" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header br">
                <h5 class="modal-title">Tabel Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table datatable-primary table-striped table-hover datatable-barang" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Stok Pecahan</th>
                                <th>Jumlah Pecahan</th>
                                <th>Harga Pecahan</th>
                                <th>Kadaluarsa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            @foreach ($barang as $key=> $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>         
                                <td>{{ $item->nama_barang }}</td>        
                                <td>{{ $item->stok * $item->jumlah_pecahan + $item->sisa_pecahan }}</td>                           
                                <td>{{ $item->jumlah_pecahan }}</td>                            
                                <td>{{ "Rp. ".number_format($item->harga_jual_tablet, 0 , ',' , '.' ) }}</td>                            
                                <td>{{ $item->ed }}</td>                           
                                <td><button onclick="tambah_list_barang({{ $item->id }}, '{{ $item->nama_barang }}', '{{ $item->harga_jual_tablet }}')" class='btn btn-info btn-sm mr-1 mx-3'><a style='color: white;'><i class='fa fa-plus'></i></a></button></td>                           
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <p class="text-danger">*Hanya Barang Dengan Jumlah Pecahan Yang Ditampilkan</p>

            </div>
       </div>
    </div>
 </div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('.datatable-barang').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,2,3,4,5]
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

    function tambah_list_barang(id, nama_barang, harga_jual){
        let shouldExit = false;
        $('.id_list_barang').each(function() {
            if ($(this).val() == id) {
            swal('Barang Sudah Dimasukkan Dalam List..!', { icon: 'error', });
            shouldExit = true;
            return false; // Exit the loop early if a match is found
            }
        });

        if (shouldExit) {
            return; // Exit the function early
        }

        $('.list-barang-container').last().after(`
            <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center list-barang-container" style="gap: 10px">
                <div class="form-group" style="flex: 12">
                    <div class="d-flex" style="gap: 15px">
                        <input hidden class="id_list_barang" name="id_barang[]" value="${id}">
                        <input class="form-control" style="flex: 2" type="text" readonly value="${nama_barang}" >
                        <input class="form-control harga-racik" style="flex: 1" price="${harga_jual}" type="text" readonly value="${fungsiRupiah(harga_jual)}" >
                        <input class="form-control" style="flex: 1" type="number" onchange="changeQtyRacik(this)" placeholder="Qty" value=1 name="jumlah[]" >
                    </div>
                </div>
                <div style="flex: 1; margin-bottom: 1rem" class="d-flex justify-content-center">
                    <button type="button" class="btn btn-danger hapus-list-barang-button" onclick="hapus_list_barang(this)"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        `);

        let hargaValue = parseInt($('#harga').attr('price')) + parseInt(harga_jual);
        $('#harga').val(fungsiRupiah(hargaValue));
        $('#harga').attr('price', hargaValue);

        if($('.list-barang-container').length > 1){
            $('.hapus-list-barang-button').show();
        }
    }

    function changeQtyRacik(element){
        if($(element).val() < 1){
            swal("Quantity Tidak Boleh Kurang dari 1", { icon: 'info', })
            $(element).val(1)
        }

        hitung()
    }

    function hitung(){
        let harga_final = 0;
        $('.harga-racik').each(function(index, harga_racik){
            harga_final += parseInt($(harga_racik).attr('price')) * parseInt($(harga_racik).next().val());
        });

        $('#harga').val(fungsiRupiah(harga_final));
        $('#harga').attr('price', harga_final);
    }

    function add_barang(){
        $('#modal_barang').modal('show');
    }

    function hapus_list_barang(element){
      $(element).parent().parent().remove();
      if($('.list-barang-container').length <= 1){
         $('.hapus-list-barang-button').hide();
      }
      hitung();
   }

   $('#form_obat_racik').submit(function(e){
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
                    url: "{{ route('obat-racik') }}/store",
                    type: "POST",
                    data: $('#form_obat_racik').serialize(),
                    success: function (response) {
                        setTimeout(function () {
                            $('#modal_loading').modal('hide');
                        }, 500);
                        if (response.status === 200) {
                            swal(response.message, { icon: 'success', }).then(function() {
                            window.location.href = "{{ route('obat-racik') }}"
                            });
                        } else {
                            swal(response.message, {
                                icon: 'error',
                            });
                        }
                        console.log(response)

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
    });
</script>
@endsection
