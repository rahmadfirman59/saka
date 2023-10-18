<?php

use App\Helpers\App;
use App\Http\Controllers\AkunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\JurnalPembelianController;
use App\Http\Controllers\JurnalPenjualanController;
use App\Http\Controllers\JurnalPenyesuaianController;
use App\Http\Controllers\JurnalUmumController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatRacikController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiObatRacikController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\TransaksiPenjualanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('post.login');
Route::get('login', [AuthController::class, 'index'])->name('login');

Route::group(['middleware' => ['ceklogin']], function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('grafik')->group(function () {
        Route::get('/', [GrafikController::class, 'index'])->name('grafik');
        Route::get('/get-data', [GrafikController::class, 'get_data']);
    });


    Route::prefix('master')->group(function () {

        Route::prefix('akun')->group(function () {
            Route::get('/', [AkunController::class, 'index'])->name('akun');
            Route::post('/store-update', [AkunController::class, 'store_update']);
            Route::post('/tambah-modal', [AkunController::class, 'tambah_modal']);
            Route::get('/detail/{id}', [AkunController::class, 'detail']);
            Route::delete('/delete/{id}', [AkunController::class, 'delete']);
        });

        Route::prefix('barang')->group(function () {
            Route::get('/', [BarangController::class, 'index'])->name('barang');
            Route::post('/store-update', [BarangController::class, 'store_update']);
            Route::get('/detail/{id}', [BarangController::class, 'detail']);
            Route::get('/restore/{id}', [BarangController::class, 'restore']);
            Route::delete('/delete/{id}', [BarangController::class, 'delete']);
        });

        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('supplier');
            Route::post('/store-update', [SupplierController::class, 'store_update']);
            Route::get('/detail/{id}', [SupplierController::class, 'detail']);
            Route::delete('/delete/{id}', [SupplierController::class, 'delete']);
        });

        Route::prefix('dokter')->group(function () {
            Route::get('/', [DokterController::class, 'index'])->name('dokter');
            Route::post('/store-update', [DokterController::class, 'store_update']);
            Route::get('/detail/{id}', [DokterController::class, 'detail']);
            Route::delete('/delete/{id}', [DokterController::class, 'delete']);
        });

        Route::prefix('pasien')->group(function () {
            Route::get('/', [PasienController::class, 'index'])->name('pasien');
            Route::get('/check-history/{id}', [PasienController::class, 'check_history']);
            Route::post('/store-update', [PasienController::class, 'store_update']);
            Route::get('/detail/{id}', [PasienController::class, 'detail']);
            Route::delete('/delete/{id}', [PasienController::class, 'delete']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user');
            Route::post('/store', [UserController::class, 'store']);
            Route::post('/update', [UserController::class, 'update']);
            Route::get('/detail/{id}', [UserController::class, 'detail']);
            Route::delete('/delete/{id}', [UserController::class, 'delete']);
        });

        Route::prefix('obat-racik')->group(function () {
            Route::get('/', [ObatRacikController::class, 'index'])->name('obat-racik');
            Route::get('/detail/{id}', [ObatRacikController::class, 'detail']);
            Route::get('/add', [ObatRacikController::class, 'add'])->name('obat-racik-add');
            Route::post('/store', [ObatRacikController::class, 'store']);
            Route::delete('/delete/{id}', [ObatRacikController::class, 'delete']);
        });
    });

    Route::prefix('transaksi')->group(function () {
        Route::prefix('penjualan')->group(function () {
            Route::get('/', [TransaksiPenjualanController::class, 'index'])->name('transaksi.penjualan');
            Route::post('/add-keranjang', [TransaksiPenjualanController::class, 'add_keranjang']);
            Route::get('/get-keranjang/{id}/{batchPrefix}', [TransaksiPenjualanController::class, 'get_keranjang']);
            Route::get('/delete-keranjang/{id}', [TransaksiPenjualanController::class, 'delete_keranjang']);

            Route::post('/store', [TransaksiPenjualanController::class, 'store']);
        });

        Route::prefix('pembelian')->group(function () {
            Route::get('/', [TransaksiPembelianController::class, 'index'])->name('transaksi.pembelian');
            Route::post('/add-keranjang', [TransaksiPembelianController::class, 'add_keranjang']);
            Route::get('/delete-keranjang/{id}', [TransaksiPembelianController::class, 'delete_keranjang']);

            Route::post('/store', [TransaksiPembelianController::class, 'store']);
        });

        Route::prefix('obat-racik')->group(function () {
            Route::get('/', [TransaksiObatRacikController::class, 'index'])->name('transaksi.obat-racik');
            Route::post('/add-keranjang', [TransaksiObatRacikController::class, 'add_keranjang']);
            Route::get('/delete-keranjang/{id}', [TransaksiObatRacikController::class, 'delete_keranjang']);

            Route::post('/store', [TransaksiObatRacikController::class, 'store']);
        });

        Route::prefix('pembayaran-tempo')->group(function () {
            Route::get('/', [JurnalPembelianController::class, 'pembayaran_tempo'])->name('transaksi.pembayaran-tempo');
        });
    });

    Route::prefix('jurnal')->group(function () {
        Route::prefix('jurnal-umum')->group(function () {
            Route::get('/', [JurnalUmumController::class, 'index'])->name('jurnal.umum');
            Route::post('/store', [JurnalUmumController::class, 'store']);
            Route::post('/change-priode', [JurnalUmumController::class, 'change_priode']);
            Route::post('/reset-priode', [JurnalUmumController::class, 'reset_priode']);
        });

        Route::prefix('jurnal-penjualan')->group(function () {
            Route::get('/', [JurnalPenjualanController::class, 'index'])->name('jurnal.penjualan');
            Route::get('/detail-penjualan/{id}', [JurnalPenjualanController::class, 'detail_penjualan']);
            Route::get('/cetak-penjualan/{id}', [JurnalPenjualanController::class, 'cetak_penjualan']);
            Route::post('/login-admin', [JurnalPenjualanController::class, 'login_admin']);
            Route::post('/batal-penjualan', [JurnalPenjualanController::class, 'batal_penjualan'])->middleware('ceklevel');
            Route::post('/clear-session', [JurnalPenjualanController::class, 'clear_session']);
        });

        Route::prefix('jurnal-pembelian')->group(function () {
            Route::get('/', [JurnalPembelianController::class, 'index'])->name('jurnal.pembelian');
            Route::get('/detail-pembelian/{id}', [JurnalPembelianController::class, 'detail_pembelian']);
            Route::get('/cetak-pembelian/{id}', [JurnalPembelianController::class, 'cetak_pembelian']);
            Route::post('/login-admin', [JurnalPembelianController::class, 'login_admin']);
            Route::post('/batal-pembelian', [JurnalPembelianController::class, 'batal_pembelian'])->middleware('ceklevel');
            Route::post('/clear-session', [JurnalPembelianController::class, 'clear_session']);
        });

        Route::prefix('jurnal-penyesuaian')->group(function () {
            Route::get('/', [JurnalPenyesuaianController::class, 'index'])->name('jurnal.penyesuaian');
            Route::get('/detail/{id}', [JurnalPenyesuaianController::class, 'detail']);
            Route::post('/store-update', [JurnalPenyesuaianController::class, 'store_update']);
        });
    });

    Route::prefix('laporan')->group(function () {
        Route::prefix('rugi-laba')->group(function () {
            Route::get('/', [LaporanController::class, 'rugi_laba'])->name('laporan.rugiLaba');
            Route::post('/change-priode', [LaporanController::class, 'rugi_laba_change_priode']);
        });
        Route::prefix('neraca')->group(function () {
            Route::get('/', [LaporanController::class, 'neraca'])->name('laporan.neraca');
        });
        Route::prefix('persediaan')->group(function () {
            Route::get('/', [LaporanController::class, 'persediaan'])->name('laporan.persediaan');
            Route::get('/pdf', [LaporanController::class, 'persediaan_pdf']);
        });
        Route::prefix('perubahan-modal')->group(function () {
            Route::get('/', [LaporanController::class, 'perubahan_modal'])->name('laporan.perubahan-modal');
        });
        Route::prefix('hutang')->group(function () {
            Route::get('/', [LaporanController::class, 'hutang'])->name('laporan.hutang');
        });
        Route::prefix('pembelian')->group(function () {
            Route::get('/', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
            Route::post('/change-priode', [LaporanController::class, 'pembelian_change_priode']);
            Route::get('/pdf', [LaporanController::class, 'pembelian_pdf']);
        });

        Route::prefix('penjualan')->group(function () {
            Route::get('/', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
            Route::post('/change-priode', [LaporanController::class, 'penjualan_change_priode']);
            Route::get('/pdf', [LaporanController::class, 'penjualan_pdf']);
        });
    });


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
