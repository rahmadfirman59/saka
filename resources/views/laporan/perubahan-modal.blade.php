@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    .card-header{
        background: #fff;
    }

    thead tr th{
        color: black;
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Laporan Neraca</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                <li class="breadcrumb-item active"><a href="#">Laporan Neraca</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header text-center">
                    <h5 class="card-title p-0" style="display: inline-block"><?php echo"$perusahaan->nm_perusahaan<br>$perusahaan->alamat" ?><br>Laporan Persediaan Priode <?php echo $tanggal;?></h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="6">Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4">Modal Awal</td>
									<td><?php  echo "Rp.".number_format($modal,2,'.',','); ?></td>
								</tr>
									<tr>
									<td colspan="2">Laba</td>
									<td><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) - ($biaya + (($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) * 10 / 100)),2,'.',','); ?></td>
								</tr>
								<tr>
									<td colspan="2">Prive</td>
									<td><?php  echo "Rp.".number_format($prive,2,'.',','); ?></td>
									<td align="right"><?php echo"-"; ?></td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) - ($biaya + (($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total)) * 10 / 100) - $prive ,2,'.',',') . "+"; ?></td>
								</tr>
								<tr>
									<td colspan="4">Modal Akhir</td>
									<td><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) - ($biaya + (($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total)) * 10 / 100) - $prive,2,'.',','); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection