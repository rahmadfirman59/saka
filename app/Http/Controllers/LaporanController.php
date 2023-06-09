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
            'alamat' => 'JALAN DIPONEGORO SEMARANG',
        ];;

        $this->tanggal = Carbon::now()->isoFormat('D MMMM Y');
    }

    public function rugi_laba()
    {
        $data['penjualan'] = MasterTransaksi::where('type', 2)->sum('kredit');
        $data['pembelian'] = MasterTransaksi::where('type', 1)->sum('debt');
        $data['potongan'] = MasterTransaksi::where('type', 1)->sum('potongan');
        $data['persediaan'] = Akun::where('kode_akun', 113)->select('jumlah')->first();
        $data['retur'] = Akun::select('jumlah')->where('kode_akun', 511)->first();
        $data['biaya'] = Akun::whereBetween('kode_akun', [611, 699])->sum('jumlah');
        //me
        $data['hpp'] = Akun::where('kode_akun', 119)->sum('jumlah');
        $data['laba_kotor'] = Akun::where('kode_akun', 411)->sum('jumlah');
        $data['laba'] = $data['laba_kotor'] - $data['hpp'];
        $data['total_harga_beli_produk'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->get();
        $data['perusahaan'] = $this->perusahaan;

        $data['tanggal'] = $this->tanggal;
        return view('laporan.rugi-laba', $data);
    }

    public function neraca()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['tanggal'] = $this->tanggal;
        $data['kas'] = Akun::where('kode_akun', 111)->sum('jumlah');
        $data['hutang'] = Akun::where('kode_akun', 211)->sum('jumlah');
        $data['persediaan'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->first();
        // $data['penjualan'] = MasterTransaksi::where('type', 2)->sum('kredit');
        $data['pembelian'] = MasterTransaksi::where('type', 1)->sum('debt');
        $data['potongan'] = MasterTransaksi::where('type', 1)->sum('potongan');
        $data['persediaan'] = Akun::where('kode_akun', 113)->select('jumlah')->first();

        //me
        $data['hpp'] = Akun::where('kode_akun', 119)->sum('jumlah');
        $data['penjualan'] = Akun::where('kode_akun', 411)->sum('jumlah');

        $data['retur'] = Akun::select('jumlah')->where('kode_akun', 511)->first();

        $data['total_harga_beli_produk'] = Barang::select(DB::raw('sum(harga_beli * stok) as total'))->get();
        $data['aktiva'] = Akun::where('kode_akun', 113)->sum('jumlah') + $data['kas'] + $data['hpp'];
        $data['pasiva'] =
            Akun::where('kode_akun', 311)->first();
        // $data['modal'] = Akun::where('kode_akun', 311)->sum('jumlah') +;
        // dd($data);
        return view('laporan.neraca', $data);
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

    public function pembelian_pdf()
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
        return $data;
    }

    public function penjualan()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['penjualan'] = Penjualan::with('transaksi', 'dokter')->get();
        $data['Totalpenjualan'] = Penjualan::sum('subtotal');
        $data['tanggal'] = $this->tanggal;
        return view('laporan.penjualan', $data);
    }

    public function penjualan_pdf()
    {
        $data['perusahaan'] = $this->perusahaan;
        $data['penjualan'] = Penjualan::with('transaksi', 'dokter')->get();
        $data['Totalpenjualan'] = Penjualan::sum('subtotal');
        $data['tanggal'] = $this->tanggal;
        $pdf = PDF::loadView('pdf.penjualanPDF2', $data);
        return $pdf->stream();
    }

    public function penjualan_change_priode(Request $request)
    {
        $tanggal1 = $request->thn_1 . '-' . $request->bln_1 . '-' . $request->tgl_1;
        $tanggal2 = $request->thn_2 . '-' . $request->bln_2 . '-' . $request->tgl_2;
        $data['penjualan'] = Penjualan::with(['transaksi' => function ($query) use ($tanggal1, $tanggal2) {
            $query->whereBetween('tanggal', [$tanggal1, $tanggal2]);
        }])->with('dokter')->get();
        $data['Totalpenjualan'] = Penjualan::whereBetween('created_at', [$tanggal1, $tanggal2])->sum('subtotal');
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
}
