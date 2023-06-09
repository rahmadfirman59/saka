@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }
	.card-header{
		background: #fff;
	}
</style>
@endsection
@section('content')
<?php
	function combotgl($awal, $akhir, $var, $terpilih){
	echo "<select name=$var class='form-control form-select' style='display: inline-block; width: unset'>";
	for ($i=$awal; $i<=$akhir; $i++){
		$lebar=strlen($i);
		switch($lebar){
		case 1:
		{
			$g="0".$i;
			break;     
		}
		case 2:
		{
			$g=$i;
			break;     
		}      
		}  
		if ($i==$terpilih)
		echo "<option value=$g selected>$g</option>";
		else
		echo "<option value=$g>$g</option>";
	}
	echo "</select> ";
	}

	function combobln($awal, $akhir, $var, $terpilih){
	echo "<select  name=$var class='form-control form-select' style='display: inline-block; width: unset'>";
	for ($bln=$awal; $bln<=$akhir; $bln++){
		$lebar=strlen($bln);
		switch($lebar){
		case 1:
		{
			$b="0".$bln;
			break;     
		}
		case 2:
		{
			$b=$bln;
			break;     
		}      
		}  
		if ($bln==$terpilih)
			echo "<option value=$b selected>$b</option>";
		else
			echo "<option value=$b>$b</option>";
	}
	echo "</select> ";
	}

	function combothn($awal, $akhir, $var, $terpilih){
	echo "<select  name=$var class='form-control form-select' style='display: inline-block; width: unset'>";
	for ($i=$awal; $i<=$akhir; $i++){
		if ($i==$terpilih)
		echo "<option value=$i selected>$i</option>";
		else
		echo "<option value=$i>$i</option>";
	}
	echo "</select> ";
	}

	function combonamabln($awal, $akhir, $var, $terpilih){
	$nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
						"Juni", "Juli", "Agustus", "September", 
						"Oktober", "November", "Desember");
	echo "<select  name=$var class='form-control form-select' style='display: inline-block; width: unset'>";
	for ($bln=$awal; $bln<=$akhir; $bln++){
		if ($bln==$terpilih)
			echo "<option value=$bln selected>$nama_bln[$bln]</option>";
		else
			echo "<option value=$bln>$nama_bln[$bln]</option>";
	}
	echo "</select> ";
	}

?>
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
					<h5 class="card-title p-0" style="display: inline-block">{{ $perusahaan->nm_perusahaan }}<br>{{ $perusahaan->alamat }}<br>Laporan Rugi/Laba Priode {{ $tanggal }}</h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="3">Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<tr class="body">
									<td colspan='10'><div align="left">Penjualan</div></td>
									<td align="right"><?php  echo "Rp.".number_format($penjualan,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								{{-- <tr class="body">
									<td colspan='7'><div align="left">Persedian Awal</div></td>
									<td align="right"><?php  echo "Rp.".number_format($persediaan->jumlah,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
								<tr class="body">
									<td colspan='6'><div align="left">Pembelian</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pembelian,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='5'><div align="left">Potongan Pembelian</div></td>
									<td align="right"><?php  echo "Rp.".number_format($potongan,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='5'><div align="left">Retur Pembelian</div></td>
									<td align="right"><?php  echo "Rp.".number_format($retur->jumlah,2,'.',','); ?>+<hr></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='6'><div align="left"></div></td>
									<td align="right"><?php  echo "Rp.".number_format($retur->jumlah + $potongan,2,'.',','); ?>+<hr></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  ?></td><td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='7'><div align="left">Pembelian Bersih</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pembelian - $retur->jumlah + $potongan,2,'.',','); ?><hr></td>
									<td align="right"><?php echo"+"; ?></td>
								</tr></tr><tr class="body">
									<td colspan='7'><div align="left">Barang Siap Jual</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pembelian - $retur->jumlah + $potongan + $persediaan->jumlah,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  ?></td>
								</tr></tr><tr class="body">
									<td colspan='7'><div align="left">Persediaan Akhir</div></td>
									<td align="right"><?php  echo "Rp.".number_format($total_harga_beli_produk[0]->total,2,'.',','); ?><hr></td>
									<td align="right"><?php echo"-"; ?></td>
									<td align="right"><?php  ?></td>
								</tr> --}}
								<tr class="body">
									<td colspan='9'><div align="left">Harga Pokok Penjualan</div></td>
									<td align="right"><?php  echo "Rp.".number_format($hpp,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="right">Laba</div></td>
									<td align="right"><?php  ?></td>
									{{-- <td align="right"><?php  echo "Rp.".number_format($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total,2,'.',','); ?></td> --}}
									<td align="right"><?php  echo "Rp.".number_format($laba,2,'.',','); ?></td>
								</tr>
								{{-- <tr class="body">
									<td colspan='9'><div align="left">Biaya</div></td>
									<td align="right"><?php  echo "Rp.".number_format($biaya,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="left">Pajak</div></td>
									<td align="right"><?php  echo "Rp.".number_format(($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) * 10 / 100,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr> --}}
								{{-- <tr class="body">
									<td colspan='9'><div align="right">Jumlah Beban</div></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  echo "Rp.".number_format($biaya + ($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total) * 10 / 100,2,'.',','); ?></td>
									<td align="right"><?php  echo "Rp.".number_format($penjualan - $pembelian - $retur->jumlah + $potongan + $persediaan->jumlah - $total_harga_beli_produk[0]->total,2,'.',','); ?></td>
								</tr> --}}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 mb-5">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0">Priode</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row">
						<form action="llb.pdf" method="post" name="postform" class="d-flex" style="gap: 15px"  target='_blank'>
							<?php 
							echo 	
								combonamabln(1,12,'bln_1',date('m'));
								combothn(2000,date('Y'),'thn_1',date('Y'));
							?>
							<input type="submit" class='btn btn-success' name="report" value="Tampilkan" /> <a onclick="window.open('llb.print','Print','menubar=no,navigator=no,width=825,height=600,left=200,top=150,toolbar=no')";><i class='icon-print'></i></a>
						</form> 
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection