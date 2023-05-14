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
        <h3 style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 20px">Laporan Pembelian <?php echo $tanggal . '<br>' . $perusahaan->nm_perusahaan ?></h3>
    </header>
    <main style="margin-top: 30px">
        <table border="2" width="100%" cellspacing="0">
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
                    <td align="center">{{ $key + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->pembelian[0]->no_faktur }}</td>
                    <td>{{ $item->pembelian[0]->tgl_faktur }}</td>
                    <td>{{ $item->pembelian[0]->supplier->nama_supplier }}</td>
                    <td style="text-align: end"><?php echo "Rp. ".number_format($item->debt, 2 , ',' , '.' ) ?></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>