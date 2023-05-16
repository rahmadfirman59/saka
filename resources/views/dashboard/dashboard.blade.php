@extends('layouts.layouts')
@section('content')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="pagetitle">
            <h1 class="h3 mb-0">Dashboard</h1>
        </div>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 penjualan-card">
                <div class="card-body">
                    <h5 class="card-title2">Transaksi</h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="pl-3">
                            <h6 style="font-size: 1.4rem; font-weight: 700;"><a href="/saka/transaksi/penjualan" style="color: #012970" class="penjualan-href">Penjualan</a></h6>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 pembelian-card">
                <div class="card-body">
                    <h5 class="card-title2">Pembelian</h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="pl-3">
                            <h6 style="font-size: 1.4rem; font-weight: 700;"><a href="/saka/transaksi/pembelian" style="color: #012970" class="pembelian-href">Penjualan</a></h6>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 laporan-card">
                <div class="card-body">
                    <h5 class="card-title2">Laporan</h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="pl-3">
                            <h6 style="font-size: 1.4rem; font-weight: 700;"><a href="/saka/laporan/rugi-laba" style="color: #012970" class="llb-href">Rugi/Laba</a></h6>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 grafik-card">
                <div class="card-body">
                    <h5 class="card-title2">Grafik</h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <div class="pl-3">
                            <h6 style="font-size: 1.4rem; font-weight: 700;"><a href="/saka/grafik" style="color: #012970" class="grafik-href">Grafik</a></h6>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Operasional Table -->
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title2">Biaya Operasional</h5>

                    <table class="table table-borderless tb-false datatable-primary">
                        <thead>
                            <tr>
                                <th scope="col" align="center"><div align="center">Kode Akun</div></th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Masa</th>
                                <th scope="col">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($akuns as $akun)
                            <tr>
                                <td ><div align="center">{{ $akun->kode_akun }}</div></td>
                                <td>{{ $akun->nama_akun }}</td>
                                <td>1 Bln</td>
                                <td>{{ 'Rp. ' .  number_format($akun->jumlah,2,'.',',');}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"><div align="center"><strong>TOTAL Penyesuaian</strong></div></td>
                                <td style="display: none"></td>
                                <td style="display: none"></td>
                                <td><strong>{{ 'Rp. ' .  number_format($total,2,'.',',');}}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="card recent-sales overflow-auto mt-5">

                <div class="card-body">
                    <h5 class="card-title2">Peringatan Barang Telah Expired</h5>

                    <table class="table table-borderless datatable-jquery-barang datatable-primary" style="border-left: 1px solid #00000033; border-right: 1px solid #00000033; border-bottom: 1px solid #00000033">
                        <thead>
                            <tr>
                                <th scope="col" align="center"><div align="center">Nama Barang</div></th>
                                <th scope="col">Expired Date</th>
                                <th scope="col">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang_ed as $barang)
                            <tr>
                                <td ><div>{{ $barang->nama_barang }}</div></td>
                                <td>{{ $barang->ed }}</td>
                                <td>{{ $barang->stok }}</td>
                                {{-- <td>{{ 'Rp. ' .  number_format($barang->jumlah,2,'.',',');}}</td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div><!-- End Operasional Table -->

        <!-- Hutang Table -->
        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title2">Hutang Dagang</h5>

                    <table class="table table-borderless datatable-jquery datatable-primary">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">No. Faktur</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Total</th>
                                <th scope="col">Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_hutang = 0 ?>
                            @foreach ($hutangDagang as $data)
                            <tr>
                                <td>{{ $data->tanggal }}</td>
                                <td>{{ $data->pembelian[0]->no_faktur }}</td>
                                <td>1 Bln</td>
                                <td>{{ 'Rp. ' .  number_format($data->debt,2,'.',',');}}</td>
                                <td>
                                    <button class='btn btn-info btn-sm mr-1'><a style='color: white' href='/saka/jurnal/jurnal-pembelian/detail-pembelian/{{ $data->id }}'>
                                        <i class='bi bi-credit-card-2-back-fill'></i>
                                    </a></button>
                                </td>
                            </tr>
                            <?php $total_hutang += $data->debt ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><div align="center"><strong>TOTAL Hutang</strong></div></td>
                                <td align="right"><strong><?php echo number_format($total_hutang,2,'.',','); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div><!-- End Hutang Table -->
    </div>

</div> 

@endsection

@section('script')
<script>
    $(document).ready(function () {
		$tb = $('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,3,4]				}
			],
		});

        $tb2 = $('.datatable-jquery-barang').dataTable({
			sDom: 'lBfrtip',
            pageLength : 5,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
			columnDefs: [{
					className: 'text-center',
					targets: [1,2]				}
			],
		});
	});
</script>
@endsection