@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }
	.card-header{
		background: #fff;
	}

    #month-header{
        display: none;
    }
</style>
@endsection
@section('content')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Laporan Rugi/Laba</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                <li class="breadcrumb-item active"><a href="#">Laporan Rugi/Laba</a></li>
                
            </ol>
        </nav>
    </div>

    <div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header text-center">
					<h5 class="card-title p-0" style="display: inline-block">{{ $perusahaan->nm_perusahaan }}<br>{{ $perusahaan->alamat }}<br>Laporan Rugi/Laba <span id="month-header"></span></h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
                        <div class="row justify-content-between" style="gap: 11px 0; margin: 15px 0">
                            <form id="form_rugi_laba" method="post" name="postform" style="display: flex; gap: 10px; align-items: center;">
                                @csrf
                                Priode Bulan
                                <?php
                                    $list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                                ?>
                                <select  name="priode_bulan" id="priode_bulan" class='form-control form-select mx-2' style='display: inline-block; width: unset'>
                                    <option value="null" disabled selected>-- Pilih Bulan --</option>
                                    @foreach ($list_bulan as $key => $item)
                                        {{-- <option value="{{ $key + 1 }}" {{ $key + 1 == date('m') ? 'selected' : '' }}>{{ $item }}</option> --}}
                                        <option value="{{ $key + 1 }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-success">Tampilkan</button>
                                <button type="button" onclick="window.location.reload()" class="btn btn-secondary">Reset</button>
                                <button type="button" onclick="print_pdf()" class="btn btn-warning">Print PDF</button>
                                
                            </form>
                        </div>
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="3">Laba Rugi</th>
								</tr>
							</thead>
                            <tbody id="tbody_laba_kotor">	
								<tr class="body">
									<td colspan='9'><div align="left">Penjualan Kotor</div></td>
									<td align="right"><?php  echo "Rp.".number_format($laba_kotor,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
							</tbody>
							<tbody id="tbody_akun_laba">	
								@foreach ($akun_laba as $value )
									<tr class="body">
										<td colspan='9'><div align="left">{{ $value->nama_akun }}</div></td>
										<td align="right"><?php  echo "Rp.".number_format($value->total,2,'.',','); ?></td>
										<td align="right"><?php  ?></td>
									</tr>	
								@endforeach
								<br>
							</tbody>
                            <tbody id="tbody_laba">	
								<tr class="body">
									<td colspan='9'><div align="right">Laba Kotor</div></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  echo "Rp.".number_format($laba,2,'.',','); ?></td>
								</tr>
							</tbody>
                            <tbody id="tbody_akun_beban">	
								@foreach ($akun_beban as $beban )
									<tr class="body">
										<td colspan='9'><div align="left">{{ $beban->nama_akun }}</div></td>
										<td align="right"><?php  echo "Rp.".number_format($beban->total,2,'.',','); ?></td>
										<td align="right"><?php  ?></td>
									</tr>
								@endforeach
							</tbody>
                            <tbody id="tbody_total_biaya">	
								<tr class="body">
									<td colspan='9'><div align="right">Total Biaya </div></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  echo "Rp.".number_format($total_beban,2,'.',','); ?></td>
								</tr>
								
							</tbody>
                            <tbody id="tbody_laba_rugi">	
								
								<tr class="body">
									<td colspan='9'><div align="right">Laba/Rugi Bersih </div></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  echo "Rp.".number_format($laba_rugi,2,'.',','); ?></td>
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
            "bLengthChange": false,
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,3,4]
				}
			],
		});
	});

    function print_pdf(){
        let url = `{{ route('laporan.rugiLaba') }}/pdf/${$('#priode_bulan').val() ?? ''}`;
        window.open(url, '_blank');
    }

    $('#form_rugi_laba').submit(function(e){
        e.preventDefault();
        $("#modal_loading").modal('show');
        $.ajax({
            url: "{{ route('laporan.rugiLaba') }}/change-priode",
            type: "POST",
            data: $('#form_rugi_laba').serialize(),
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                if(response.status == 200){
                    $('#month-header').css(`display`, 'inline-block');
                    $('#month-header').text(`Priode Bulan ${response.data.month}`);
                    $('#tbody_akun_laba').empty();
                    response.data['akun_laba'].forEach((element, i) => {
                        $('#tbody_akun_laba').append(`
                        <tr class='body'>
                            <td colspan='9'><div align='left'>${element.nama_akun}</div></td>
                            <td style='text-align: right'>`+ fungsiRupiah(parseInt(element.total ?? 0)) +`</td>
                            <td class='text-center'></td>
                        </tr>	
                        `)
                    });
                    $('#tbody_laba_kotor').empty();
                    $('#tbody_laba_kotor').append(`
                        <tr class='body'>
                            <td colspan='9'><div align='left'>Penjualan Kotor</div></td>
                            <td style='text-align: right'>`+ fungsiRupiah(parseInt(response.data['laba_kotor'] ?? 0)) +`</td>
                            <td class='text-center'></td>
                        </tr>	
                        `)
                    $('#tbody_laba').empty();
                    $('#tbody_laba').append(`
                        <tr class='body'>
                            <td colspan='9'><div align='right'>Laba Kotor</div></td>
                            <td class='text-center'></td>
                            <td style='text-align: right'>`+ fungsiRupiah(parseInt(response.data['laba'] ?? 0)) +`</td>
                        </tr>	
                        `)
    
                    $('#tbody_akun_beban').empty();
                    response.data['akun_beban'].forEach((element, i) => {
                        $('#tbody_akun_beban').append(`
                        <tr class='body'>
                            <td colspan='9'><div align='left'>${element.nama_akun}</div></td>
                            <td style='text-align: right'>`+ fungsiRupiah(parseInt(element.total ?? 0)) +`</td>
                            <td class='text-center'></td>
                        </tr>	
                        `)
                    });
    
                    $('#tbody_total_biaya').empty();
                    $('#tbody_total_biaya').append(`
                        <tr class='body'>
                            <td colspan='9'><div align='right'>Laba Kotor</div></td>
                            <td class='text-center'></td>
                            <td style='text-align: right'>`+ fungsiRupiah(parseInt(response.data['total_beban'] ?? 0)) +`</td>
                        </tr>	
                        `)
    
                    $('#tbody_laba_rugi').empty();
                    $('#tbody_laba_rugi').append(`
                        <tr class='body'>
                            <td colspan='9'><div align='right'>Laba/Rugi Bersih</div></td>
                            <td class='text-center'></td>
                            <td style='text-align: right'>`+ fungsiRupiah(parseInt(response.data['laba_rugi'] ?? 0)) +`</td>
                        </tr>	
                        `)
                } else {
                    swal(response.message, {
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
    });
</script>
    
@endsection