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
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Laporan Hutang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                <li class="breadcrumb-item active"><a href="#">Laporan Hutang</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
		<div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="card-title p-0" style="display: inline-block">Data Hutang</h5>
                </div>
                <form id="form_penjualan">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nomor Transaksi</th>
                                    <th>No. Faktur</th>
                                    <th>Kode Rekening</th>
                                    <th>Keterangan</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @foreach ($hutang as $key=> $item)
                                <tr>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->pembelian[0]->no_faktur }}</td>
                                    <td>{{ $item->kode_akun }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td><?php echo "Rp. ".number_format($item->pembelian[0]->total, 2 , ',' , '.' ) ?></td>
                                    <td>
                                        <button class='btn btn-info btn-sm mr-1'><a style='color: white' href='/saka/jurnal/jurnal-pembelian/detail-pembelian/{{ $item->id }}'>Detail</a></button>    
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
									<th align='right' colspan='6' style="background:rgb(63 84 251 / 86%); color: white; padding: 0.25em; vertical-align: middle"><b>Total<b></th>
                                    <th id="total" colspan="2" style="background:rgb(63 84 251 / 86%); color: white; font-weight: bold" price="<?php echo $totalHutang ?>"><b><?php echo "Rp. ".number_format($totalHutang, 2 , ',' , '.' ) ?></b></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,3,4,5,6]
				},
				{
					width: "7%",
					targets: [0]
				}
			],
		});
	});
</script>

@endsection