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
                @php
                  $total_persediaan = [
                      'pembelian' => 0,
                      'penjualan' => 0,
                      'stok' => 0,
                      'subTotal' => 0,
                  ];
                @endphp
                @foreach ($barang as $key=> $item )
                <tr>
                    @php
                        $total_persediaan['pembelian'] += $item->pembelian_sum_jumlah;
                        $total_persediaan['penjualan'] += $item->penjualan_sum_jumlah;
                        $total_persediaan['stok'] += $item->stok;
                        $total_persediaan['subTotal'] += ($item->harga_beli * $item->stok);
                    @endphp
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
                <tr style="background-color: rgba(211, 211, 211, 0.663)">
                  <td colspan="3" align="center">Total Persediaan</td>
                  <td align="center">{{ $total_persediaan['pembelian'] }}</td>
                  <td align="center">{{ $total_persediaan['penjualan'] }}</td>
                  <td align="center">{{ $total_persediaan['stok'] }}</td>
                  <td align="center">*</td>
                  <td align="center"><?php echo "Rp." . number_format($total_persediaan['subTotal'],2,'.',','); ?></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>