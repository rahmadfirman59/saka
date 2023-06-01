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
        <h1 class="h3 mb-2">Transaksi Penjualan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="#">Penjualan</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <button type="button" style="left: 12px;top: 13px;font-size: 13px" class="btn btn-warning"
                                onclick="tambah_barang_penjualan();"><i class="fa fa-plus mr-1"></i>
                                Tambah Barang</button>
                </div>
                <form id="form_penjualan">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Penjualan</th>
                                    <th>Satuan</th>
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
                                <?php $total+=$item->barang->harga_jual ?>
                                <tr>
                                    <input type="hidden" value="{{ $item->barang->id }}" name="idbarang[]">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->barang->nama_barang }}</td>
                                    <td>
                                        <select name="tipe[]" onchange="ubah_tipe(this.value, {{ $key }}, {{ $item->id }})" id="tipe{{ $key }}" class="form-control">
                                            <option value="0" selected>Satuan</option>
                                            <option value="1">Grosir</option>
                                        </select>
                                    </td>
                                    <td class="display_satuan{{ $key }}">{{ $item->barang->satuan }}</td>
                                    <td class="display_stok{{ $key }}">{{ $item->barang->stok }}</td>
                                    <td style="width: 10%">
                                        <input name="qty[]" type="number" id="qty{{ $key }}" value="1" class="form-control text-center" onchange="change_qty(this.value, {{ $key }}, {{ $item->barang->stok }}, {{ $item->barang->harga_jual }})">
                                    </td>
                                    <td price="{{ $item->barang->harga_jual }}" class="display_harga{{ $key }}">{{ "Rp. ".number_format($item->barang->harga_jual, 2 , ',' , '.' ) }}</td>
                                    <td id="subtotal{{ $key }}" class="subtotal" price="{{ $item->barang->harga_jual }}">{{ "Rp. ".number_format($item->barang->harga_jual, 2 , ',' , '.' ) }}</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm' type="button"><a style='color: white' onclick="delete_keranjang({{ $item->id }})"><i class='bi bi-trash-fill'></i></a></button>
                                    </td>
                                </tr>
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
                                <input type="text" id="uang" name="uang" onkeyup="hitung(this.value)" onkeypress="return hanyaAngka(event,false);" class="form-control">
                            </div>
                        </div>
						<div class="d-flex w-100" style="font-size: 16px!important; padding-top: 15px;border-top: 1px solid #d8d1d1;margin-top: 10px;">
							<div class="p-2 fw-bold ml-2" style="color: black">
                                <label style="font-weight: bold">Kembali</label>
                                <span id="kembali" style='margin-left:10px; font-weight: bold'><span style='margin-left:15px'></span>-</span>
                            </div>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title p-0" style="display: inline-block">Transaksi Penjualan</h5>
                </div>
                <div class="card-body mt-3">
                    <div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Dokter</label>
                                <select name="nm_dokter" class="form-control">
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
                                <input type="text" value="{{ 'PJL'.(int)$count_penjualan.date('Ymd') }}" id="kode_transaksi" class="form-control" disabled>
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
                                <th>No. Batch</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Kadaluarsa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            @foreach ($barang as $key=> $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>         
                                <td>{{ $item->nama_barang }}</td>                           
                                <td>{{ $item->no_batch }}</td>                           
                                <td>{{ $item->harga_jual }}</td>                           
                                <td>{{ $item->stok }}</td>                              
                                <td>{{ $item->ed }}</td>           
                                @if($item->ed > \Carbon\Carbon::today()->addDays(30)->format('Y-m-d'))                
                                <td><button class='btn btn-info btn-sm mr-1 mx-3' onclick="add_barang({{ $item->id }}, {{ $item->stok }})"><a style='color: white;'><i class='fa fa-plus'></i></a></button></td>                           
                                @else
                                <td><p class="text-danger"; style="font-size: .9rem">Barang Sudah Expired</p></td>
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

        $('.datatable-barang').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,2,3,4,5,6]
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

    function tambah_barang_penjualan(){
        $("#modal_barang").modal('show');
    }

    function ubah_tipe(val, id, id_keranjang){
        $('#qty'+id).val(1);
        $('#modal_loading').modal('show');
        $.ajax({
            url: '/saka/transaksi/penjualan/get-keranjang/' + id_keranjang,
            type: "GET",
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                let query_barang = response[0]['barang'];
                let grosir
                if(val == 1){grosir = '_grosir';}else{grosir = ''}

                $('.display_satuan' + id).text(query_barang['satuan' + grosir]);
                $('.display_stok' + id).text(query_barang['stok' + grosir]);
                $('.display_harga' + id).text(fungsiRupiah(query_barang['harga_jual' + grosir]));
                $('.display_harga' + id).attr('price', query_barang['harga_jual' + grosir]);
                $('#subtotal' + id).text(fungsiRupiah(query_barang['harga_jual' + grosir]));
                $('#subtotal' + id).attr('price', query_barang['harga_jual' + grosir])
                total = 0;
                $( ".subtotal" ).each(function( index ) {
                    //   console.log( index + ": " + $( this ).text() );
                    total += parseInt($( this ).attr('price'));
                    $('#total').text(fungsiRupiah(total));
                    $('#total').attr('price', total);
                    $('#total_belanja').val(fungsiRupiah(total));
                });
                $('#total_belanja').val($('#total').text());
                hitung($('#uang').val(), $('#total').attr('price'));
                
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

    function add_barang(id, stok){
        if(stok < 1){
            swal("Stok Produk Sudah Habis", {
                icon: 'error',
            });
            return;
        }
        $('#modal_loading').modal('show');
        $.ajax({
            url: '/saka/transaksi/penjualan/add-keranjang',
            type: "POST",
            data: {"id_barang": id, "type": 2},
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
                     url: '/saka/transaksi/penjualan/delete-keranjang/' + id,
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
        } else if(qty < 1){
            swal('Jumlah Barang Tidak Boleh Kurang dari 0', { icon: 'error', });
            $('#qty' + id).val(1);
            $(`#subtotal${id}`).text(fungsiRupiah(harga));
            return
        }
        
        harga *= qty;
        $(`#subtotal${id}`).text(fungsiRupiah(harga));
        $(`#subtotal${id}`).attr('price', harga);

        
        total = 0;
        $( ".subtotal" ).each(function( index ) {
            //   console.log( index + ": " + $( this ).text() );
            total += parseInt($( this ).attr('price'));
            $('#total').text(fungsiRupiah(total));
            $('#total').attr('price', total);
            $('#total_belanja').val(fungsiRupiah(total));
        });
        $('#total_belanja').val($('#total').text());
        hitung($('#uang').val(), $('#total').attr('price'));
    }

    function hitung(value){
        total = $('#total').attr('price');
        if(total < parseInt(value)){
            $('#kembali').text(fungsiRupiah(value - total));
        } else if (total == parseInt(value)){
            $('#kembali').text("Rp. 0,00");
        } else {
            $('#kembali').text('Uang Belum Cukup');
        }
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
                    url: '/saka/transaksi/penjualan/store',
                    type: "POST",
                    data: $('#form_penjualan').serialize() + '&total_belanja=' + $('#total').attr('price') + '&kode_transaksi=' + $('#kode_transaksi').val(),
                    success: function (response) {
                        setTimeout(function () {
                            $('#modal_loading').modal('hide');
                        }, 500);
                        console.log(response);
                        if (response.status === 200) {
                             swal(response.message, { icon: 'success', }).then(function() {
                            window.location.href = '/saka/jurnal/jurnal-penjualan/detail-penjualan/' + response.id
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