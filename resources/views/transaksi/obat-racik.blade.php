@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    .table tr td{
        vertical-align: middle!important;
    }

    @media(min-width: 600px){
        #DataTables_Table_0_filter{
            position: absolute;
            top: 17px;
            right: 25px;
        }
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button {  

    opacity: 1;

    }

</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Transaksi Obat Racik</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="#">Obat Racik</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <button type="button" style="left: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
                                onclick="tambah_obat_racik();"><i class="fa fa-plus mr-1"></i>
                                Tambah Obat Racik</button>
                </div>
                <form id="form_penjualan">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Racik</th>
                                    <th>List Barang</th>
                                    <th>Stok</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                <?php $total = 0 ?>
                                @foreach ($keranjang as $key=> $item)
                                <tr>
                                    <input type="hidden" value="{{ $item->obat_racik->id }}" name="idbarang[]">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->obat_racik->nama_racik }}</td>
                                    <td>
                                        @php
                                            $harga_racik = 0;
                                        @endphp
                                        @foreach ($item->obat_racik->barangs as $barang)
                                        @php
                                            $harga_racik += $barang->harga_jual_tablet * $barang->pivot->jumlah;                   
                                        @endphp
                                            <span class="badge badge-primary" style="font-family: 'Nunito', sans-serif; padding: .35em .7em;">{{ $barang->nama_barang }} (<span class="jumlah_stock_{{ $key }}" stock="{{ $barang->pivot->jumlah }}">{{ $barang->pivot->jumlah }}</span>)</span>
                                        @endforeach
                                    </td>
                                    <td class="display_stok{{ $key }}">{{ $item->obat_racik->stok }}</td>
                                    <td style="width: 10%">
                                        <input name="qty[]" type="number" id="qty{{ $key }}" value="1" class="form-control text-center" onchange="change_qty(this.value, {{ $key }}, {{ $item->obat_racik->stok }}, {{ $harga_racik }})">
                                    </td>
                                    <td price="{{ $harga_racik }}" class="display_harga{{ $key }}">{{ "Rp. ".number_format($harga_racik, 2 , ',' , '.' ) }}</td>
                                    <td id="subtotal{{ $key }}" class="subtotal" price="{{ $harga_racik }}">{{ "Rp. ".number_format($harga_racik, 2 , ',' , '.' ) }}</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm' type="button"><a style='color: white' onclick="delete_keranjang({{ $item->id }})"><i class='bi bi-trash-fill'></i></a></button>
                                    </td>
                                </tr>
                                <?php $total+=$harga_racik ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
									<td align='right' colspan='6' style="background:rgb(249 249 249 / 86%); padding: 0.25em; vertical-align: middle"><b>Total<b></td>
                                    <td id="total" colspan="2" style="background:rgb(249 249 249 / 86%); font-weight: bold" price="<?php echo $total ?>"><b><?php echo "Rp. ".number_format($total, 2 , ',' , '.' ) ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="card-title p-0" style="display: inline-block">Kasir</h5>
                </div>
                <div class="card-body">
                    <div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Total Belanja</label>
                                <input type="text" readonly  id="total_belanja" value="<?php echo "Rp. ".number_format($total, 2 , ',' , '.' ) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Uang</label>
                                <input type="text" id="uang" name="uang" onkeyup="hitung(this.value)" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Potongan</label>
                                <input type="text" id="potongan" name="potongan" onkeyup="potongan_change(this.value)" onkeypress="return hanyaAngka(event,false);" class="form-control">
                            </div>
                        </div>
						<div class="d-flex w-100" style="font-size: 16px!important; padding-top: 15px;border-top: 1px solid #d8d1d1;margin-top: 10px;">
							<div class="p-2 fw-bold ml-2" style="color: black">
                                <label style="font-weight: bold">Kembali</label>
                                <span id="kembali" style='margin-left:10px; font-weight: bold' price=""><span style='margin-left:15px'></span>-</span>
                            </div>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title p-0" style="display: inline-block">Transaksi Obat Racik</h5>
                </div>
                <div class="card-body mt-3">
                    <div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Dokter</label>
                                <select name="nm_dokter" class="form-control select2">
                                    <option value="" selected>- Pilih Dokter -</option>
                                    @foreach ($dokters as $dokter)
                                    <option value={{ $dokter->id }}>{{ $dokter->nama_dokter }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Pasien</label>
                                <select name="nm_pasien" class="form-control select2">
                                    <option value="" selected>- Pilih Pasien -</option>
                                    @foreach ($pasiens as $pasien)
                                    <option value={{ $pasien->id }}>{{ $pasien->nama_pasien }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>No. Nota</label>
                                <input type="text" value="{{ 'PJL'.(int)$count_obat_racik.date('Ymd') }}" id="kode_transaksi" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" value="{{ date('d-m-Y') }}" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="d-flex mt-4">
                <button type="submit" style="width: 100%" class="py-2 btn btn-success" value="Simpan">Simpan</button>			
            </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('modal')
<div class="modal fade" role="dialog" id="modal_obat_racik" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header br">
                <h5 class="modal-title">Tabel Obat Racik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table datatable-primary table-striped table-hover datatable-obat-racik" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Racik</th>
                                <th>List Barang</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            @foreach ($obat_racik as $key=> $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->nama_racik }}</td>
                                <td>
                                    @php
                                        $expired = false;
                                        $harga_racik = 0;
                                    @endphp
                                    @foreach ($item->barangs as $barang)
                                        @php
                                            $harga_racik += $barang->harga_jual_tablet * $barang->pivot->jumlah;
                                            if($barang->ed <= \Carbon\Carbon::today()->addDays(30)->format('Y-m-d')){
                                                $expired = true;
                                            }
                                        @endphp
                                        <span class="badge @if($barang->ed <= \Carbon\Carbon::today()->addDays(30)->format('Y-m-d')) badge-danger @else badge-primary @endif" style="font-family: 'Nunito', sans-serif; padding: .35em .7em;">{{ $barang->nama_barang }} ({{ $barang->pivot->jumlah }}) @if($barang->ed <= \Carbon\Carbon::today()->addDays(30)->format('Y-m-d'))(expired)@endif</span>
                                    @endforeach
                                </td>
                                <td>{{ $item->stok }}</td>
                                <td>{{ "Rp. ".number_format($harga_racik, 2 , ',' , '.' ) }}</td>
                                @if($expired === false )                
                                <td><button class='btn btn-info btn-sm mr-1 mx-3' onclick="add_barang({{ $item->id }}, {{ $item->stok }})"><a style='color: white;'><i class='fa fa-plus'></i></a></button></td>                           
                                @else
                                <td><p class="text-danger"; style="font-size: .9rem">Terdapat Barang Sudah Expired</p></td>
                                @endif
                            </tr>
                            @endforeach
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
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
            "ordering": false,
            "bPaginate": false,
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,3,4,5,6,7]
				},
				{
					width: "7%",
					targets: [0]
				},
                {
                    width: '10%',
                    targets: [4]
                }
			],
		});

        $('.datatable-obat-racik').dataTable({
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

    function tambah_obat_racik(){
        $("#modal_obat_racik").modal('show');
    }

    function add_barang(id, stok){
        if(stok < 1){
            swal("Stok Obat Racik Sudah Habis", {
                icon: 'error',
            });
            return;
        }
        $('#modal_loading').modal('show');
        $.ajax({
            url: "{{ route('transaksi.obat-racik') }}/add-keranjang",
            type: "POST",
            data: {"id_racik": id, "type": 3},
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                if (response.status === 200) {
                    location.reload();
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

    function delete_keranjang(id){

        swal({
             title: 'Yakin?',
             text: 'Apakah anda yakin akan menghapus data ini?',
             icon: 'warning',
             buttons: true,
             dangerMode: true,
       })
       .then((willDelete) => {
             if (willDelete) {
                 $.ajax({
                     url: "{{ route('transaksi.obat-racik') }}/delete-keranjang/" + id,
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

    function change_qty(qty, id){

        let harga = parseInt($('.display_harga' + id).attr('price'));
        let stok = parseInt($('.display_stok' + id).text())

        if(qty > stok){
            swal('Jumlah Barang Tidak Boleh Melebihi Stok', { icon: 'error', });
            $('#qty' + id).val(stok);
            qty = stok;
        } else if(qty < 1){
            swal('Jumlah Barang Tidak Boleh Kurang dari 0', { icon: 'error', });
            $('#qty' + id).val(1);
            $(`#subtotal${id}`).text(fungsiRupiah(harga));
            $(`#subtotal${id}`).attr('price', harga);
            qty = 1;
        }

        $('.jumlah_stock_' + id).each((i, element) => {
            $(element).text(parseInt($(element).attr('stock')) * qty)
        });
        
        harga *= qty;
        $(`#subtotal${id}`).text(fungsiRupiah(harga));
        $(`#subtotal${id}`).attr('price', harga);

        
        total = 0;
        $( ".subtotal" ).each(function( index ) {
            total += parseInt($( this ).attr('price'));
            $('#total').text(fungsiRupiah(total));
            $('#total').attr('price', total);
            $('#total_belanja').val(fungsiRupiah(total));
        });
        $('#total_belanja').val($('#total').text());
        hitung($('#uang').val());
    }

    function hitung(value){
        total = $('#total').attr('price');
        potongan = parseInt($('#potongan').val());
        if(isNaN(potongan)){
            potongan = 0;
        }
        if (parseInt(potongan) > total){
            swal('Jumlah Potongan Melebihi Harga Total', { icon: 'error', });
            $('#potongan').val(0);
            return;
        }
        value_potongan = parseInt(value) + potongan;
        if(total < value_potongan){
            $('#kembali').text(fungsiRupiah(value_potongan - total));
            $('#kembali').attr('price', value - total);
        } else if (total == value_potongan){
            $('#kembali').text("Rp. 0,00");
        } else {
            $('#kembali').text('Uang Belum Cukup');
        }
    }

    function potongan_change(value){
        total = parseInt($('#total').attr('price'));
        uang = parseInt($('#uang').val());
        if (parseInt(value) > total){
            swal('Jumlah Potongan Melebihi Harga Total', { icon: 'error', });
            $('#potongan').val(0);
            return;
        }
        uang_and_potongan = uang + parseInt(value);
        // console.log(uang_and_potongan);
        if(isNaN(uang_and_potongan)){
            hitung(uang);
            return;
        }
        hitung(uang);
    }


    $('#form_penjualan').submit(function(e){
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
                    url: "{{ route('transaksi.obat-racik') }}/store",
                    type: "POST",
                    data: $('#form_penjualan').serialize() + '&total_belanja=' + $('#total').attr('price') + '&kode_transaksi=' + $('#kode_transaksi').val(),
                    success: function (response) {
                        setTimeout(function () {
                            $('#modal_loading').modal('hide');
                        }, 500);
                        console.log(response);
                        if (response.status === 200) {
                             swal(response.message, { icon: 'success', }).then(function() {
                            window.location.href = "{{ route('jurnal.penjualan') }}/detail-penjualan/" + response.id
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
    });
</script>
    
</script>
@endsection