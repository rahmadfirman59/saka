@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    @media(min-width: 600px){
        #DataTables_Table_0_filter{
            position: absolute;
            top: 17px;
            right: 25px;
        }
    }

    table tr th{
        font-size: .9rem;
    }

    table tr td{
        font-size: .9rem;
    }

    .table thead th, .table tbody td{
        vertical-align: middle;
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
        <h1 class="h3 mb-2">Transaksi Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="#">Pembelian</a></li>
                
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
                <form id="form_pembelian">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 13%">No. Batch Dirubah</th>
                                    <th style="width: 9%">Jumlah</th>
                                    <th style="width: 5%">Item Per Box</th>
                                    <th>Harga</th>
                                    <th>Kadaluarsa</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @foreach ($keranjang as $key=> $item)
                                <tr>
                                    <input type="hidden" value="{{ $item->barang->id }}" name="idbarang[]">
                                    <td>{{ $key+1 }}</td>
                                    <td style="white-space: nowrap">{{ $item->barang->nama_barang }}</td>
                                    <td><input type="text" class="form-control" style="font-size: .9rem" name="no_batch[]"></td>
                                    <td>
                                        <input name="qty[]" type="number" id="qty{{ $key }}" value="1" class="form-control text-center" onchange="change_qty(this.value, {{ $key }}, {{ $item->barang->stok }}, {{ $item->barang->harga_jual }})">
                                    </td>
                                    <td>
                                        <input name="qty_grosir[]" type="number" id="qty_grosir{{ $key }}" onchange="change_item_box(this.value, {{ $key }})" value="1" class="form-control text-center">
                                    </td>
                                    <td><input type="text" class="form-control" style="font-size: .9rem" id="harga{{ $key }}" onchange="change_harga(this.value, {{ $key }})" name="harga[]"></td>
                                    <td><input type="date" style="font-size: .8rem; height: 38px;" class="form-control" name="ed[]"></td>
                                    <td style="white-space: nowrap" id="subtotal{{ $key }}" class="subtotal" price="0"><?php echo "Rp. ".number_format(0, 2 , ',' , '.' ) ?></td>
                                    <td style="width: 10%">
                                        <button class='btn btn-danger btn-sm' type="button"><a style='color: white' onclick="delete_keranjang({{ $item->id }})"><i class='bi bi-trash-fill'></i></a></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr> 
                                    <td style="text-align:right; vertical-align: middle" colspan="7"><b style="float: right;">Potongan</b></td> 
                                    <td id="tot_bar" colspan="2"><input type="text" name='potongan' onchange="potongan_change(this.value)" id='potongan' style="font-size: .9rem" class="form-control"></td>
                                </tr> 
                                <tr>
									<td align='right' colspan='7' style="border-bottom: 1px solid lightgray; padding: 0.25em; vertical-align: middle;"><b style=" float: right;">Total<b></td>
                                    <td id="total" colspan='2' style="font-size: 1rem; border-bottom: 1px solid lightgray; font-weight: bold; text-align: center" price="0"><b><?php echo "Rp. ".number_format(0, 2 , ',' , '.' ) ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="card-title p-0" style="display: inline-block">Transaksi Pembelian</h5>
                </div>
                <div class="card-body">
                    <div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Supplier</label>
                                <select name="nm_supplier" class="form-control select2">
									<option value="" selected>- Pilih Supplier -</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>No. Faktur</label>
                                <input type="text" id="no_faktur" name="no_faktur" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Faktur</label>
                                <input type="date" id="tgl_faktur" name="tgl_faktur" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label>No. Nota</label>
                                <input type="text" value="{{ 'PMB'.(int)$count_pembelian.date('Ymd') }}" id="kode_transaksi" name="kode_transaksi" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" value="{{ date('d-m-Y') }}" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center justify-content-center">
                            <div class="form-group d-flex justify-content-around" style="margin: 0; gap: 40px">
                                <div>
                                    <label class="checkbox-inline"><input name='status' id="tunai" checked type='radio' value='1'></label>
                                    <label>Kas</label>
                                </div>
                                <div>
                                    <label class="checkbox-inline"><input name='status' id="tempo" type='radio' value='2'></label>
                                    <label>Bank</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Jatuh Tempo</label>
                                <input type="date" name="tgl_tempo" disabled id="tgl_tempo" value="" class="form-control">
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
                                <th>Satuan Grosir</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Kadaluarsa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            @foreach ($barang as $key=> $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>         
                                <td>{{ $item->nama_barang }}</td>        
                                @if(isset($item->satuan_grosir))                   
                                <td>{{ $item->satuan_grosir . '(' . $item->jumlah_grosir .')' }}</td>                           
                                @else
                                <td></td>
                                @endif
                                <td>{{ $item->stok }}</td>                           
                                <td>{{ $item->satuan }}</td>                           
                                <td>{{ $item->ed }}</td>                           
                                <td><button onclick="add_barang({{ $item->id }}, {{ $item->stok }})" class='btn btn-info btn-sm mr-1 mx-3'><a style='color: white;'><i class='fa fa-plus'></i></a></button></td>                           
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
					targets: [0,1,2,3,4,5,6,7,8]
				},
				{
					width: "7%",
					targets: [0]
				},
                {
                    width: '8%',
                    targets: [4]
                },
                {
                    width: '13%',
                    targets: [7]
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

    $("#tunai").click(function () {
        document.getElementById("tgl_tempo").disabled = true;
    });

    $("#tempo").click(function () {
        document.getElementById("tgl_tempo").disabled = false;
    });

    function tambah_barang_penjualan(){
        $("#modal_barang").modal('show');
    }

    function add_barang(id, stok){
        $('#modal_loading').modal('show');
        $.ajax({
            url: '/saka/transaksi/pembelian/add-keranjang',
            type: "POST",
            data: {"id_barang": id, "type": 1},
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
             text: 'Apakah anda yakin akan menyimpan data ini?',
             icon: 'warning',
             buttons: true,
             dangerMode: true,
       })
       .then((willDelete) => {
             if (willDelete) {
                 $.ajax({
                     url: '/saka/transaksi/pembelian/delete-keranjang/' + id,
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

    function potongan_change(value){
        
        total = 0;
        $( ".subtotal" ).each(function( index ) {
            //   console.log( index + ": " + $( this ).text() );
            total += parseInt($( this ).attr('price'));
        });
        if (value > total){
            swal('Jumlah Potongan Melebihi Harga Total', { icon: 'error', });
            $('#potongan').val(0);
            return;
        }
        total_with_potongan = total - value;
        $('#total').text(fungsiRupiah(total_with_potongan));
    }

    function change_harga(value, id){
        if(value < 0){
            swal('Jumlah Barang Tidak Boleh Kurang dari 0', { icon: 'error', });
            $('#harga' + id).val('');
            return;
        }

        subtotal = parseInt(value) * $(`#qty${id}`).val();
        $(`#subtotal${id}`).text(fungsiRupiah(subtotal));
        $(`#subtotal${id}`).attr('price', subtotal);

        total = 0;
        $( ".subtotal" ).each(function( index ) {
            //   console.log( index + ": " + $( this ).text() );
            total += parseInt($( this ).attr('price'));
            $('#total').text(fungsiRupiah(total));
            $('#total').attr('price', total);
        });
    }

    function change_qty(qty, id, stok){
        if(qty < 1){
            swal('Jumlah Barang Tidak Boleh Kurang dari 1', { icon: 'error', });
            $('#qty' + id).val(1);
            qty=1;
        }

        harga = $(`#harga${id}`).val();
        
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
    }

    function change_item_box(qty, id){
        if(qty < 1){
            swal('Jumlah Item Per Box Tidak Boleh Kurang dari 1', { icon: 'error', });
            $('#qty_grosir' + id).val(1);
            return;
        }
    }


    $('#form_pembelian').submit(function(e){
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
                    url: '/saka/transaksi/pembelian/store',
                    type: "POST",
                    data: $('#form_pembelian').serialize() + '&total_belanja=' + $('#total').attr('price') + '&kode_transaksi=' + $('#kode_transaksi').val(),
                    success: function (response) {
                        setTimeout(function () {
                            $('#modal_loading').modal('hide');
                        }, 500);
                        if (response.status === 200) {
                             swal(response.message, { icon: 'success', }).then(function() {
                            window.location.href = '/saka/jurnal/jurnal-pembelian/detail-pembelian/' + response.id
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