@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    td{
        font-family: 'Open Sans';
        font-size: .9rem;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Laporan Persediaan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                <li class="breadcrumb-item active"><a href="#">Laporan Persediaan</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="card-title p-0 w-100" style="display: inline-block; text-align: center"><?php echo"$perusahaan->nm_perusahaan<br>$perusahaan->alamat" ?><br>Laporan Persediaan Priode <?php echo $tanggal;?></h5>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-end">
                <a href="{{ route('laporan.persediaan') }}/pdf" class="btn btn-secondary" style="font-size: .9rem" target='_blank'><i class="bi bi-file-earmark-break-fill mr-2"></i>Print To PDF</a>
            </div>
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Batch</th>
                            <th>Jenis</th>
                            <th class="text-center">Nama Barang</th>
                            <th>Satuan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th class="text-center">SubTotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($barang as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->no_batch }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->pembelian_sum_jumlah }}</td>
                            <td>@if(isset($item->penjualan_sum_jumlah)){{ $item->penjualan_sum_jumlah .' '. $item->satuan}}@endif @if(isset($item->sisa_pecahan)&&$item->sisa_pecahan !== 0)({{ $item->sisa_pecahan }} Pecahan)@endif</td>
                            <td>{{ $item->stok }}</td>
                            <td><?php echo "Rp.&nbsp" . number_format($item->harga_beli,2,'.',','); ?></td>
                            <td align="right"><?php echo "Rp.&nbsp" . number_format($item->harga_beli * $item->stok,2,'.',','); ?></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,4,5,6,7,8]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [1]
				}
			],
		});
	});
</script>
    
</script>
@endsection