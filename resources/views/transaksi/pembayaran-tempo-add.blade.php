@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    .table tr th , .table tr td{
        font-family: "Nunito", sans-serif;
        /* font-size: 16px; */
        font-weight: bold;
        color: #31353B;
    }

    .table tr td{
        color: #31353bcf!important;
    }

    .section-badge::before {
		content: '';
		border-radius: 5px;
		height: 8px;
		width: 30px;
		background-color: #6777ef;
		display: inline-block;
		float: left;
	}

    .summary-desc{
        font-size: 1rem;
        color: black;
        line-height: 22px;
        font-weight: 600;
        letter-spacing: 0;
        margin: 0;
    }

    .child-padding > * {
        padding-left: 20px;
        padding-right: 20px;
    }

    .child-padding{
        margin-top: 10px
    }

    .section-badge{
        font-family: "Nunito", sans-serif;
        font-size: 15px;
        padding-bottom: 10px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 11px;
        color: #31353B;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Pelunasan Tempo</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transaksi.pembayaran-tempo') }}">Pembayaran Tempo</a></li>
                <li class="breadcrumb-item active"><a href="#">Pelunasan Tempo</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="card" style="width: 90%">
        <div class="card-body" style="padding: 0 0.64rem">
            <div class="row" style="margin: 2.2rem 0 0 0">
                <div class="col-7">
                    <div style="margin-right: 3.5rem">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="custom-heading-text" style="padding-bottom: 5px">Detail Pembelian</div>
                            <div class="custom-heading-text text-danger" style="font-size: 13px">Jatuh Tempo : {{ App\Helpers\App::tgl_indo($pembelian[0]->tgl_tempo) }}</div>
                        </div>
                        <div class="box-main-content d-flex justify-content-between align-items-center">
                            <p style="margin: 0;font-size: .8rem;color: #5e666d;font-weight: 600;">No. Faktur : {{ $pembelian[0]->no_faktur }}<br>Tanggal Faktur : {{ $pembelian[0]->tgl_faktur }}</p>
                            <button class="border-button btn">Kode Transaksi : {{ $transaksi->kode }}</button>
                        </div>
                        <div style="border-bottom: 6px solid #F3F4F5;">
                            <div class="row py-2">
                                <div class="col-6" style="color: #5e666d">
                                    <strong>Supplier:</strong><br>
                                    <p style="font-size: .9rem; margin-top: 8px; margin-bottom: 0; font-family: &quot;Nunito&quot;">{{ $pembelian[0]->supplier->nama_supplier }}<br>{{ $pembelian[0]->supplier->alamat }}, {{ $pembelian[0]->supplier->kota }}</p>
                                </div>
                                <div class="col-6" style="color: #5e666d">
                                    <strong>Billed To:</strong><br>
                                    <p style="font-size: .9rem; margin-top: 8px; margin-bottom: 0; font-family: &quot;Nunito&quot;">APOTEK SAKA SASMITRA<br>JALAN DIPONEGORO, SEMARANG</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card-custom">
                        <div class="heading-text">Summary</div>
                        <div class="d-flex justify-content-between child-padding">
                            <p class="summary-desc">Total</p>
                            <p class="summary-desc"><?php echo"Rp&nbsp".number_format($transaksi->kredit,2,'.',','); ?></p>
                        </div>
                        @if($transaksi->potongan)
                        <div class="d-flex justify-content-between child-padding">
                            <p class="summary-desc text-danger">Potongan</p>
                            <p class="summary-desc text-danger"><?php echo"-&nbspRp&nbsp".number_format($transaksi->potongan,2,'.',','); ?></p>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between child-padding" style="border-top : 1px solid #DBDEE2; padding-top: 15px; margin-top: 15px">
                            <p class="summary-desc" style="font-size: 18px; font-weight: 700">Grand Total</p>
                            <p class="summary-desc" style="font-size: 18px; font-weight: 700"><?php echo"Rp&nbsp".number_format($transaksi->kredit - $transaksi->potongan,2,'.',','); ?></p>
                        </div>
                    </div>         
                </div>
                <div class="col-12">
                    <div class="mt-3 mb-4">
                        <div class="section-badge">List Barang</div>
                        <table class="table table-md">
                            <thead>
                                <tr style="background: #e4e4e4d9;">
                                    <th class="text-center">No.</th>
                                    <th class="text-center">No. Batch</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Harga Grosir</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($pembelian as $key => $item)
                            <tr class="body">
                                <td align="center">{{ $key + 1 }}</td>
                                <td align="center">{{ $item->barang->no_batch }}</td>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td align="center"><?php echo"Rp&nbsp".number_format($item->barang->harga_beli_grosir,2,'.',','); ?></td>
                                <td align="center">{{ $item->jumlah . ' ' . $item->barang->satuan_grosir }}</td>
                                <td align="center"><?php echo"Rp&nbsp".number_format($item->total,2,'.',','); ?></td>
                            </tr>  
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="my-5 mx-2 float-right" style="width: 15%; height: 13%">
                        <button class="btn btn-success" onclick="show_modal()" style="width: 100%;font-size: 17px;height: 100%;font-weight: 700;font-family: 'Nunito';"><i class="bi bi-credit-card-fill mr-2"></i> Bayar</button>
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
             <h5 class="modal-title">Pilih Pembayaran</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" value="{{ $transaksi->kode }}" name="kode" id="kode">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group custom-radio d-flex justify-content-center">
                            <input type="radio" id="kas_radio" name="type_pembayaran" value="0" checked><label for="kas_radio" style="border-right: none; text-align: center">Kas</label>
                            <input type="radio" id="transfer_radio" name="type_pembayaran" value="1"><label for="transfer_radio" style="border-left: none; text-align: center">Transfer</label>
                        </div>
                    </div>
                </div>
             </div>
             <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Bayar</button>
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

    function show_modal(){
        $("#modal").modal('show');
        $("#form_upload")[0].reset();
        $('input, select').removeClass('is-invalid');
    }

    $('#form_upload').submit(function(e){
        e.preventDefault();
        swal({
             title: 'Apakah Anda Yakin ingin melanjutkan Pembayaran...?',
             text: 'pastikan semua data sudah diisi dengan benar',
             icon: 'warning',
             buttons: true,
             dangerMode: true,
       })
       .then((willDelete) => {
            $.ajax({
                url: "{{ route('transaksi.pembayaran-tempo') }}/pelunasan/store-update",
                type: "POST",
                data: $('#form_upload').serialize(),
                success: function (response) {
                    setTimeout(function () {
                        $('#modal_loading').modal('hide');
                    }, 500);
                    if(response.status === 200){
                        swal(response.message, { icon: 'success', }).then(function() {
                            window.location.href = `{{ route('jurnal.pembelian') }}/detail-pembelian/${response.id}`;
                        });
                    } else {
                        swal("Oops! Terjadi kesalahan", {
                            icon: 'error',
                        });
                    }
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
       })
    })
</script>
    
</script>
@endsection