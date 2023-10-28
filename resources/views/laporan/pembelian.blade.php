@php
    use App\Helpers\App;
@endphp
@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    #DataTables_Table_0_wrapper{
        margin-top: -45px!important;
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
        <h1 class="h3 mb-2">Laporan Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Jurnal</a></li>
                <li class="breadcrumb-item active"><a href="#">Laporan Pembelian</a></li>
                
            </ol>   
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header text-center">
                    <h5 class="card-title p-0" style="display: inline-block"><?php echo"$perusahaan->nm_perusahaan<br>$perusahaan->alamat" ?><br>Laporan Pembelian <span id="priode"></span></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row" style="gap: 11px 0; margin: 15px 0 10px 0">
                            <input type="hidden" name="tanggal_1" id="tanggal_1">
                            <input type="hidden" name="tanggal_2" id="tanggal_2">
                            <form id="form_pembelian" method="post" name="postform" style="display: flex; gap: 10px; align-items: center;">
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
                                <button type="button" class="btn btn-secondary ml-2" onclick="location.reload()">Reset</button>
                                <button type="button" class="btn btn-warning ml-2" onclick="print_pdf()">Print PDF</button>
                        </div>
                        <table class="table datatable-primary table-striped table-hover datatable-jquery" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Nomor Bukti</th>
                                    <th>Nomor Faktur</th>
                                    <th>Tanggal Faktur</th>
                                    <th>Supplier</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
        
                            <tbody id="tbody-jquery">
                                @foreach ($transaksi as $key=> $item )
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->pembelian[0]->no_faktur }}</td>
                                    <td>{{ $item->pembelian[0]->tgl_faktur }}</td>
                                    <td>{{ $item->pembelian[0]->supplier->nama_supplier }}</td>
                                    <td style="text-align: end"><?php echo "Rp. ".number_format($item->debt, 2 , ',' , '.' ) ?></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="footer">
                                    <td style="background: rgb(241 248 255) !important" colspan="6"><div align="center"><strong>TOTAL PEMBELIAN</strong></div></td>
                                    <td style="background: rgb(241 248 255) !important" align="right"><strong id="total_pembelian"><?php echo "Rp.&nbsp" . number_format($Totalpembelian,2,'.',','); ?></strong></td>
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
		$tb = $('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
            "bLengthChange": false,
			columnDefs: [{
					className: 'text-center',
					targets: [0,1,2,3,4,5]
				}
			],
		});
	});

    function print_pdf(){
        let url = `{{ route('laporan.pembelian') }}/pdf?tanggal_awal=${$('#tanggal_1').val()}&tanggal_akhir=${$('#tanggal_2').val()}`;
        window.open(url, '_blank');
    }

    $('#form_pembelian').submit(function(e){
        e.preventDefault();
        $("#modal_loading").modal('show');
        $.ajax({
            url: "{{ route('laporan.pembelian') }}/change-priode",
            type: "POST",
            data: $('#form_pembelian').serialize(),
            success: function (response) {
                setTimeout(function () {
                    $('#modal_loading').modal('hide');
                }, 500);
                $('#tanggal_1').val(response.tanggal_1);
                $('#tanggal_2').val(response.tanggal_2);
                $('#priode').text('Priode ' + formatDate(response.tanggal_1) + ' - ' + formatDate(response.tanggal_2));
                $('total_pembelian').text(fungsiRupiah(response.Totalpembelian));
                $('#tbody-jquery').empty();
                response['transaksi'].forEach((element, i) => {
                    $('#tbody-jquery').append(`
                    <tr>
                        <td class='text-center'>${i + 1}</td>
                        <td class='text-center'>${element.tanggal ?? 0}</td>
                        <td class='text-center'>${element.kode ?? 0}</td>
                        <td class='text-center'>${element.pembelian[0].no_faktur}</td>
                        <td class='text-center'>${element.pembelian[0].tgl_faktur}</td>
                        <td class='text-center'>${element.pembelian[0].supplier.nama_supplier ?? 0}</td>
                        <td style='text-align: end'>`+ fungsiRupiah(parseInt(element.debt ?? 0)) +`</td>
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