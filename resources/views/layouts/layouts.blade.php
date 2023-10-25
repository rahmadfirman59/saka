@php
    use App\Helpers\App;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="author" content="">

    <title>Akuntansi SAKA SASMITRA</title>
    <link rel="icon" type="image/png" href="{{ asset('public\sbadmin\img\icon\favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('public\sbadmin\img\icon\favicon-16x16.png') }}" sizes="16x16"/> 

    <link href="{{ asset('public/sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,500&display=swap" rel="stylesheet">
    

    <!-- Custom styles for this template-->
    <link href="{{ asset('public/sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/plugins/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/sbadmin/css/style-additional.css') }}">

    @yield('css')

    <style>
        .nav-link.notification-link{
            padding: 0.5rem .75rem!important;
            height: unset!important;
        }

        .fade:not(.show){
            display: none;
        }
    </style>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('public\sbadmin\img\logo-sasmitra.png') }}" width="50" alt="logo_sasmitra">
                <div class="sidebar-brand-text mr-3">Apotik Saka Sasmitra</div>
            </a>


            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php
            $url_menu = Request::segment(1);
            $url_submenu = Request::segment(2);
            ?>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item @if($url_menu == "dashboard") active @endif">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Grafik
            </div>

            <li class="nav-item @if($url_menu == "grafik") active @endif">
                <a class="nav-link" href="{{ route('grafik') }}">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Grafik</span></a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Data Master
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            @if(Session::get('useractive')->level == 'superadmin')
            <li class="nav-item @if($url_menu == "master") active @endif">
                <a class="nav-link @if($url_menu == "master") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Master</span>
                </a>
                <div id="collapsePages" class="collapse  @if($url_menu == "master") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'akun') active @endif" href="{{ route('akun') }}">Akun</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'barang') active @endif" href="{{ route('barang') }}">Barang</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'supplier') active @endif" href="{{ route('supplier') }}">Supplier</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'dokter') active @endif" href="{{ route('dokter') }}">Dokter</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'pasien') active @endif" href="{{ route('pasien') }}">Pasien</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'user') active @endif" href="{{ route('user') }}">User</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'obat-racik') active @endif" href="{{ route('obat-racik') }}">Obat Racik</a>
                    </div>
                </div>
            </li>
            <li class="nav-item @if($url_menu == "transaksi") active @endif">
                <a class="nav-link collapsed @if($url_menu == "transaksi") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages2"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-cart"></i>
                    <span>Transaksi</span>
                </a>
                <div id="collapsePages2" class="collapse @if($url_menu == "transaksi") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'penjualan') active @endif" href="{{ route('transaksi.penjualan') }}">Penjualan</a>
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'pembelian') active @endif" href="{{ route('transaksi.pembelian') }}">Pembelian</a>
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'obat-racik') active @endif" href="{{ route('transaksi.obat-racik') }}">Obat Racik</a>
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'pembayaran-tempo') active @endif" href="{{ route('transaksi.pembayaran-tempo') }}">Pembayaran Tempo</a>
                        
                    </div>
                </div>
            </li>
            <li class="nav-item @if($url_menu == "jurnal") active @endif">
                <a class="nav-link collapsed @if($url_menu == "jurnal") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages3"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Data</span>
                </a>
                <div id="collapsePages3" class="collapse @if($url_menu == "jurnal") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "jurnal" && $url_submenu == 'jurnal-umum') active @endif" href="{{ route('jurnal.umum') }}">Jurnal Umum</a>
                        <a class="collapse-item @if($url_menu == "jurnal" && $url_submenu == 'jurnal-penjualan') active @endif" href="{{ route('jurnal.penjualan') }}">Data Penjualan</a>
                        <a class="collapse-item @if($url_menu == "jurnal" && $url_submenu == 'jurnal-pembelian') active @endif" href="{{ route('jurnal.pembelian') }}">Data Pembelian</a>
                        <a class="collapse-item @if($url_menu == "jurnal" && $url_submenu == 'jurnal-penyesuaian') active @endif" href="{{ route('jurnal.penyesuaian') }}">Data biaya</a>
                        
                    </div>
                </div>
            </li>
           
            <li class="nav-item @if($url_menu == "laporan") active @endif">
                <a class="nav-link collapsed @if($url_menu == "laporan") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages4"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-book"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapsePages4" class="collapse @if($url_menu == "laporan") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'rugi-laba') active @endif" href="{{ route('laporan.rugiLaba') }}">Laporan Rugi Laba</a>
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'neraca') active @endif" href="{{ route('laporan.neraca') }}">Laporan Neraca</a>
                        {{-- <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'perubahan-modal') active @endif" href="{{ route('laporan.perubahanModal') }}">Laporan Perubahan Modal</a> --}}
                        {{-- <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'hutang') active @endif" href="{{ route('laporan.hutang') }}">Laporan Hutang</a> --}}
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'penjualan') active @endif" href="{{ route('laporan.penjualan') }}">Laporan Penjualan</a>
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'pembelian') active @endif" href="{{ route('laporan.pembelian') }}">Laporan Pembelian</a>
                        {{-- {{-- <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'retur-pembelian') active @endif" href="{{ route('laporan.retur-pembelian') }}">Laporan Retur Penjualan</a> --}}
                        {{-- <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'perubahan-modal') active @endif" href="{{ route('laporan.perubahan-modal') }}">Laporan Perubahan Modal</a> --}}
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'persediaan') active @endif" href="{{ route('laporan.persediaan') }}">Laporan Persediaan</a>
                    </div>
                </div>
            </li>

            @elseif (Session::get('useractive')->level == 'kasir')
            <li class="nav-item @if($url_menu == "master") active @endif">
                <a class="nav-link @if($url_menu == "master") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Master</span>
                </a>
                <div id="collapsePages" class="collapse  @if($url_menu == "master") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'dokter') active @endif" href="{{ route('dokter') }}">Dokter</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'pasien') active @endif" href="{{ route('pasien') }}">Pasien</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'obat-racik') active @endif" href="{{ route('obat-racik') }}">Obat Racik</a>
                    </div>
                </div>
            </li>

            <li class="nav-item @if($url_menu == "transaksi") active @endif">
                <a class="nav-link collapsed @if($url_menu == "transaksi") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages2"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-cart"></i>
                    <span>Transaksi</span>
                </a>
                <div id="collapsePages2" class="collapse @if($url_menu == "transaksi") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'penjualan') active @endif" href="{{ route('transaksi.penjualan') }}">Penjualan</a>                        
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'obat-racik') active @endif" href="{{ route('transaksi.obat-racik') }}">Obat Racik</a>
                    </div>
                </div>
            </li>
             <li class="nav-item @if($url_menu == "jurnal") active @endif">
                <a class="nav-link collapsed @if($url_menu == "jurnal") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages3"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Data</span>
                </a>
                <div id="collapsePages3" class="collapse @if($url_menu == "jurnal") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "jurnal" && $url_submenu == 'jurnal-penjualan') active @endif" href="{{ route('jurnal.penjualan') }}">Data Penjualan</a>
                    </div>
                </div>
            </li>
             <li class="nav-item @if($url_menu == "laporan") active @endif">
                <a class="nav-link collapsed @if($url_menu == "laporan") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages4"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-book"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapsePages4" class="collapse @if($url_menu == "laporan") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'penjualan') active @endif" href="{{ route('laporan.penjualan') }}">Laporan Penjualan</a>
                    </div>
                </div>
            </li>

           

            @elseif (Session::get('useractive')->level == 'pembelian')
            <li class="nav-item @if($url_menu == "master") active @endif">
                <a class="nav-link @if($url_menu == "master") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Master</span>
                </a>
                <div id="collapsePages" class="collapse  @if($url_menu == "master") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'barang') active @endif" href="{{ route('barang') }}">Barang</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'supplier') active @endif" href="{{ route('supplier') }}">Supplier</a>
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'obat-racik') active @endif" href="{{ route('transaksi.obat-racik') }}">Obat Racik</a>
                    </div>
                </div>
            </li>
            <li class="nav-item @if($url_menu == "transaksi") active @endif">
                <a class="nav-link collapsed @if($url_menu == "transaksi") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages2"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-cart"></i>
                    <span>Transaksi</span>
                </a>
                <div id="collapsePages2" class="collapse @if($url_menu == "transaksi") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "transaksi" && $url_submenu == 'pembelian') active @endif" href="{{ route('transaksi.pembelian') }}">Pembelian</a>                        
                    </div>
                </div>
            </li>
            <li class="nav-item @if($url_menu == "jurnal") active @endif">
                <a class="nav-link collapsed @if($url_menu == "jurnal") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages3"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Data</span>
                </a>
                <div id="collapsePages3" class="collapse @if($url_menu == "jurnal") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "jurnal" && $url_submenu == 'jurnal-pembelian') active @endif" href="{{ route('jurnal.pembelian') }}">Data Pembelian</a>  
                    </div>
                </div>
            </li>
             <li class="nav-item @if($url_menu == "laporan") active @endif">
                <a class="nav-link collapsed @if($url_menu == "laporan") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages4"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-book"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapsePages4" class="collapse @if($url_menu == "laporan") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'pembelian') active @endif" href="{{ route('laporan.pembelian') }}">Laporan Pembelian</a>
                        <a class="collapse-item @if($url_menu == "laporan" && $url_submenu == 'persediaan') active @endif" href="{{ route('laporan.persediaan') }}">Laporan Persediaan</a>
                    </div>
                </div>
            </li>

            @elseif (Session::get('useractive')->level == 'apoteker')
            <li class="nav-item @if($url_menu == "master") active @endif">
                <a class="nav-link @if($url_menu == "master") active  @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Master</span>
                </a>
                <div id="collapsePages" class="collapse  @if($url_menu == "master") show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'barang') active @endif" href="{{ route('barang') }}">Barang</a>
                        <a class="collapse-item  @if($url_menu == "master" && $url_submenu == 'obat-racik') active @endif" href="{{ route('obat-racik') }}">Obat Racik</a>
                    </div>
                </div>
            </li>
            @endif

           
            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

           <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" style="right: .15rem">{{ App::count_semua() }}</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header" style="background: white!important; border-color: lightgray!important">
                                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active position-relative notification-link" id="semua-tab" data-toggle="tab" href="#semua" role="tab"
                                                aria-controls="semua" aria-selected="true">
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light"
                                                    style="top: -5px; right: -5px; padding: .45em .6em;">{{ App::count_semua() }}</span>
                                                Semua
                                            </a>
                                        </li>
                                        <li class="nav-item mx-3">
                                            <a class="nav-link position-relative notification-link" id="expired-tab" data-toggle="tab" href="#expired" role="tab"
                                                aria-controls="expired" aria-selected="true">
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light"
                                                    style="top: -5px; right: -5px; padding: .45em .6em;">{{ App::count_barang_expired() }}</span>
                                                Expired
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link position-relative notification-link" id="stock-tab" data-toggle="tab" href="#stock" role="tab"
                                                aria-controls="stock" aria-selected="true">
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light"
                                                    style="top: -5px; right: -5px; padding: .45em .6em;">{{ App::count_barang_stock() }}</span>
                                                Stock Habis
                                            </a>
                                        </li>
                                    </ul>
                                </h6>
                                <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
                                    <div style="height: 350px; overflow-y: auto">
                                        @foreach(App::get_barang_all() as $item)
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" @if(auth()->user()->level == 'superadmin' || auth()->user()->level == 'pembelian') href="{{ route('barang') }}?search={{ $item->nama_barang }}" @endif style="gap: 30px">
                                            <div>
                                                <div class="small text-gray-500">Expired At <span style="color: #817d7d; font-weight: 700; margin-left: .15rem">{{ App::tgl_indo($item->ed) }}</span></div>
                                                <span class="font-weight-bold">{{ $item->nama_barang }}</span>
                                            </div>
                                            <div>
                                                @if($item->ed <= \Carbon\Carbon::today()->addDays(30)->format('Y-m-d'))
                                                <div class="small text-gray-500 d-none">Expired At <span style="color: #817d7d; font-weight: 700; display: none">{{ App::tgl_indo($item->ed) }}</span></div>
                                                @endif
                                                @if($item->stok < 10)
                                                <span class="font-weight-bold" style="font-size: .8rem; white-space: nowrap;">Stock : {{ $item->stok }}</span>
                                                @endif
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500" style="border-top: 1px solid #e3e6f0" href="{{ route('dashboard') }}#expired_barang_table">Show All</a>
                                </div>
                                <div class="tab-pane fade" id="expired" role="tabpanel" aria-labelledby="expired-tab">
                                    <div style="height: 350px; overflow-y: auto">
                                        @foreach(App::get_barang_expired() as $item)
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" @if(auth()->user()->level == 'superadmin' || auth()->user()->level == 'pembelian') href="{{ route('barang') }}?search={{ $item->nama_barang }}" @endif style="gap: 30px">
                                            <div>
                                                <div class="small text-gray-500">Expired At <span style="color: #817d7d; font-weight: 700; margin-left: .15rem">{{ App::tgl_indo($item->ed) }}</span></div>
                                                <span class="font-weight-bold">{{ $item->nama_barang }}</span>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500 d-none">Expired At <span style="color: #817d7d; font-weight: 700; display: none">{{ App::tgl_indo($item->ed) }}</span></div>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500" style="border-top: 1px solid #e3e6f0" href="{{ route('dashboard') }}#expired_barang_table">Show All</a>
                                </div>
                                <div class="tab-pane fade" id="stock" role="tabpanel" aria-labelledby="stock-tab">
                                    <div style="height: 350px; overflow-y: auto">
                                        @foreach(App::get_barang_stock() as $item)
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" @if(auth()->user()->level == 'superadmin' || auth()->user()->level == 'pembelian') href="{{ route('barang') }}?search={{ $item->nama_barang }}" @endif style="gap: 30px">
                                            <div>
                                                <span class="font-weight-bold">{{ $item->nama_barang }}</span>
                                            </div>
                                            <div>
                                                <span class="font-weight-bold" style="font-size: .8rem; white-space: nowrap;">Stock : {{ $item->stok }}</span>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500" style="border-top: 1px solid #e3e6f0" href="{{ route('dashboard') }}#expired_barang_table">Show All</a>
                                </div>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Session::get('useractive')->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('public/sbadmin/img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                {{-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a> --}}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
               @yield('content')
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-5">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto" style="font-size: .9rem">
                        <span>&copy;<a href="#">SAKA SASMITRA</a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
  
    @yield('modal')
    <!-- Modal Load-->
    <div class="modal fade" role="dialog" id="modal_loading" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pt-0" style="background-color: #FAFAF8; border-radius: 6px;">
                <div class="text-center">
                    <img style="border-radius: 4px; height: 140px;" src="{{ asset('public/sbadmin/img/icon/loader.gif') }}" alt="Loading">
                    <h6 style="position: absolute; bottom: 10%; left: 37%;" class="pb-2">Mohon Tunggu..</h6>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('public/sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('public/sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('public\sbadmin\js\sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('public/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset("public/plugins/sweetalert/sweetalert.min.js") }}"></script>
    <script src="{{ asset("public/plugins/select2/dist/js/select2.full.min.js") }}"></script>

    
    <!-- Page level custom scripts -->
    <script src="{{ asset('public/sbadmin/js/demo/datatables-demo.js') }}"></script>
    <script src="{{ asset('public/plugins/simple-datatables/simple-datatables.js') }}"></script>
    @include('scriptjs')
    
    @yield('script')

    <script>
    if(jQuery().select2) {
        $(".select2").select2();
    }

    $('.dropdown-menu .nav-item a').on('click', function(e) {
      e.stopPropagation();
      $(this).tab('show');
    });
    </script>
</body>

</html>