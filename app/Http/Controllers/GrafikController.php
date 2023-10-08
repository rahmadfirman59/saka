<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class GrafikController extends Controller
{
    public function index(){
        return view('grafik.index');
    }

    public function get_data(){

        $data = DB::table('transaksi')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN kode_akun = 113 THEN debt - IFNULL(potongan, 0) ELSE 0 END) as total_113'),
                DB::raw('SUM(CASE WHEN kode_akun = 411 THEN kredit - IFNULL(potongan, 0) ELSE 0 END) as total_411'),
                DB::raw('SUM(CASE WHEN kode_akun = 211 THEN kredit - IFNULL(potongan, 0) ELSE 0 END) as total_211'),
                DB::raw('SUM(CASE WHEN kode_akun = 411 THEN kredit - IFNULL(potongan, 0) ELSE 0 END - CASE WHEN kode_akun = 119 THEN debt - IFNULL(potongan, 0) ELSE 0 END) as total_laba')
            )
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

                $formattedData = [
            'month' => [],
            'kode_akun_113' => [],
            'kode_akun_411' => [],
            'kode_akun_211' => [],
            'total_laba' => [],
        ];

        foreach ($data as $item) {
            $formattedData['month'][] = strftime('%B %Y', strtotime($item->month)); // Format month name in English
            $formattedData['kode_akun_113'][] = $item->total_113;
            $formattedData['kode_akun_411'][] = $item->total_411;
            $formattedData['kode_akun_211'][] = $item->total_211;
            $formattedData['total_laba'][] = $item->total_laba;
        }

        return response()->json($formattedData);

        //?? Testing Regroup
        // // Group the data by date and kode_akun
        // $groupedData = collect($data)->groupBy(function ($item) {
        //     return substr($item['created_at'], 0, 10); // Extract the date part (e.g., '2023-10-05')
        // });

        // // Create a new collection to hold the final result
        // $result = collect();

        // // Loop through the grouped data
        // $groupedData->each(function ($transactions, $date) use ($result) {
        //     // Initialize sums for kode_akun 113 and 411
        //     $sumKodeAkun113 = 0;
        //     $sumKodeAkun411 = 0;

        //     // Calculate the sums for each kode_akun
        //     foreach ($transactions as $transaction) {
        //         if ($transaction['kode_akun'] == 113) {
        //             $sumKodeAkun113 += ($transaction['debt'] ?? 0) - ($transaction['potongan'] ?? 0);
        //         } elseif ($transaction['kode_akun'] == 411) {
        //             $sumKodeAkun411 += $transaction['kredit'] ?? 0 - ($transaction['potongan'] ?? 0);
        //         }
        //     }

        //     // Add the result for the date to the final collection
        //     $result->push([
        //         'date' => $date,
        //         'kode_akun_113' => $sumKodeAkun113,
        //         'kode_akun_411' => $sumKodeAkun411,
        //     ]);
        // });

        // return response()->json($result);

    }
}
