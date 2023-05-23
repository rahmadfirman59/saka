@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
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
        <h1 class="h3 mb-2">Jurnal Umum</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Jurnal</a></li>
                <li class="breadcrumb-item active"><a href="#">Jurnal Umum</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="row" style="gap: 40px">
        <div class="col-lg-8">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title m-0" style="display: inline-block">Priode</h5>
				</div>
				<div class="card-body mx-3 mt-3">
					<div class="row" style="gap: 11px 0">
						<form id="form_jurnal_umum" method="post" name="postform" style="display: flex; gap: 10px; align-items: center;">
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
							
							<input type="submit" class="btn btn-success" />
						</form>
                    </div>
				</div>
			</div>
		</div>
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 card-title">Data Jurnal Umum</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Transaksi</th>
                                    <th>Kode Rekening</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Debet</th>
                                    <th class="text-center">Kredit</th>
                                </tr>
                            </thead>
        
                            <tbody id="tbody-jquery">
                                @foreach ($transaksi as $item )
                                <tr>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>
                                        @if($item->type == 1)
                                        <a href="/saka/jurnal/jurnal-pembelian/detail-pembelian/{{ $item->id }}" target="_blank">{{ $item->kode }}</a>
                                        @elseif($item->type == 2)
                                        <a href="/saka/jurnal/jurnal-penjualan/detail-penjualan/{{ $item->id }}" target="_blank">{{ $item->kode }}</a>
                                        @else
                                        {{ $item->kode }}
                                        @endif
                                    </td>
                                    
                                    <td>{{ $item->kode_akun }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td style="text-align: end"><?php echo "Rp. ".number_format($item->debt, 2 , ',' , '.' ) ?></td>
                                    <td style="text-align: end"><?php echo "Rp. ".number_format($item->kredit, 2 , ',' , '.' ) ?></td>
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
					targets: [0,1,2,3]
				}
			],
		});
	});

    $('#form_jurnal_umum').submit(function(e){
        e.preventDefault();
        $("#modal_loading").modal('show');
        $.ajax({
            url: '/saka/jurnal/jurnal-umum/change_priode',
            type: "POST",
            data: $('#form_jurnal_umum').serialize(),
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                $('#tbody-jquery').empty();
                response.forEach(element => {
                    $('#tbody-jquery').append(`
                    <tr>
                        <td class='text-center'>${element.tanggal}</td>
                        <td class='text-center'>${element.kode}</td>
                        <td class='text-center'>${element.kode_akun}</td>
                        <td class='text-center'>${element.keterangan}</td>
                        <td style='text-align: end'>`+ fungsiRupiah(parseInt(element.debt ?? 0)) +`</td>
                        <td style='text-align: end'>`+ fungsiRupiah(parseInt(element.kredit ?? 0)) +`</td>
                    </tr>
                    `)
                });
                // while(response){
                // }
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
    
</script>
@endsection