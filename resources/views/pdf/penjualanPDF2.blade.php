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
</head>
<body>
    <header style="border-bottom: 5px double black; width: 100%">
        <h3 style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 20px">Laporan Penjualan <?php echo $tanggal . '<br>' . $perusahaan->nm_perusahaan ?></h3>
    </header>
    <main style="margin-top: 30px">
        <table border="2" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nomor Bukti</th>
                    <th>Dokter</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>

            <tbody id="tbody-jquery">
                @foreach ($penjualan as $key=> $item )
                <tr>
                    <td align="center">{{ $key + 1 }}</td>
                    <td align="center">{{ $item->transaksi->tanggal }}</td>
                    <td align="center">{{ $item->transaksi->kode }}</td>
                    <td align="center">{{ $item->dokter->nama_dokter }}</td>
                    <td align="right"><?php echo "Rp. ".number_format($item->subtotal, 2 , ',' , '.' ) ?></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="footer">
                    <td style="background: rgb(241 248 255) !important" colspan="4"><div align="center"><strong>TOTAL PENJUALAN</strong></div></td>
                    <td style="background: rgb(241 248 255) !important" align="right"><strong><?php echo "Rp." . number_format($Totalpenjualan,2,'.',','); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </main>
</body>
</html>