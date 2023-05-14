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
        font-size: .8rem;
      }
      th, td{
        padding: 5px;
      }
    </style>
</head>
<body>
    <header style="border-bottom: 5px double black; width: 100%">
        <h3 style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 20px">Laporan Stok <?php echo $tanggal . '<br>' . $perusahaan->nm_perusahaan ?></h3>
    </header>
    <main style="margin-top: 30px">
        <table border="2" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Stok Barang</th>
                    <th>Harga Beli</th>
                    <th>SubTotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($barang as $key=> $item )
                <tr>
                    <td align="center">{{ $key + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td align="center">{{ $item->satuan }}</td>
                    <td align="center">{{ $item->pembelian_sum_jumlah }}</td>
                    <td align="center">{{ $item->penjualan_sum_jumlah }}</td>
                    <td align="center">{{ $item->stok }}</td>
                    <td><?php echo "Rp." . number_format($item->harga_beli,2,'.',','); ?></td>
                    <td align="right"><?php echo "Rp." . number_format($item->harga_beli * $item->stok,2,'.',','); ?></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </main>
</body>
</html>