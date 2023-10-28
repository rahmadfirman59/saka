@php
    use App\Helpers\App;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        tr:nth-child(even) {
        background-color: #f2f2f2;
      }
      body{
        font-family: monospace;
      }
      th{
        font-size: .9rem;
      }
      td{
        font-size: .7rem;
      }
      th, td{
        padding: 5px;
      }

      table{
        width: 100%;
      }
    </style>
    <script src="{{ asset('public/sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public\sbadmin\js\jquery.shortcuts.min.js') }}"></script>
    <script>
        $.Shortcuts.add({
            type: 'down',
            mask: 'p',
            handler: function () {
                window.print();
            }
        }).start();

    </script>
</head>
<body>
    <header style="border-bottom: 5px double black; width: 100%">
        <h3 style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 20px">Laporan Neraca 
        @if ($tanggal_default)
            {{ 'per ' . $tanggal_default }}
        @else
            @if($tanggal)
                <br>
                {{ App::tgl_indo($tanggal['tanggal_awal']) . ' - ' . App::tgl_indo($tanggal['tanggal_akhir']) }}
            @endif
        @endif
        <br>
        {{ $perusahaan->nm_perusahaan }}
        </h3>
    </header>
    <main style="margin-top: 30px">
        <div style="width: 100%;" class="row">
            <table>
                <tr>
                    <td style="padding: 0; margin: 0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr style="background: #f2f2f2">
                                        <th colspan="4">Aktiva</th>
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
                                    <tr style="background: #f2f2f2">
                                        <td colspan="3">Total</td>
                                        <td align="right"><?php  echo "Rp.".number_format($total_aktiva,2,'.',','); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="padding: 0; margin: 0">
                        <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr style="background: #f2f2f2">
                                <th colspan="4">Passiva</th>
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
                            
                            <tr style="background: #f2f2f2">
                                <td colspan="3">Total</td>
                                <td align="right"><?php  echo "Rp.".number_format($total_pasiva,2,'.',','); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                    </td>
                </tr>
            </table>
        </div>
    </main>
</body>
</html>