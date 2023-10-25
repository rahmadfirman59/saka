<html>

<head>
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
    <div class='span8  offset2'>
        <div id='nota'>
            <fieldset>
                <div class='kepala-nota'>
                    <p>{{ $perusahaan->nm_perusahaan }}<br>{{ $perusahaan->alamat }}</p>
                    <hr>
                    <hr style='color:#000;'>
                </div>
                <div class='kanan-nota'>
                    Nota :<?php echo"$transaksi->kode<span style='margin-left:10px;'></span>$transaksi->tanggal;";?>
                </div>
                <div id='scroll'>
                    <table class='nota' style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($type_penjualan->tipe == 2)
                            @foreach ($penjualan as $key=> $item)
                            <tr class="body">
                                <td align="center">{{ $key + 1 }}</td>
                                <td align="center">{{ $item->obat_racik->nama_racik }}</td>
                                <td align="center">{{ $item->harga }}</td>
                                <td align="center">{{ $item->jumlah }}</td>
                                <td align="center"><?php echo "Rp&nbsp". number_format($item->subtotal,2,'.',','); ?></td>
                            </tr>
                            @endforeach
                            @else
                            @php
                                $batchPrefixes = [];
                            @endphp

                            @foreach($penjualan as $penjualanItem)
                                @php
                                    $barang = $penjualanItem['barang'];
                                    $stok = $penjualanItem['jumlah'];
                                    $tipe = $penjualanItem['tipe'];
                                    if($tipe == 0){
                                        $batchPrefix = $barang['batch_prefix'];
                                    } elseif($tipe == 1) {
                                        $batchPrefix = $barang['batch_prefix'] . "-GSR";
                                    }
                                    
                                    // Initialize the new_stok_grosir and new_stok_satuan for this batch_prefix
                                    if (!isset($batchPrefixes[$batchPrefix])) {
                                        $batchPrefixes[$batchPrefix] = [
                                            'new_stok_grosir' => 0,
                                            'new_stok_satuan' => 0,
                                            'harga' => 0,
                                            'subtotal' => 0,
                                        ];
                                    }

                                    $batchPrefixes[$batchPrefix]['barang'] = $barang['nama_barang'];
                                    $batchPrefixes[$batchPrefix]['harga'] = $penjualanItem['harga'];
                                    $batchPrefixes[$batchPrefix]['subtotal'] += $penjualanItem['subtotal'];


                                    // Update the new_stok_grosir and new_stok_satuan based on the tipe
                                    if ($tipe == 1) {
                                        $batchPrefixes[$batchPrefix]['new_stok_grosir'] += $stok;
                                        $batchPrefixes[$batchPrefix]['satuan_grosir'] = $barang['satuan_grosir'];
                                    } elseif ($tipe == 0) {
                                        $batchPrefixes[$batchPrefix]['new_stok_satuan'] += $stok;
                                        $batchPrefixes[$batchPrefix]['satuan'] = $barang['satuan'];
                                    }
                                @endphp
                            @endforeach
                            <?php $key = 0;?>
                            @foreach ($batchPrefixes as $item)
                            <tr class="body">
                                <td align="center">{{ $key + 1 }}</td>
                                <td align="center">{{ $item['barang'] }}</td>
                                <td align="center">{{ $item['harga'] }}</td> 
                                <td align="center">
                                    @if(isset($item['satuan_grosir']))
                                    {{ $item['new_stok_grosir'] . ' ' . $item['satuan_grosir'] }}
                                    @endif
                                    @if(isset($item['satuan']))
                                    {{ $item['new_stok_satuan'] . ' ' . $item['satuan'] }}
                                    @endif
                                </td>
                                <td align="center"><?php echo "Rp&nbsp". number_format($item['subtotal'],2,'.',','); $key = $key+1?></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='5'><h4 align='right'></h4></td>
                                <td colspan='5'><h4></h4></td>
                            </tr>
                            </table></div><hr><h3 style='float:right; margin-right:20px;'><?php echo "TOTAL&nbsp&nbsp Rp&nbsp". number_format($transaksi->kredit,2,'.',','); ?></h3>
                            <p style='font-size:10pt;float:left; padding-top:35px;color: red'>* barang yang sudah dibeli tidak bisa dikembalikan</br>
                            <i class='icon-user'></i>Petugas : {{ $petugas->name }}<p></fieldset></div>
                        </tfoot>
            </fieldset>
        </div>
</body>

</html>
