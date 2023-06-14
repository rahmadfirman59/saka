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
			<section class="section">
				<div class="row">
					<div class="col-lg-12">

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$perusahaan->nm_perusahaan<br>$perusahaan->alamat" ?><br>Laporan Persediaan Priode <?php echo $tanggal;?></h5>
							</div>
							<div class="card-body mt-4">
								<div class="row">
                                    <div class="col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Aktiva</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3">Kas</td>
                                                        <td><?php  echo "Rp.".number_format($kas,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Persedian Akhir Barang</td>
                                                        <td><?php  echo "Rp.".number_format($persediaan->jumlah,2,'.',','); ?></td>
                                                        
                                                    </tr>
                                                     <tr>
                                                        <td colspan="3">HPP</td>
                                                        <td><?php  echo "Rp.".number_format($hpp,2,'.',','); ?></td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($aktiva,2,'.',','); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Passiva</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3">Hutang</td>
                                                        <td><?php  echo "Rp.".number_format($hutang,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Modal</td>
                                                        {{-- <td><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total),2,'.',','); ?></td> --}}
                                                        <td ><?php  echo "Rp.".number_format($pasiva->jumlah,2,'.',','); ?></td>
                                                    </tr>
                                                      <tr>
                                                        <td colspan="3">Penjualan</td>
                                                        {{-- <td><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total),2,'.',','); ?></td> --}}
                                                        <td ><?php  echo "Rp.".number_format($penjualan,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        {{-- <td align="right"><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) + $hutang,2,'.',','); ?></td> --}}
                                                        {{-- <td align="right"><?php  echo "Rp.".number_format(1000000,2,'.',','); ?></td> --}}
                                                        
                                                        <td align="right"><?php  echo "Rp.".number_format($pasiva->jumlah + $penjualan,2,'.',','); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>

					</div>
				</div>
			</section>
		</div>
	</div>
</div>
@endsection