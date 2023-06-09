@extends('layouts.layouts')
@section('css')
<style>
    .modal-body{
        padding: 1.4em;
    }

    text{
        font-weight: 700!important;
        font-size: 12px;
    }

    tspan{
        font-weight: 500;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="pagetitle">
        <h1 class="h3 mb-2">Grafik</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Grafik</a></li>
                <li class="breadcrumb-item active"><a href="#">Grafik Keuangan</a></li>
                
            </ol>
        </nav>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4" style="padding: 15px 6px;">
        <div class="card-header py-3" style="background: white; border-color: #fff">
            <h6 class="m-0 card-title">Grafik Keuangan Apotek</h6>
        </div>
        <div class="card-body">
            <div id="reportsChart"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener("DOMContentLoaded", () => {
    new ApexCharts(document.querySelector("#reportsChart"), {
    chart: {
        height: 350,
        type: 'area',
        toolbar: {
        show: false
        },
    },
    markers: {
        size: 4
    },
    colors: ['#4154f1', '#2eca6a', '#ff771d', '#dc3545'],
    fill: {
        type: "gradient",
        gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.3,
        opacityTo: 0.4,
        stops: [0, 90, 100]
        }
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {
        categories: [ 'Jun', 'Jul', 'Aug','Sep','Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar','Apr','May',  ]
    },
    yaxis: {
        title: {
            text: 'Jumlah'
        }
    },
    tooltip: {
        formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+
                this.x +': '+'Rp.'+ this.y ;
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [
    {
        name: 'Penjualan',
        data: [0,15]
    }, {
        name: 'Pembelian',
        data: [0, 120]
    },{
        name: 'Laba',
        data: [0, 18]
    },{
        name: 'Hutang',
        data: [0, 7]
    }]
    }).render();
});
</script>
@endsection


