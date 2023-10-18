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
            <div id="chart_keuangan"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset("public/sbadmin/vendor/apexcharts/apexcharts.min.js") }}"></script>

<script>

// document.addEventListener("DOMContentLoaded", () => {
//     initialize_chart();
//     fetchDataAndRefreshChart();
// });

// let chart;

// function initialize_chart(){
//     let options = {
//         chart: {
//             height: 350,
//             type: 'area',
//             toolbar: {
//             show: false
//             },
//         },
//         markers: {
//             size: 4
//         },
//         colors: ['#4154f1', '#2eca6a', '#ff771d', '#dc3545'],
//         fill: {
//             type: "gradient",
//             gradient: {
//             shadeIntensity: 1,
//             opacityFrom: 0.3,
//             opacityTo: 0.4,
//             stops: [0, 90, 100]
//             }
//         },
//         dataLabels: {
//             enabled: false
//         },
//         xaxis: {
//             categories: [ 'Jun', 'Jul', 'Aug','Sep','Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar','Apr','May',  ]
//         },
//         yaxis: {
//             title: {
//                 text: 'Jumlah'
//             }
//         },
//         tooltip: {
//             formatter: function() {
//                 return '<b>'+ this.series.name +'</b><br/>'+
//                     this.x +': '+'Rp.'+ this.y ;
//             }
//         },
//         plotOptions: {
//             line: {
//                 dataLabels: {
//                     enabled: true
//                 },
//                 enableMouseTracking: true
//             }
//         },
//         series: [
//         {
//             name: 'Penjualan',
//             data: [0,15]
//         }, {
//             name: 'Pembelian',
//             data: [0, 120]
//         },{
//             name: 'Laba',
//             data: [0, 18]
//         },{
//             name: 'Hutang',
//             data: [0, 7]
//         }]
//     }
    
//     chart = new ApexCharts(document.querySelector("#reportsChart"), options);
//     return chart.render();
// }

$.ajax({
    url: "{{ route('grafik') }}/get-data",
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        // Extract month names and totals from the data
        const months = data.month;
        const months_number = data.month_number;
        const totals113 = data.kode_akun_113;
        const totals411 = data.kode_akun_411;
        const totals211 = data.kode_akun_211;
        const totalsLaba = data.total_laba;

        if(months.length === 1 && totals113.length === 1 && totals411.length === 1 && totals211.length === 1 && totalsLaba.length === 1){      
            const today = new Date();
            const day = today.getDate();
            const monthIndex = today.getMonth();
            const year = today.getFullYear();
            const monthNames = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            
            const formattedDate = day + ' ' + monthNames[monthIndex] + ' ' + year;
            if(months_number == monthIndex + 1 ){
                months[0] = formattedDate;
                months.unshift('1 ' + monthNames[monthIndex] + '' + year);
                totals113.unshift(0);
                totals411.unshift(0);
                totals211.unshift(0);
                totalsLaba.unshift(0);
            } else if (months_number < monthIndex + 1){
                months.push(formattedDate);
                totals113.push(0);
                totals411.push(0);
                totals211.push(0);
                totalsLaba.push(0);
            }
        }

        // Create and render the ApexCharts chart

        const options = {
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
                // categories: [ 'Jun', 'Jul', 'Aug','Sep','Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar','Apr','May',  ]
                categories: months, // Month names in English
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
                data: totals411
            }, {
                name: 'Pembelian',
                data: totals113,
            },{
                name: 'Laba',
                data: totalsLaba
            },{
                name: 'Hutang',
                data: totals211
            }]
        }

        const chart = new ApexCharts(document.querySelector('#chart_keuangan'), options);
        chart.render();
    },
    error: function (error) {
        console.error(error);
    },
});

// function fetchDataAndRefreshChart(){
//     $.ajax({
//         url: '{{ route('grafik') }}/get-data',
//         type: 'GET',
//         dataType: 'json',
//         async: true,
//         cache: false,
//         success: function (data) {
//             // Verify that data is available and is an array
//             if (Array.isArray(data) && data.length > 0) {
//                 const chartData = data;

//                 // Example: Update the chart series data

//                 chart.updateSeries([
//                     {
//                         name: 'Penjualan',
//                         data: chartData.map(item => item.kode_akun_411 || 0),
//                     },
//                     {
//                         name: 'Pembelian',
//                         data: chartData.map(item => item.kode_akun_113 || 0),
//                     },
//                 ]);
//             } else {
//                 console.error("Data received from the server is empty, not an array, or invalid.");
//             }
//         },
//         error: function (error) {
//             // Handle any errors related to the AJAX request
//             console.error("Error in AJAX request:", error);
//         }
//     });
// }
</script>
@endsection


