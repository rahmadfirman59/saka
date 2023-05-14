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
        <h1 class="h3 mb-2">Detail Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Jurnal</a></li>
                <li class="breadcrumb-item"><a href="/saka/jurnal/jurnal-pembelian">Jurnal Pembelian</a></li>
                <li class="breadcrumb-item active"><a href="#">Detail Pembelian</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
		<div class="col-lg-12">
			<div class="card p-3">
				<div class="card-header pb-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex justify-content-between pb-2">
								<div>
									<h3 class="fw-bold" style="color: #6c757d">Detail Pembelian</h3>
									<p style="margin: 0;font-size: .8rem;color: #5e666d;font-weight: 600;">No. Faktur : {{ $pembelian[0]->no_faktur }}<br>Tanggal Faktur : {{ $pembelian[0]->tgl_faktur }}</p>
								</div>
								<div style="font-size: 1rem; text-align: end; font-weight: 700; font-family: 'Nunito', 'Segoe UI', arial"><?php echo"<span style='font-size: 20px; color: #6c757d'>Nota : $transaksi->kode</span><br>$transaksi->tanggal";?></div>
							</div>
							<div class="row mt-4">
								<div class="col-md-12">
									<address class="row">
										<div class="col-6" style="color: #5e666d">
											<strong>Supplier:</strong><br>
											<p style='font-size: .9rem; margin-top: 8px; font-family: "Nunito"'>{{ $pembelian[0]->supplier->nama_supplier }}<br>{{ $pembelian[0]->supplier->alamat }}</p>
										</div>
										<div class="col-6" style="color: #5e666d">
											<strong>Billed To:</strong><br>
											<p style='font-size: .9rem; margin-top: 8px; font-family: "Nunito"'>{{ $perusahaan->nm_perusahaan }}<br>{{ $perusahaan->alamat }}</p>
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
									@foreach ($pembelian as $key=> $item)
									<tr class="body">
										<td align="center">{{ $key + 1 }}</td>
										<td align="center">{{ $item->barang->no_batch }}</td>
										<td>{{ $item->barang->nama_barang }}</td>
										<td align="center"><?php echo"Rp&nbsp".number_format($item->barang->harga_beli,2,'.',','); ?></td>
										<td align="center">{{ $item->jumlah . ' ' . $item->barang->satuan }}</td>
										<td align="center"><?php echo"Rp&nbsp".number_format($item->total,2,'.',','); ?></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="row mt-4" style="width: 100%">
							<div class="col-lg-8">
								<div class="section-badge-success m-0">Metode Pembayaran</div>
                                @if($pembelian[0]->status == 1)
								<div class="section-title" style="margin-left: 45px; margin-top: 6px;font-family: 'nunito';color: green;font-weight: 700;font-size: 1.1rem;">Tunai</div>
                                @else
                                <div class="section-title text-info" style="margin-left: 45px; margin-top: 6px;font-family: 'nunito';font-weight: 700;font-size: 1.1rem;">Tempo</div>
                                @endif
							</div>
							<div class="col-lg-4 text-right">
								<div class="row" style="width: 100%">
									<div class="col-6">
										<div class="invoice-detail-item">
											<div class="invoice-detail-name" style="margin-top: .8rem; margin-bottom: .8rem">Total</div>
											<div class="invoice-detail-name" style="margin-top: .8rem; margin-bottom: .8rem; font-size: 1.1rem">Potongan</div>
											<hr class="mt-4 mb-4" style="width: 240%">
											<div class="invoice-detail-name h5">Grand Total</div>
										</div>
									</div>
									<div class="col-6">
										<div class="invoice-detail-item">
		                                    @if($pembelian[0]->status == 1)
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value"><?php echo"Rp&nbsp".number_format($transaksi->debt,2,'.',','); ?></div>
											@else
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value"><?php echo"Rp&nbsp".number_format($transaksi->kredit,2,'.',','); ?></div>
											@endif
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value"><?php
											// if($cret > 0){
											// 	$grand=$sql[total]-$ret[total];
											// 	echo"Rp&nbsp".number_format($ret[total],2,',','.');echo"</h4>";
											// 	}else{
											// 	echo"Rp&nbsp".number_format($ret[total],2,',','.');echo"</h4>";
											// 		$grand=$sql[total];
											// 	}
                                            ?>
                                            @if(isset($transaksi->potongan))
                                            <p style="font-size: 1.1rem; color: red"><?php echo" - Rp&nbsp".number_format($transaksi->potongan,2,'.',','); ?></p>
                                            @else
                                            <p>-</p>
                                            @endif
                                        </div>
											<hr class="mt-4 mb-4" style="opacity: 0;">
											@if($pembelian[0]->status == 1)
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value invoice-detail-value-lg"><?php echo"Rp&nbsp".number_format($transaksi->debt - $transaksi->potongan,2,'.',','); ?></div>
											@else
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value invoice-detail-value-lg"><?php echo"Rp&nbsp".number_format($transaksi->kredit - $transaksi->potongan,2,'.',','); ?></div>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between mt-4" style="width: 100%">
                            @if ($pembelian[0]->status == 2)
                            <input type=hidden id='kode' value='{{ $transaksi->kode }}'>
                            <a href='?module=transaksi&act=detailpelunasan&id=$sql[kd_pmb]' class="btn btn-primary" id='lunas' >Pembayaran&nbsp<i class='icon-tags'></i></a>
                            @else
                            <div>
                                <button class="btn btn-warning btn-icon icon-left">
                                    <a href="" style="color: white">
                                        <i class="bi bi-arrow-counterclockwise"></i> Retur
                                    </a>
                                </button>
                                <button class="btn btn-danger btn-icon icon-left">
                                    <a href="" style="color: white">
                                        <i class="bi bi-x-circle-fill"></i> Batal
                                    </a>
                                </button>
                            </div>
                            <button class="btn btn-success btn-icon icon-left">
                                <a href="" style="color: white">
                                    <i class="bi bi-check2-circle"></i> Lunas
                                </a>
                            </button>
                            @endif
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection