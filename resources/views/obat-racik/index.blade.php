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
        <h1 class="h3 mb-2">Master Obat Racik</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active"><a href="#">Obat Racik</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 card-title">Tabel Obat Racik</h6>
            <a href="{{ route('obat-racik-add') }}">
                <button type="button" style="position: absolute;right: 12px;top: 13px;font-size: 13px" class="btn btn-warning"><i class="fa fa-plus mr-1"></i>Tambah Obat Racik</button>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Racik</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($obat_racik as $key=> $item )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_racik }}</td>
                            <td>
                                @php
                                    $harga_racik = 0;
                                @endphp
                                @foreach ($item->barangs as $barang)
                                    @php
                                        $harga_racik += $barang->harga_jual_tablet * $barang->pivot->jumlah;
                                    @endphp
                                    <span class="badge badge-primary" style="font-family: 'Nunito', sans-serif; padding: .35em .7em;">{{ $barang->nama_barang }} ({{ $barang->pivot->jumlah }})</span>
                                @endforeach
                            </td>
                            <td>{{ "Rp. ".number_format($harga_racik, 2 , ',' , '.' ) }}</td>
                            <td>
                                <a style='color: white;' href="obat-racik/detail/{{ $item->id }}">
                                    <button class='btn btn-info btn-sm mr-1'><i class='fa fa-edit'></i></button>
                                </a>
                                <a style='color: white'; Onclick="delete_action('obat-racik/delete/{{ $item->id }}', '{{ $item->nama_racik }}')">
                                    <button class='btn btn-danger btn-sm'><i class='bi bi-trash-fill'></i></button>
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
			columnDefs: [{
					className: 'text-center',
					targets: [0, 3, 4]
				},
				{
					width: "7%",
					targets: [0]
				},
				{
					orderable: false,
					targets: [0, 4]
				}
			],
		});
	});
    
</script>
    
</script>
@endsection