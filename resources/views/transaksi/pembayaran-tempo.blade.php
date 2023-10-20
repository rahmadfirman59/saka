@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Pembayaran Tempo</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="#">Pembayaran Tempo</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 card-title">Data Tempo</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Transaksi</th>
                                    <th>No. Faktur</th>
                                    <th>Supplier</th>
                                    <th>Pembayaran</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                </tr>
                            </thead>
        
                            <tbody id="tbody-jquery">
                                @foreach ($transaksi as $key=> $item)
                                <tr> 
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->pembelian[0]->no_faktur }}</td>
                                    <td>{{ $item->pembelian[0]->supplier->nama_supplier }}</td>
                                    @if($item->pembelian[0]->status == 2)
                                        <td><span class="p-2 badge badge-danger">Tempo</span></td>
                                        <td>{{ $item->pembelian[0]->tgl_tempo }}</td>
                                        <td><?php echo "Rp. ".number_format($item->kredit, 2 , ',' , '.' ) ?></td>
                                    @endif
                                    <td>
                                        <a style='color: white' onclick="window.location.href = '{{ route('transaksi.pembayaran-tempo') }}/pelunasan/{{ $item->kode }}'">
                                        <button class='btn btn-warning btn-sm mr-1'>
                                            <i class="bi bi-credit-card-2-back-fill"></i>
                                        </button>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
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
		$tb = $('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,3,4,5,6,7]
				}
			],
		});
	});
</script>
    
</script>
@endsection