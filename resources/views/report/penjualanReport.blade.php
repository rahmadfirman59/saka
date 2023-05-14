@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

	.card-header{
		background: #fff;
	}

	.fw-bold{
		font-weight:  700;
	}

	.section-badge::before {
		content: '';
		border-radius: 5px;
		height: 8px;
		width: 30px;
		background-color: #6777ef;
		display: inline-block;
		float: left;
		margin-top: 6px;
		margin-right: 15px;
	}

	.table{
		color: black;
	}

	.section-badge{
		font-size: 18px;
		color: #191d21;
		font-weight: 600;
		position: relative;
		margin: 30px 0 25px 0;
		font-family: 'nunito', arial;
	}
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Jurnal Penjualan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Jurnal</a></li>
                <li class="breadcrumb-item"><a href="/saka/jurnal/jurnal-penjualan">Jurnal Penjualan</a></li>
                <li class="breadcrumb-item active"><a href="#">Detail Penjualan</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
		<div class="col-lg-12">

			<div class="card p-3">
				<div class="card-header pb-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex justify-content-between">
								<h3 class="fw-bold" style="color: #6c757d">Detail Penjualan</h3>
								<div style="font-size: 1rem; text-align: end; font-weight: 700; font-family: 'Nunito', 'Segoe UI', arial"><?php echo"<span style='font-size: 20px; color: #6c757d'>Nota : $transaksi->kode</span><br>$transaksi->tanggal";?></div>
							</div>
							<div class="row mt-4">
								<div class="col-md-12">
									<address class="row">
										<div class="col-6" style="color: #5e666d; font-size: 1.2rem">
											<strong>Form:</strong><br>
											<?php echo"<p style='font-size: 1rem; margin-top: 8px; font-family: \"Nunito\"'>$perusahaan->nm_perusahaan<br>$perusahaan->alamat</p>"; ?>
										</div>
										<div class="col-6" style="color: #5e666d">
											<strong>Petugas:</strong><br>
											<p style='font-size: 1rem; margin-top: 8px; font-family: "Nunito"'>{{ $petugas->name }}</p>
										</div>
									</address>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body mt-3">
					<div class="row">
						<div class="section-badge">Barang Teroder</div>
						<div class="table-responsive">
							<table class="table table-md">
								<thead>
									<tr style="background: #e4e4e4d9;">
										<th class="text-center">No.</th>
										<th class="text-center">No. Batch</th>
										<th>Nama Barang</th>
										<th class="text-center">Harga</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($penjualan as $key=> $item)
									<tr class="body">
										<td align="center">{{ $key + 1 }}</td>
										<td align="center">{{ $item->barang->no_batch }}</td>
										<td>{{ $item->barang->nama_barang }}</td>
										<td align="center">{{ $item->harga }}</td>
										<td align="center">{{ $item->jumlah . ' ' . $item->barang->satuan }}</td>
										<td align="center"><?php echo "Rp&nbsp". number_format($item->subtotal,2,'.',','); ?></td>
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr class="footer" style="font-size: 1rem;">
										<td colspan="5"><div align="center"><strong>TOTAL Penyesuaian</strong></div></td>
										<td align="center"><strong><?php echo "Rp&nbsp". number_format($transaksi->kredit,2,'.',','); ?></strong></td>
									</tr>
								</tfoot>
							</table>
						</div>
						<p style="color: red; font-family: 'nunito', arial; font-size: .9rem; margin-bottom: 50px; display: block; width: 100%">*barang yang sudah dibeli tidak bisa dikembalikan</p>
						<div class="d-flex justify-content-between" style="width: 100%">
							<button class="btn btn-danger btn-icon icon-left">
								<a href="batalpnj.<?php echo"";?>" style="color: white">
									<i class="bi bi-x-circle-fill"></i> Batal
								</a>
							</button>
							<button class="btn btn-warning btn-icon icon-left" <?= "onclick=\"window.open('/saka/jurnal/jurnal-penjualan/cetak-penjualan/$transaksi->id','Print','menubar=no,navigator=no,width=500,height=450,left=200,top=150,toolbar=no')\";" ?>><i class="bi bi-printer-fill"></i></i> Print</button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection