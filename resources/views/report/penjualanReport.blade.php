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

	.swal-text{
		text-align: center;
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
                <li class="breadcrumb-item"><a href="{{ route('jurnal.penjualan') }}">Jurnal Penjualan</a></li>
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
											<p style="margin-bottom: .6rem">
												<strong>Petugas :&nbsp;</strong>
												<span style='font-size: 1rem; margin-top: 8px; font-family: "Nunito"'>{{ $petugas->name }}</span>
											</p>
											<p>
												<strong>Pasien :&nbsp;</strong>
												<span style='font-size: 1rem; margin-top: 8px; font-family: "Nunito"'>{{ $pasien->pasien->nama_pasien }}</span>
											</p>
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
								@if($type_penjualan->tipe == 2)
								<thead>
									<tr style="background: #e4e4e4d9;">
										<th class="text-center">No.</th>
										<th class="text-center">Nama Obat Racik</th>
										<th class="text-center">List Barang</th>
										<th class="text-center">Harga</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($penjualan as $key=> $item)
									<tr class="body">
										<td align="center">{{ $key + 1 }}</td>
										<td align="center">{{ $item->obat_racik->nama_racik }}</td>
										<td align="center">
											@php
												$harga_racik = 0;
											@endphp
											@foreach ($item->obat_racik->barangs as $barang)
												@php
													$harga_racik += $barang->harga_jual_tablet * $barang->pivot->jumlah;
												@endphp
												<span class="badge badge-primary" style="font-family: 'Nunito', sans-serif; padding: .35em .7em;">{{ $barang->nama_barang }} ({{ $barang->pivot->jumlah }})</span>
											@endforeach
										</td>
										<td align="center">{{ $harga_racik }}</td>
										<td align="center">{{ $item->jumlah }}</td>
										<td align="center"><?php echo "Rp&nbsp". number_format($item->subtotal,2,'.',','); ?></td>
									</tr>
									@endforeach
								</tbody>
								@else
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
										@if($item->tipe == 0)
										<td align="center">{{ $item->jumlah . ' ' . $item->barang->satuan }}</td>
										@else
										<td align="center">{{ $item->jumlah . ' ' . $item->barang->satuan_grosir }}</td>
										@endif
										<td align="center"><?php echo "Rp&nbsp". number_format($item->subtotal,2,'.',','); ?></td>
									</tr>
									@endforeach
								</tbody>
								@endif
								<tfoot>
									@if(isset($transaksi->potongan))
									<tr class="footer" style="font-size: 1rem;">
										<td colspan="5"><div align="center"><strong>POTONGAN</strong></div></td>
										<td align="center"><strong style="color: red">- <?php echo "Rp&nbsp". number_format($transaksi->potongan,2,'.',','); ?></strong></td>
									</tr>
									@endif
									<tr class="footer" style="font-size: 1rem;">
										<td colspan="5"><div align="center"><strong>TOTAL Penyesuaian</strong></div></td>
										<td align="center"><strong><?php echo "Rp&nbsp". number_format($transaksi->kredit - $transaksi->potongan ?? 0,2,'.',','); ?></strong></td>
									</tr>
								</tfoot>
							</table>
						</div>
						<p style="color: red; font-family: 'nunito', arial; font-size: .9rem; margin-bottom: 50px; display: block; width: 100%">*barang yang sudah dibeli tidak bisa dikembalikan</p>
						<div class="d-flex justify-content-between" style="width: 100%">
							@if($type_penjualan->tipe != 2)
							{{-- <button class="btn btn-danger btn-icon icon-left" onclick="batal_penjualan({{ $transaksi->id }}, '{{ Session::get('useractive')->level }}')">
								<p style="color: white; margin: 0">
									<i class="bi bi-x-circle-fill"></i> Batal
								</p>
							</button> --}}
							@else
							<p></p>
							@endif
							<button class="btn btn-warning btn-icon icon-left" <?= "onclick=\"window.open('{{ route('jurnal.penjualan') }}/cetak-penjualan/$transaksi->id','Print','menubar=no,navigator=no,width=500,height=450,left=200,top=150,toolbar=no')\";" ?>><i class="bi bi-printer-fill"></i></i> Print</button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection

@section('modal')
<div class="modal fade" role="dialog" id="modal_admin" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header br">
			<div>
				<h5 class="modal-title">Login Admin</h5>
				<p style="margin-bottom: -5px; font-size: .9rem">Enter your email & password to login</p>
			</div>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_admin" action="{{ route('jurnal.penjualan') }}/login-admin" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="transaksi_id_admin" value="{{ $transaksi->id }}">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Email</label>
                              <input class="form-control" type="text" id="email" name="email" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-email-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Password</label>
                              <input class="form-control" type="password" id="password" name="password" >
                              <span class="d-flex text-danger invalid-feedback" id="invalid-password-feedback"></span>
                        </div>
                    </div>
                </div>
             </div>
             <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Simpan</button>
             </div>
          </form>
       </div>
    </div>
</div>
@endsection

@section('script')
<script>
	function batal_penjualan(id, level){
		if(level !== 'superadmin'){
			swal({
				title: 'Peringatan',
				text: 'Anda Harus Login Sebagai Admin Untuk Menghapus Transaksi',
				icon: 'info',
			})
			.then((willDelete) => {
				$('#modal_admin').modal('show');
			});
		}else{
			batal_penjualan_function(id);
		}

	}

	function batal_penjualan_function(id){
		swal({
             title: 'Yakin?',
             text: 'Apakah anda yakin akan menghapus transaksi ini?',
             icon: 'warning',
             showCancelButton: true,
			 buttons: true,
             dangerMode: true,
			 confirmButtonText: 'Yes, delete it!'
       })
       .then((willDelete) => {
		   if(willDelete){
			   $("#modal_loading").modal('show');
			   $.ajax({
				   url: "{{ route('jurnal.penjualan') }}/batal-penjualan",
				   data: {id: id},
				   type: "POST",
				   success: function (response) {
					   setTimeout(function () {
						   $('#modal_loading').modal('hide');
					   }, 500);
					   if (response.status === 200) {
						   swal(response.message, { icon: 'success', }).then(function() {
							   window.location.href = "{{ route('transaksi.penjualan') }}";
						   });
					   } else{
						   swal(response.message, { icon: 'error', })
					   }
				   },
				   error: function (jqXHR, textStatus, errorThrown) {
					   setTimeout(function () {
						   $('#modal_loading').modal('hide');
					   }, 500);
					   swal("Oops! Terjadi kesalahan (" + errorThrown + ")", {
						   icon: 'error',
					   });
				   }
			   })
		   } else {
				$("#modal_loading").modal('show');
				$.ajax({
					url: "{{ route('jurnal.penjualan') }}/clear-session",
					type: "POST",
					success: function (response) {
						setTimeout(function () {
							$('#modal_loading').modal('hide');
						}, 500);
					},
					error: function (jqXHR, textStatus, errorThrown) {
						setTimeout(function () {
							$('#modal_loading').modal('hide');
						}, 500);
						swal("Oops! Terjadi kesalahan (" + errorThrown + ")", {
							icon: 'error',
						});
					}
				})
		   }
	   });
	}

	$('#modal_admin').submit(function(e){
		e.preventDefault();
		$("#modal_loading").modal('show');
		$.ajax({
			url: $('#form_admin').attr('action'),
			data: $('#form_admin').serialize(),
			type: "POST",
			success: function (response) {
				setTimeout(function () {
					$('#modal_loading').modal('hide');
				}, 500);
				if (response.status === 200) {
					swal(response.message, { icon: 'success', }).then(function() {
						$("#modal_admin").modal('hide');
				        $("#form_admin")[0].reset();
						batal_penjualan_function($('#transaksi_id_admin').val());
					});
				} else if(response.status == 300){
					swal(response.message, { icon: 'error', })
				} else {
					Object.keys(response.message).forEach(function (key) {
						var elem_name = $('[name=' + key + ']');
						var elem_feedback = $('[id=invalid-' + key + '-feedback' + ']');
						elem_name.addClass('is-invalid');
						elem_feedback.text(response.message[key]);
					});
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				setTimeout(function () {
					$('#modal_loading').modal('hide');
				}, 500);
				swal("Oops! Terjadi kesalahan (" + errorThrown + ")", {
					icon: 'error',
				});
			}
		})
	})
</script>
@endsection