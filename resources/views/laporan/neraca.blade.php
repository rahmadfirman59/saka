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
        <h1 class="h3 mb-2">Laporan Neraca</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                <li class="breadcrumb-item active"><a href="#">Laporan Neraca</a></li>
                
            </ol>
        </nav>
    </div>
<h5 style="color: red" >*Pastikan nominal pada aktiva dan pasiva sama</h5>
    <div class="row">
		<div class="col-lg-12">
			<section class="section">
				<div class="row">
					<div class="col-lg-12">

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$perusahaan->nm_perusahaan<br>$perusahaan->alamat" ?><br>Laporan Persediaan per <?php echo $tanggal;?></h5>
                                <br>
                                
							</div>
							<div class="card-body mt-4">
                                <div class="row" style="gap: 11px 0; margin: 15px 0">
                                    <form id="form_rugi_laba" method="post" name="postform" style="display: flex; gap: 10px; align-items: center;">
                                        @csrf
                                        <?php 
                                            echo 	combotgl(1,31,'tgl_1',1);
                                                    combonamabln(1,12,'bln_1',date('m'));
                                                    combothn(2000,date("Y"),'thn_1',date("Y"));
                                        ?>
                                        
                                        S/D
                                        <?php 
                                                echo 	combotgl(1,31,'tgl_2',date('d'));
                                                        combonamabln(1,12,'bln_2',date('m'));
                                                        combothn(2000,date("Y"),'thn_2',date("Y"));
                                        ?>
                                            <button type="submit" class="btn btn-success">Tampilkan</button>
                                    </form>

                                    <a href="/saka/laporan/penjualan/pdf" target="_blank" class="mx-3">
                                        <button type="submit" class="btn btn-warning">Print PDF</button>
                                    </a>
                                </div>
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
                                                        <td colspan="3">Kas Bank</td>
                                                        <td><?php  echo "Rp.".number_format($kas_bank,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Persedian Barang</td>
                                                        <td><?php  echo "Rp.".number_format($persediaan->jumlah,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($aktiva ,2,'.',','); ?></td>
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
                                                        
                                                        <td ><?php  echo "Rp.".number_format($pasiva->jumlah,2,'.',','); ?></td>
                                                    </tr>
                                                      
                                                    </tr>
                                                      <tr>
                                                        <td colspan="3">Laba/Rugi  </td>
                                                        
                                                        <td ><?php  echo "Rp.".number_format($laba_rugi,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($pasiva->jumlah + $laba_rugi,2,'.',','); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
                        <!--  -->
                        
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
@endsection