@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    .content-heading {
        text-align: center;
        padding-bottom: 5px;
        padding-top: 7px;
        border-radius: 20px;
        border-bottom: 5px solid #d7d7d7;
        border-right: 1px solid #d7d7d7;
        border-left: 1px solid #d7d7d7;
        color: #012970;
        font-size: 20px;
        font-weight: 700;
        margin: auto;
        margin-bottom: 17px;
        width: 80%;
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
                    <div style="position: absolute;right: 12px;top: 13px;font-size: 13px!important">
                        <button type="button" class="btn btn-success mr-2"
                                onclick="$('#modal_transaksi_manual').modal('show');"><i class="fas fa-plus mr-1"></i>
                                Tambah Transaksi Manual</button>
                    </div>
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
                                        <a href="{{ route('jurnal.pembelian') }}/detail-pembelian/{{ $item->id }}" target="_blank">{{ $item->kode }}</a>
                                        @elseif($item->type == 2)
                                        <a href="{{ route('jurnal.penjualan') }}/detail-penjualan/{{ $item->id }}" target="_blank">{{ $item->kode }}</a>
                                        @elseif($item->type == 6)
                                        <a onclick="show_modal_manual('{{ $item->kode }}', '{{ $item->keterangan }}')" style="color: #4e73df; cursor: pointer">
                                            {{ $item->kode }}
                                        </a>
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


@section('modal')
<div class="modal fade" role="dialog" id="modal_transaksi_manual" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title">Tambah Transaksi Manual</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <form id="form_upload" action="{{ route('jurnal.umum') }}/store" method="POST" autocomplete="off">
             @csrf
             <div class="modal-body">
                <div class="row">
                    <input type="text" hidden class="form-control" name="id" id="id">
                    <div class="col-12 col-md-6 col-lg-6">
                        <h5 class="content-heading">Debit</h5>
                        <div class="form-group">
                            <label>Pilih Akun</label>
                            <select id="pilih_biaya" name="pilih_akun_debit" class="form-control select2">
                                <option value="" >-- Pilih Akun --</option>
                                @foreach ($akun as $item)
                                <option value="{{ $item->kode_akun }}">{{ $item->kode_akun . ' - ' .  $item->nama_akun }}</option>
                                @endforeach
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-pilih_akun_debit-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <h5 class="content-heading">Kredit</h5>
                        <div class="form-group">
                            <label>Pilih Akun</label>
                            <select id="pilih_biaya" name="pilih_akun_kredit" class="form-control select2">
                                <option value="" >-- Pilih Akun --</option>
                                @foreach ($akun as $item)
                                <option value="{{ $item->kode_akun }}">{{ $item->kode_akun . ' - ' .  $item->nama_akun }}</option>
                                @endforeach
                            </select>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-pilih_akun_kredit-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nominal</label>
                            <input class="form-control" type="text" id="nominal_debit" name="nominal_debit" >
                            <span class="d-flex text-danger invalid-feedback" id="invalid-nominal_debit-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nominal</label>
                            <input class="form-control" type="text" id="nominal_kredit" name="nominal_kredit" >
                            <span class="d-flex text-danger invalid-feedback" id="invalid-nominal_kredit-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Uraian</label>
                            <textarea class="form-control" name="uraian_debit" style="min-height: 120px"></textarea>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-uraian_debit-feedback"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Uraian</label>
                            <textarea class="form-control" name="uraian_kredit" style="min-height: 120px"></textarea>
                            <span class="d-flex text-danger invalid-feedback" id="invalid-uraian_kredit-feedback"></span>
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

<div class="modal fade" role="dialog" id="modal_uraian" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
       <div class="modal-content">
          <div class="modal-header br">
             <h5 class="modal-title">Detail Uraian</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <h5 class="content-heading" id="uraian_kode"></h5>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>Uraian</label>
                        <textarea class="form-control" id="uraian" disabled style="min-height: 120px"></textarea>
                    </div>
                </div>
            </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

    function show_modal_manual(kode, keterangan){
        $('#modal_uraian').modal('show');
        $('#uraian_kode').text('Kode : ' + kode);
        $('#uraian').val(keterangan);
    }

    $('#form_jurnal_umum').submit(function(e){
        e.preventDefault();
        $("#modal_loading").modal('show');
        $.ajax({
            url: "{{ route('jurnal.umum') }}/change_priode",
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