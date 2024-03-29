<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Barang;
use App\Models\MasterTransaksi;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    private $perusahaan, $tanggal;

    public function __construct()
    {
        $this->perusahaan = (object) [
            'nm_perusahaan' => 'APOTEK SAKA SASMITRA',
            'email' => 'ichimentei.indo@gmail.com',
            'alamat' => 'Jl. Kimar I No.249, Pandean Lamper, Kec. Gayamsari, Kota Semarang, Jawa Tengah',
        ];;

        $this->tanggal = Carbon::now()->isoFormat('D MMMM Y');
    }

    public function rugi_laba($neraca = false)
    {
        $data['total_beban'] = 0;
        $data['perusahaan'] = $this->perusahaan;
        $data['bulan'] = Carbon::now()->isoFormat("MMMM");

        $akun_laba = Akun::whereIn("kode_akun", [411, 119, 422])->orderBy('kode_akun', "desc")->get();
        // return $akun_laba;
        $data['laba_kotor'] = 0;
        $data['hpp'] = 0;
        $potongan = 0;
        foreach ($akun_laba as  $item) {
            $penjualan_kotor = 0;
            $hpp = 0;

            $transaksi = MasterTransaksi::where("kode_akun", $item->kode_akun)->get();
            foreach ($transaksi as  $value) {
                switch ($value->kode_akun) {

                    case '119':
                        $hpp += $value->debt;
                        break;
                    case '422':
                        $potongan += $value->debt;
                        break;
                    case '411':
                        $penjualan_kotor += $value->kredit;
                        break;
                    default:
                        # code...
                        break;
                }
            }
            switch ($item->kode_akun) {

                case '119':
                    $item->total = $hpp;
                    break;
                case '422':
                    $item->total = $potongan;
                    break;
                case '411':
                    $item->total = $penjualan_kotor;
                    $item->nama_akun = "Penjualan Bersih";
                    break;
                default:
                    # code...
                    break;
            }


            $item->transaksi = $transaksi;
            $data['laba_kotor'] += $penjualan_kotor;
            $data['hpp'] += $hpp;
            if ($item->kode_akun == '411') {
                $item->total = $penjualan_kotor - $potongan;
            }
        }


        $data['laba'] = $data['laba_kotor'] - $data['hpp'];
        $data['akun_laba'] = $akun_laba;
        // return $data;

        $akun_beban = Akun::whereIn("kode_akun", [611, 622, 612, 911])->get();
        foreach ($akun_beban as  $value) {
            $biaya_listrik = 0;
            $biaya_sewa = 0;
            $biaya_air = 0;
            $biaya_gaji = 0;
            $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
            foreach ($transaksi as $values) {
                switch ($values->kode_akun) {
                    case '611':
                        $biaya_listrik += $values->debt;
                        break;
                    case '622':
                        $biaya_gaji += $values->debt;
                        break;
                    case '612':
                        $biaya_air += $values->debt;
                        break;
                    case '911':
                        $biaya_sewa += $value->debt;
                        break;
                }
            }
            switch ($value->kode_akun) {
                case '611':
                    $value->total = $biaya_listrik;
                    break;
                case '622':
                    $value->total = $biaya_gaji;
                    break;
                case '612':
                    $value->total = $biaya_air;
                    break;
                case '911':
                    $value->total = $biaya_sewa;
                    break;
                default:
                    # code...
                    break;
            }
            $value->transaksi = $transaksi;
            $data['total_beban'] += $biaya_listrik + $biaya_air + $biaya_gaji +  $biaya_sewa;
        }

        $data['akun_beban'] = $akun_beban;
        $data['laba_rugi'] =  $data['laba'] - $data['total_beban'];



        if ($neraca == true) {
            return $data['laba_rugi'];
        }
        return view('laporan.rugi-laba', $data);
    }

    public function rugi_laba_change_priode(Request $request)
    {
        if (!isset($request->priode_bulan)) {
            return [
                'status' => 300,
                'message' => "Pilih Bulan Terlebih Dahulu"
            ];
        }

        $data['total_beban'] = 0;
        $data['perusahaan'] = $this->perusahaan;
        // $data['tanggal'] = $this->tanggal;

        $akun_laba = Akun::whereIn("kode_akun", [411, 119, 422])->get();
        $data['laba_kotor'] = 0;
        $data['hpp'] = 0;
        foreach ($akun_laba as  $item) {
            $penjualan_kotor = 0;
            $hpp = 0;
            $potongan = 0;
            $transaksi = MasterTransaksi::where("kode_akun", $item->kode_akun)
                ->whereMonth('tanggal', '=', $request->priode_bulan)
                ->get();
            foreach ($transaksi as  $value) {
                switch ($value->kode_akun) {
                    case '411':
                        $penjualan_kotor += $value->kredit;
                        break;
                    case '119':
                        $hpp += $value->debt;
                        break;
                    case '422':
                        $potongan += $value->debt;
                        break;
                    default:
                        # code...
                        break;
                }
            }
            switch ($item->kode_akun) {
                case '411':
                    $item->total = $penjualan_kotor;
                    break;
                case '119':
                    $item->total = $hpp;
                    break;
                case '422':
                    $item->total = $potongan;
                    break;
                default:
                    # code...
                    break;
            }
            $item->transaksi = $transaksi;
            $data['laba_kotor'] += $penjualan_kotor;
            $data['hpp'] += $hpp;
        }

        $data['laba'] = $data['laba_kotor'] - $data['hpp'];
        $data['akun_laba'] = $akun_laba;

        $akun_beban = Akun::whereIn("kode_akun", [611, 622, 612, 911])->get();
        foreach ($akun_beban as  $value) {
            $biaya_listrik = 0;
            $biaya_sewa = 0;
            $biaya_air = 0;
            $biaya_gaji = 0;
            $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                ->whereMonth('tanggal', '=', $request->priode_bulan)
                ->get();
            foreach ($transaksi as $values) {
                switch ($values->kode_akun) {
                    case '611':
                        $biaya_listrik += $values->debt;
                        break;
                    case '622':
                        $biaya_gaji += $values->debt;
                        break;
                    case '612':
                        $biaya_air += $values->debt;
                        break;
                    case '911':
                        $biaya_sewa += $value->debt;
                        break;
                }
            }
            switch ($value->kode_akun) {
                case '611':
                    $value->total = $biaya_listrik;
                    break;
                case '622':
                    $value->total = $biaya_gaji;
                    break;
                case '612':
                    $value->total = $biaya_air;
                    break;
                case '911':
                    $value->total = $biaya_sewa;
                    break;
                default:
                    # code...
                    break;
            }
            $value->transaksi = $transaksi;
            $data['total_beban'] += $biaya_listrik + $biaya_air + $biaya_gaji +  $biaya_sewa;
        }

        $data['akun_beban'] = $akun_beban;
        $data['laba_rugi'] =  $data['laba'] - $data['total_beban'];
        $data['month'] = Carbon::create(null, $request->priode_bulan, 1)->isoFormat('MMMM');


        return [
            'status' => 200,
            'data' => $data
        ];
    }

    public function rugi_laba_pdf($month = null){
        $data['total_beban'] = 0;
        $data['perusahaan'] = $this->perusahaan;
        $data['bulan'] = Carbon::now()->isoFormat("MMMM");

        $akun_laba = Akun::whereIn("kode_akun", [411, 119, 422])->orderBy('kode_akun', "desc")->get();
        // return $akun_laba;
        $data['laba_kotor'] = 0;
        $data['hpp'] = 0;
        foreach ($akun_laba as  $item) {
            $penjualan_kotor = 0;
            $hpp = 0;
            $potongan = 0;
            if(!isset($month) || $month === null ){
                $transaksi = MasterTransaksi::where("kode_akun", $item->kode_akun)->get();
            } else {
                $transaksi = MasterTransaksi::where("kode_akun", $item->kode_akun)
                ->whereMonth('tanggal', '=', $month)
                ->get();
            }

            foreach ($transaksi as  $value) {
                switch ($value->kode_akun) {

                    case '119':
                        $hpp += $value->debt;
                        break;
                    case '422':
                        $potongan += $value->debt;
                        break;
                    case '411':
                        $penjualan_kotor += $value->kredit;
                        break;
                    default:
                        # code...
                        break;
                }
            }
            switch ($item->kode_akun) {

                case '119':
                    $item->total = $hpp;
                    break;
                case '422':
                    $item->total = $potongan;
                    break;
                case '411':
                    $item->total = $penjualan_kotor;
                    $item->nama_akun = "Penjualan Bersih";
                    break;
                default:
                    # code...
                    break;
            }


            $item->transaksi = $transaksi;
            $data['laba_kotor'] += $penjualan_kotor;
            $data['hpp'] += $hpp;
            if ($item->kode_akun == '411') {
                $item->total = $penjualan_kotor - $potongan;
            }
        }


        $data['laba'] = $data['laba_kotor'] - $data['hpp'];
        $data['akun_laba'] = $akun_laba;

        $akun_beban = Akun::whereIn("kode_akun", [611, 622, 612, 911])->get();
        foreach ($akun_beban as  $value) {
            $biaya_listrik = 0;
            $biaya_sewa = 0;
            $biaya_air = 0;
            $biaya_gaji = 0;
            if(!isset($month) || $month === null ){
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
            } else {
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereMonth('tanggal', '=', $month)
                ->get();
            }

            foreach ($transaksi as $values) {
                switch ($values->kode_akun) {
                    case '611':
                        $biaya_listrik += $values->debt;
                        break;
                    case '622':
                        $biaya_gaji += $values->debt;
                        break;
                    case '612':
                        $biaya_air += $values->debt;
                        break;
                    case '911':
                        $biaya_sewa += $value->debt;
                        break;
                }
            }
            switch ($value->kode_akun) {
                case '611':
                    $value->total = $biaya_listrik;
                    break;
                case '622':
                    $value->total = $biaya_gaji;
                    break;
                case '612':
                    $value->total = $biaya_air;
                    break;
                case '911':
                    $value->total = $biaya_sewa;
                    break;
                default:
                    # code...
                    break;
            }
            $value->transaksi = $transaksi;
            $data['total_beban'] += $biaya_listrik + $biaya_air + $biaya_gaji +  $biaya_sewa;
        }

        if(isset($month) || $month !== null ){
            $data['month'] = $month;
        } else {
            $data['month'] = null;
        }

        $data['akun_beban'] = $akun_beban;
        $data['laba_rugi'] =  $data['laba'] - $data['total_beban'];

        $pdf = PDF::loadView('pdf.rugi-labaPDF', $data);
        return $pdf->stream();
    }

    public function neraca()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = $this->tanggal;
        $data['kas'] = Akun::where('kode_akun', 111)->sum('jumlah');
        $data['kas_bank'] = Akun::where('kode_akun', 112)->sum('jumlah');
        $data['hutang'] = Akun::where('kode_akun', 211)->sum('jumlah');
        $data['persediaan'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->first();
        // $data['penjualan'] = MasterTransaksi::where('type', 2)->sum('kredit');
        $data['pembelian'] = MasterTransaksi::where('type', 1)->sum('debt');
        $data['potongan'] = MasterTransaksi::where('type', 1)->sum('potongan');
        $data['persediaan'] = Akun::where('kode_akun', 113)->select('jumlah')->first();

        $data['gedung'] = Akun::where('kode_akun', 116)->sum('jumlah');
        $data['kendaraan'] = Akun::where('kode_akun', 115)->sum('jumlah');
        $data['peralatan_kantor'] = Akun::where('kode_akun', 118)->sum('jumlah');

        // return $data;
        //me
        $data['hpp'] = Akun::where('kode_akun', 119)->sum('jumlah');
        $data['penjualan'] = Akun::where('kode_akun', 411)->sum('jumlah');
        $data['retur'] = Akun::select('jumlah')->where('kode_akun', 511)->first();
        $data['total_harga_beli_produk'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->get();
        $data['pasiva'] = Akun::where('kode_akun', 311)->first();

        //pasiva 
        $data['hpp'] = Akun::where('kode_akun', 119)->sum('jumlah');
        $data['laba_kotor'] = Akun::where('kode_akun', 411)->sum('jumlah');
        $data['laba'] = $data['laba_kotor'] - $data['hpp'];


        $data['biaya_air'] = Akun::where('kode_akun', 612)->sum('jumlah');
        $data['biaya_listrik'] = Akun::where('kode_akun', 611)->sum('jumlah');
        $data['biaya_gaji'] = Akun::where('kode_akun', 622)->sum('jumlah');
        $data['biaya_sewa'] = Akun::where('kode_akun', 911)->sum('jumlah');
        $data['total_beban'] = $data['biaya_air'] +  $data['biaya_listrik'] +  $data['biaya_gaji'] + $data['biaya_sewa'];

        $data['laba_rugi'] =  $data['laba'] - $data['total_beban'];
        $data['aktiva'] = Akun::where('kode_akun', 113)->sum('jumlah') + $data['kas'] + $data['kas_bank'];


        // return $data['laba'];

        return view('laporan.neraca', $data);
    }

    public function neraca2()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = $this->tanggal;

        $akun_aktiva = Akun::whereIn("kode_akun", [111, 112, 115, 116, 118, 113])->orderBy('kode_akun', "asc")->get();
        $akun_pasiva =
            Akun::whereIn("kode_akun", [211, 311])->orderBy('kode_akun', "asc")->get();

        $aktiva = 0;
        $pasiva = 0;
        foreach ($akun_aktiva as $value) {

            if ($value->kode_akun == 111) {
                $kas_debit = 0;
                $kas_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $kas_debit += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $kas_kredit += $values->kredit;
                    }
                }

                $value->kas_debit = $kas_debit;
                $value->kas_kredit = $kas_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->kas_debit - $value->kas_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 112) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 115) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 116) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 118) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 113) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            }
        }

        foreach ($akun_pasiva as $v) {
            if ($v->kode_akun == 211) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)->get();
                foreach ($transaksi as  $vs) {
                    if ($vs->debt != null) {
                        $jml_debt += $vs->debt;
                    }
                    if ($vs->kredit != null) {
                        $jml_kredit += $vs->kredit;
                    }
                }

                $v->jml_debt = $jml_debt;
                $v->jml_kredit = $jml_kredit;
                $v->transaksi = $transaksi;
                $v->total = abs($v->jml_debt - $v->jml_kredit);
                $pasiva += $v->total;
            }

            if ($v->kode_akun == 311) {

                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)->get();
                foreach ($transaksi as  $vs) {
                    if ($vs->debt != null) {
                        $jml_debt += $vs->debt;
                    }
                    if ($vs->kredit != null) {
                        $jml_kredit += $vs->kredit;
                    }
                }

                $v->jml_debt = $jml_debt;
                $v->jml_kredit = $jml_kredit;
                $v->transaksi = $transaksi;
                $v->total = abs($v->jml_debt - $v->jml_kredit);
                $pasiva += $v->total;
            }
        }



        $get_laba_rugi = $this->rugi_laba(true);

        $data['aktiva'] = $akun_aktiva;
        $data['pasiva'] = $akun_pasiva;
        $data['total_aktiva'] = $aktiva;
        $data['total_pasiva'] = $pasiva + $get_laba_rugi;
        $data['laba_rugi'] = $get_laba_rugi;
        // return $data;
        return view('laporan.neraca2', $data);
    }

    public function neraca_change_priode(Request $request)
    {
        $tanggal1 = $request->thn_1 . '-' . $request->bln_1 . '-' . $request->tgl_1;
        $tanggal2 = $request->thn_2 . '-' . $request->bln_2 . '-' . $request->tgl_2;
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = ['tanggal_awal' => $tanggal1, 'tanggal_akhir' => $tanggal2];

        $akun_aktiva = Akun::whereIn("kode_akun", [111, 112, 115, 116, 118, 113])->orderBy('kode_akun', "asc")->get();
        $akun_pasiva =
            Akun::whereIn("kode_akun", [211, 311])->orderBy('kode_akun', "asc")->get();

        $aktiva = 0;
        $pasiva = 0;
        foreach ($akun_aktiva as $value) {

            if ($value->kode_akun == 111) {
                $kas_debit = 0;
                $kas_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $kas_debit += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $kas_kredit += $values->kredit;
                    }
                }

                $value->kas_debit = $kas_debit;
                $value->kas_kredit = $kas_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->kas_debit - $value->kas_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 112) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 115) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 116) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 118) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 113) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            }
        }

        foreach ($akun_pasiva as $v) {
            if ($v->kode_akun == 211) {
                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $vs) {
                    if ($vs->debt != null) {
                        $jml_debt += $vs->debt;
                    }
                    if ($vs->kredit != null) {
                        $jml_kredit += $vs->kredit;
                    }
                }

                $v->jml_debt = $jml_debt;
                $v->jml_kredit = $jml_kredit;
                $v->transaksi = $transaksi;
                $v->total = abs($v->jml_debt - $v->jml_kredit);
                $pasiva +=  $v->total;
            }

            if ($v->kode_akun == 311) {

                $jml_debt = 0;
                $jml_kredit = 0;
                $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)
                    ->whereBetween('tanggal', [$tanggal1, $tanggal2])
                    ->get();
                foreach ($transaksi as  $vs) {
                    if ($vs->debt != null) {
                        $jml_debt += $vs->debt;
                    }
                    if ($vs->kredit != null) {
                        $jml_kredit += $vs->kredit;
                    }
                }

                $v->jml_debt = $jml_debt;
                $v->jml_kredit = $jml_kredit;
                $v->transaksi = $transaksi;
                $v->total = abs($v->jml_debt - $v->jml_kredit);
                $pasiva +=  $v->total;
            }
        }



        $get_laba_rugi = $this->rugi_laba_change_priode2($tanggal1, $tanggal2);

        $data['aktiva'] = $akun_aktiva;
        $data['pasiva'] = $akun_pasiva;
        $data['total_aktiva'] = $aktiva;
        $data['total_pasiva'] = $pasiva + $get_laba_rugi;
        $data['laba_rugi'] = $get_laba_rugi;


        return $data;
    }

    public function neraca_pdf(Request $request){
        $data['perusahaan'] = $this->perusahaan;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
            $data['tanggal_default'] = $this->tanggal;
            $data['tanggal'] = null;
        } else {
            $data['tanggal_default'] = null;
            $data['tanggal'] = ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir];
        }


        $akun_aktiva = Akun::whereIn("kode_akun", [111, 112, 115, 116, 118, 113])->orderBy('kode_akun', "asc")->get();
        $akun_pasiva =
            Akun::whereIn("kode_akun", [211, 311])->orderBy('kode_akun', "asc")->get();

        $aktiva = 0;
        $pasiva = 0;
        foreach ($akun_aktiva as $value) {

            if ($value->kode_akun == 111) {
                $kas_debit = 0;
                $kas_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $kas_debit += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $kas_kredit += $values->kredit;
                    }
                }

                $value->kas_debit = $kas_debit;
                $value->kas_kredit = $kas_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->kas_debit - $value->kas_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 112) {
                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 115) {
                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 116) {
                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 118) {
                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            } elseif ($value->kode_akun == 113) {
                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $values) {
                    if ($values->debt != null) {
                        $jml_debt += $values->debt;
                    }
                    if ($values->kredit != null) {
                        $jml_kredit += $values->kredit;
                    }
                }

                $value->jml_debt = $jml_debt;
                $value->jml_kredit = $jml_kredit;
                $value->transaksi = $transaksi;
                $value->total = $value->jml_debt - $value->jml_kredit;
                $aktiva += $value->total;
            }
        }

        foreach ($akun_pasiva as $v) {
            if ($v->kode_akun == 211) {
                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $vs) {
                    if ($vs->debt != null) {
                        $jml_debt += $vs->debt;
                    }
                    if ($vs->kredit != null) {
                        $jml_kredit += $vs->kredit;
                    }
                }

                $v->jml_debt = $jml_debt;
                $v->jml_kredit = $jml_kredit;
                $v->transaksi = $transaksi;
                $v->total = abs($v->jml_debt - $v->jml_kredit);
                $pasiva += $v->total;
            }

            if ($v->kode_akun == 311) {

                $jml_debt = 0;
                $jml_kredit = 0;
                if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
                    $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)->get();
                } else {
                    $transaksi = MasterTransaksi::where("kode_akun", $v->kode_akun)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
                }
                foreach ($transaksi as  $vs) {
                    if ($vs->debt != null) {
                        $jml_debt += $vs->debt;
                    }
                    if ($vs->kredit != null) {
                        $jml_kredit += $vs->kredit;
                    }
                }

                $v->jml_debt = $jml_debt;
                $v->jml_kredit = $jml_kredit;
                $v->transaksi = $transaksi;
                $v->total = abs($v->jml_debt - $v->jml_kredit);
                $pasiva += $v->total;
            }
        }


        if(!isset($tanggal_awal) || $tanggal_awal === null || !isset($tanggal_akhir) || $tanggal_akhir === null){
            $get_laba_rugi = $this->rugi_laba(true);
        } else {
            $get_laba_rugi = $this->rugi_laba_change_priode2($tanggal_awal, $tanggal_akhir);
        }
    

        $data['aktiva'] = $akun_aktiva;
        $data['pasiva'] = $akun_pasiva;
        $data['total_aktiva'] = $aktiva;
        $data['total_pasiva'] = $pasiva + $get_laba_rugi;
        $data['laba_rugi'] = $get_laba_rugi;

        $pdf = PDF::loadView('pdf.neracaPDF', $data);
        return $pdf->stream();

    }

    public function persediaan()
    {
        $data['barang'] = Barang::withSum('penjualan', 'jumlah')->withSum('pembelian', 'jumlah')->get();
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = $this->tanggal;
        return view('laporan.persediaan', $data);
    }

    public function persediaan_pdf()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['barang'] = Barang::withSum('penjualan', 'jumlah')->withSum('pembelian', 'jumlah')->get();
        $data['tanggal'] = $this->tanggal;
        $pdf = PDF::loadView('pdf.persediaanPDF', $data);
        return $pdf->stream();
    }

    public function hutang()
    {
        // $data['hutang'] = Pembelian::where('status', 2)->with('transaksi')->get();
        $data['hutang'] = MasterTransaksi::where('kode_akun', 211)->with(['pembelian' => function ($query) {
            $query->first();
        }])->get();
        $data['totalHutang'] = Pembelian::where('status', 2)->sum('total');
        return view('laporan.hutang', $data);
    }

    public function pembelian()
    {
        $data['perusahaan'] = $this->perusahaan;
        // $data['pembelian'] = Pembelian::where('status', 1)->with('transaksi', 'supplier')->get();
        $data['transaksi'] = MasterTransaksi::whereHas('pembelian', function ($query) {
            $query->where('status', 1);
        })->with('pembelian', function ($query) {
            $query->with('supplier')->get();
        })->get();
        $data['Totalpembelian'] = MasterTransaksi::whereHas('pembelian', function ($query) {
            $query->where('status', 1);
        })->sum('debt');
        $data['tanggal'] = $this->tanggal;
        return view('laporan.pembelian', $data);
    }

    public function pembelian_pdf(Request $request)
    {
        if(!isset($request->tanggal_awal) || $request->tanggal_awal === null ){
            $data['transaksi'] = MasterTransaksi::whereHas('pembelian', function ($query) {
                $query->where('status', 1);
            })->with('pembelian', function ($query) {
                $query->with('supplier')->get();
            })->get();
            $data['Totalpembelian'] = MasterTransaksi::whereHas('pembelian', function ($query) {
                $query->where('status', 1);
            })->sum('debt');
        } else {
            $tanggal_awal = date('Y-m-d', strtotime($request->tanggal_awal));
            $tanggal_akhir = date('Y-m-d', strtotime($request->tanggal_akhir));
            $data['transaksi'] = MasterTransaksi::whereHas('pembelian', function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->where('status', 1)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            })->with('pembelian', function ($query) {
                $query->with('supplier')->get();
            })->get();
            $data['Totalpembelian'] = MasterTransaksi::whereHas('pembelian', function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->where('status', 1)->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            })->sum('debt');
            $data['tanggal'] = ['tanggal_awal' => $request->tanggal_awal, 'tanggal_akhir' => $request->tanggal_akhir];
        }
        $data['perusahaan'] = $this->perusahaan;

        $pdf = PDF::loadView('pdf.pembelianPDF', $data);
        return $pdf->stream();
    }

    public function pembelian_change_priode(Request $request)
    {
        $tanggal1 = $request->thn_1 . '-' . $request->bln_1 . '-' . $request->tgl_1;
        $tanggal2 = $request->thn_2 . '-' . $request->bln_2 . '-' . $request->tgl_2;
        $data['transaksi'] = MasterTransaksi::whereHas('pembelian', function ($query) use ($tanggal1, $tanggal2) {
            $query->where('status', 1)->whereBetween('tanggal', [$tanggal1, $tanggal2]);
        })->with('pembelian', function ($query) {
            $query->with('supplier')->get();
        })->get();
        $data['Totalpembelian'] = MasterTransaksi::whereHas('pembelian', function ($query) use ($tanggal1, $tanggal2) {
            $query->where('status', 1)->whereBetween('tanggal', [$tanggal1, $tanggal2]);
        })->sum('debt');
        $data['tanggal_1'] = $tanggal1;
        $data['tanggal_2'] = $tanggal2;
        return $data;
    }

    public function penjualan()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['penjualan'] = Penjualan::with('transaksi', 'dokter')->get();
        $data['Totalpenjualan'] = Penjualan::sum('subtotal');
        $data['tanggal'] = $this->tanggal;
        // return $data;
        return view('laporan.penjualan', $data);
    }

    public function penjualan_pdf(Request $request)
    {
        if(!isset($request->tanggal_awal) || $request->tanggal_awal === null || !isset($request->tanggal_akhir) || $request->tanggal_akhir === null){
            $data['perusahaan'] = $this->perusahaan;
            $data['penjualan'] = Penjualan::with('transaksi', 'dokter')->get();
            $data['Totalpenjualan'] = Penjualan::sum('subtotal');
        } else {
            $tanggal_awal = date('Y-m-d', strtotime($request->tanggal_awal));
            $tanggal_akhir = date('Y-m-d', strtotime($request->tanggal_akhir));
            $data['penjualan'] = Penjualan::whereHas('transaksi', function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            })->with(['transaksi' => function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            }])->with('dokter')->get();
            $data['Totalpenjualan'] = Penjualan::whereHas('transaksi', function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            })->sum('subtotal');
            $data['tanggal'] = ['tanggal_awal' => $request->tanggal_awal, 'tanggal_akhir' => $request->tanggal_akhir];
        }
        $data['perusahaan'] = $this->perusahaan;

        $pdf = PDF::loadView('pdf.penjualanPDF2', $data);
        return $pdf->stream();
    }

    public function penjualan_change_priode(Request $request)
    {
        $tanggal1 = $request->thn_1 . '-' . $request->bln_1 . '-' . $request->tgl_1;
        $tanggal2 = $request->thn_2 . '-' . $request->bln_2 . '-' . $request->tgl_2;
        $data['penjualan'] = Penjualan::whereHas('transaksi', function ($query) use ($tanggal1, $tanggal2) {
            $query->whereBetween('tanggal', [$tanggal1, $tanggal2]);
        })->with(['transaksi' => function ($query) use ($tanggal1, $tanggal2) {
            $query->whereBetween('tanggal', [$tanggal1, $tanggal2]);
        }])->with('dokter')->get();
        $data['Totalpenjualan'] = Penjualan::whereHas('transaksi', function ($query) use ($tanggal1, $tanggal2) {
            $query->whereBetween('tanggal', [$tanggal1, $tanggal2]);
        })->sum('subtotal');
        $data['tanggal_1'] = $tanggal1;
        $data['tanggal_2'] = $tanggal2;
        return $data;
    }

    public function perubahan_modal()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = $this->tanggal;
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = $this->tanggal;
        $data['kas'] = Akun::where('kode_akun', 111)->sum('jumlah');
        $data['modal'] = Akun::where('kode_akun', 311)->sum('jumlah');
        $data['biaya'] = Akun::whereBetween('kode_akun', [611, 699])->sum('jumlah');
        $data['hutang'] = Akun::where('kode_akun', 211)->sum('jumlah');
        $data['persediaan'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->first();
        $data['penjualan'] = MasterTransaksi::where('type', 2)->sum('kredit');
        $data['pembelian'] = MasterTransaksi::where('type', 1)->sum('debt');
        $data['potongan'] = MasterTransaksi::where('type', 1)->sum('potongan');
        $data['persediaan'] = Akun::where('kode_akun', 113)->select('jumlah')->first();
        $data['retur'] = Akun::select('jumlah')->where('kode_akun', 511)->first();
        $data['total_harga_beli_produk'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->get();
        $data['prive'] = Akun::where('kode_akun', 312)->sum('jumlah');
        return view('laporan.perubahan-modal', $data);
    }

    public function rugi_laba_change_priode2($awal, $akhir)
    {

        $data['total_beban'] = 0;
        $data['perusahaan'] = $this->perusahaan;
        // $data['tanggal'] = $this->tanggal;

        $akun_laba = Akun::whereIn("kode_akun", [411, 119, 422])->get();
        $data['laba_kotor'] = 0;
        $data['hpp'] = 0;
        foreach ($akun_laba as  $item) {
            $penjualan_kotor = 0;
            $hpp = 0;
            $potongan = 0;
            $transaksi = MasterTransaksi::where("kode_akun", $item->kode_akun)
                ->whereBetween('tanggal', [$awal, $akhir])
                ->get();
            foreach ($transaksi as  $value) {
                switch ($value->kode_akun) {
                    case '411':
                        $penjualan_kotor += $value->kredit;
                        break;
                    case '119':
                        $hpp += $value->debt;
                        break;
                    case '422':
                        $potongan += $value->debt;
                        break;
                    default:
                        # code...
                        break;
                }
            }
            switch ($item->kode_akun) {
                case '411':
                    $item->total = $penjualan_kotor;
                    break;
                case '119':
                    $item->total = $hpp;
                    break;
                case '422':
                    $item->total = $potongan;
                    break;
                default:
                    # code...
                    break;
            }
            $item->transaksi = $transaksi;
            $data['laba_kotor'] += $penjualan_kotor;
            $data['hpp'] += $hpp;
        }

        $data['laba'] = $data['laba_kotor'] - $data['hpp'];
        $data['akun_laba'] = $akun_laba;

        $akun_beban = Akun::whereIn("kode_akun", [611, 622, 612, 911])->get();
        foreach ($akun_beban as  $value) {
            $biaya_listrik = 0;
            $biaya_sewa = 0;
            $biaya_air = 0;
            $biaya_gaji = 0;
            $transaksi = MasterTransaksi::where("kode_akun", $value->kode_akun)
                ->whereBetween('tanggal', [$awal, $akhir])
                ->get();
            foreach ($transaksi as $values) {
                switch ($values->kode_akun) {
                    case '611':
                        $biaya_listrik += $values->debt;
                        break;
                    case '622':
                        $biaya_gaji += $values->debt;
                        break;
                    case '612':
                        $biaya_air += $values->debt;
                        break;
                    case '911':
                        $biaya_sewa += $value->debt;
                        break;
                }
            }
            switch ($value->kode_akun) {
                case '611':
                    $value->total = $biaya_listrik;
                    break;
                case '622':
                    $value->total = $biaya_gaji;
                    break;
                case '612':
                    $value->total = $biaya_air;
                    break;
                case '911':
                    $value->total = $biaya_sewa;
                    break;
                default:
                    # code...
                    break;
            }
            $value->transaksi = $transaksi;
            $data['total_beban'] += $biaya_listrik + $biaya_air + $biaya_gaji +  $biaya_sewa;
        }

        $data['akun_beban'] = $akun_beban;
        $data['laba_rugi'] =  $data['laba'] - $data['total_beban'];



        return
            $data['laba_rugi'];
    }
}
