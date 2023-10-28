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
        <h3 style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 20px">Laporan Laba Rugi 
        @if ($month)
            {{ App::monthToIndonesian($month) . ' ' . date('Y'); }}
        @endif
        <br>
        {{ $perusahaan->nm_perusahaan }}
        </h3>
    </header>
    <main style="margin-top: 30px">
        <table width="100%">
            <thead style="background: #e7e7e7">
                <tr>
                    <th colspan="3" style="padding: 0!important; border: 1px solid #F2F2F2"><h3>Laporan Laba Rugi</h3></th>
                </tr>
            </thead>
            <tbody>
                <tr class="body">
                    <td>Penjualan Kotor</td>
                    <td align="right"><?php  echo "Rp.".number_format($laba_kotor,2,'.',','); ?></td>
                    <td></td>
                </tr>
                @foreach ($akun_laba as $value )
                <tr class="body">
                    <td>{{ $value->nama_akun }}</td>
                    <td align="right"><?php  echo "Rp.".number_format($value->total,2,'.',','); ?></td>
                    <td></td>
                </tr>
                @endforeach
                <tr class="body">
                    <td colspan="2" align="center">Laba Kotor</td>
                    <td align="right"><?php  echo "Rp.".number_format($laba,2,'.',','); ?></td>
                </tr>
                @foreach ($akun_beban as $beban)
                <tr class="body">
                    <td>{{ $beban->nama_akun }}</td>
                    <td align="right"><?php  echo "Rp.".number_format($beban->total,2,'.',','); ?></td>
                    <td></td>
                </tr>    
                @endforeach
                <tr class="body" style="background: #e7e7e7">
                    <td colspan="2" align="center">Total Biaya</td>
                    <td align="right"><?php  echo "Rp.".number_format($total_beban,2,'.',','); ?></td>
                </tr>
                <tr class="body" style="background: #e7e7e7">
                    <td colspan="2" align="center">Laba/Rugi Bersih</td>
                    <td align="right"><?php  echo "Rp.".number_format($laba_rugi,2,'.',','); ?></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>