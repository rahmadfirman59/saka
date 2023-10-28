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
                        <div class="row" style="gap: 11px 0; margin: 15px 0">
                            <input type="hidden" name="tanggal_1" id="tanggal_1">
                            <input type="hidden" name="tanggal_2" id="tanggal_2">
                            <form id="neraca" method="post" name="postform" style="display: flex; gap: 10px; align-items: center;">
							@csrf
                            <?php 
							if(isset($_POST['report'])){ 
								echo 	combotgl(1,31,'tgl_1',$_POST[tgl_1]);
										combonamabln(1,12,'bln_1',$_POST[bln_1]);
										combothn(2000,date("Y"),'thn_1',$_POST[thn_1]); 
							}else{	
								echo 	combotgl(1,31,'tgl_1',1);
										combonamabln(1,12,'bln_1',date('m'));
										combothn(2000,date("Y"),'thn_1',date("Y"));
							}?>
							
							S/D
							<?php 
                            if(isset($_POST['report'])){ 
                                echo 	combotgl(1,31,'tgl_2',$_POST[tgl_2]);
                                        combonamabln(1,12,'bln_2',$_POST[bln_2]);
                                        combothn(2000,date("Y"),'thn_2',$_POST[thn_2]);  
                                
                            }else{ 
                                echo 	combotgl(1,31,'tgl_2',date('d'));
                                        combonamabln(1,12,'bln_2',date('m'));
                                        combothn(2000,date("Y"),'thn_2',date("Y"));
                            }?>
							
							<input type="submit" class="btn btn-success ml-3" />
                            <button type="button" class="btn btn-secondary ml-2" onclick="window.location.reload()">Reset</button>
                            <button type="button" class="btn btn-warning ml-2" onclick="print_pdf()">Print PDF</button>
						</form>
                        </div>

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$perusahaan->nm_perusahaan<br>$perusahaan->alamat" ?><br>Laporan Neraca <span id="priode">per <?php echo $tanggal;?></span></h5>
                                <br>
                                
							</div>
							<div class="card-body mt-4">
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
                                                <tbody id="tbody_akun_aktiva">
                                                    @foreach ($aktiva as $item )
                                                        <tr>
                                                            <td colspan="3">{{ $item->nama_akun }}</td>
                                                            <td><?php  echo "Rp.".number_format($item->total,2,'.',','); ?></td>
                                                        </tr>    
                                                    @endforeach
                                                </tbody>
                                                 <tbody id="tbody_total_aktiva">	
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($total_aktiva,2,'.',','); ?></td>
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
                                                <tbody id="tbody_akun_pasiva">
                                                    @foreach ($pasiva as $v )
                                                        <tr>
                                                        <td colspan="3">{{ $v->nama_akun }}</td>
                                                        <td><?php  echo "Rp.".number_format($v->total,2,'.',','); ?></td>
                                                    </tr>
                                                    @endforeach
                                                    
                                                   
                                                </tbody>
                                                <tbody id="tbody_laba_rugi">
                                                    <tr>
                                                        <td colspan="3">Laba Rugi tahun berjalan</td>
                                                        <td><?php  echo "Rp.".number_format($laba_rugi,2,'.',','); ?></td>
                                                    </tr>
                                                </tbody>
                                                <tbody >
                                                    <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                                <tbody id="tbody_total_pasiva">
                                                    
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($total_pasiva,2,'.',','); ?></td>
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
        let url = `{{ route('laporan.neraca') }}/pdf?tanggal_awal=${$('#tanggal_1').val()}&tanggal_akhir=${$('#tanggal_2').val()}`;
        window.open(url, '_blank');
    }

    $('#neraca').submit(function(e){
        e.preventDefault();
        $("#modal_loading").modal('show');
        $.ajax({
            url: "{{ route('neraca.filter') }}",
            type: "POST",
            data: $('#neraca').serialize(),
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                $('#tanggal_1').val(response['tanggal']['tanggal_awal']);
                $('#tanggal_2').val(response['tanggal']['tanggal_akhir']);
                $('#priode').text(formatDate(response['tanggal']['tanggal_awal']) + ' - ' + formatDate(response['tanggal']['tanggal_akhir']));
                $('#tbody_akun_aktiva').empty();
                response['aktiva'].forEach((element, i) => {
                    $('#tbody_akun_aktiva').append(`
                    <tr class='body'>
                        <td colspan='3'><div align='left'>${element.nama_akun}</div></td>
                        <td>`+ fungsiRupiah(parseInt(element.total ?? 0)) +`</td>
					</tr>	
                    `)
                });
                $('#tbody_total_aktiva').empty();
                $('#tbody_total_aktiva').append(`
                    <tr class='body'>
                        <td colspan='3'><div align='left'>Total</div></td>
                        <td align='right'>`+ fungsiRupiah(parseInt(response['total_aktiva'] ?? 0)) +`</td>
					</tr>	
                    `)

                $('#tbody_akun_pasiva').empty();
                response['pasiva'].forEach((element, i) => {
                    $('#tbody_akun_pasiva').append(`
                    <tr class='body'>
                        <td colspan='3'><div align='left'>${element.nama_akun}</div></td>
                        <td>`+ fungsiRupiah(parseInt(element.total ?? 0)) +`</td>
					</tr>	
                    `)
                });
               
                $('#tbody_laba_rugi').empty();
                $('#tbody_laba_rugi').append(`
                    <tr class='body'>
                        <td colspan='3'><div align='left'>Laba rugi tahun berjalan</div></td>
                        <td>`+ fungsiRupiah(parseInt(response['laba_rugi'] ?? 0)) +`</td>
					</tr>	
                    `)
                $('#tbody_total_pasiva').empty();
                $('#tbody_total_pasiva').append(`
                    <tr class='body'>
                        <td colspan='3'><div align='left'>Total</div></td>
                        <td align='right'>`+ fungsiRupiah(parseInt(response['total_pasiva'] ?? 0)) +`</td>
					</tr>	
                `)
                    
                    
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