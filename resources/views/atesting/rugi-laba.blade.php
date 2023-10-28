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
        <h3 style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 20px">Laporan Pembelian <?php echo $tanggal . '<br>' . $perusahaan->nm_perusahaan ?></h3>
    </header>
    <main style="margin-top: 30px">
        <table width="100%" border="2">
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
    </main>
</body>
</html>