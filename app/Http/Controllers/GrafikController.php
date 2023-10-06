<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use Illuminate\Support\Carbon;
class GrafikController extends Controller
{
    public function index(){
        return view('grafik.index');
    }

    public function get_data(){
        // Get the current date using Carbon
        $currentDate = Carbon::now();

        // Get the current month as a number (1 for January, 2 for February, etc.)
        $currentMonth = $currentDate->month;

        $data = MasterTransaksi::whereIn('kode_akun', [411, 113])->select(['kredit', 'debt', 'potongan', 'created_at', 'kode_akun'])->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'ASC')->get();
        return $data;

        // Group the data by date and kode_akun
        $groupedData = collect($data)->groupBy(function ($item) {
            return substr($item['created_at'], 0, 10); // Extract the date part (e.g., '2023-10-05')
        });

        // Create a new collection to hold the final result
        $result = collect();

        $result->push([
            'date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'kode_akun_113' => 0,
            'kode_akun_411' => 0,
        ]);

        // Loop through the grouped data
        $groupedData->each(function ($transactions, $date) use ($result) {
            // Initialize sums for kode_akun 113 and 411
            $sumKodeAkun113 = 0;
            $sumKodeAkun411 = 0;

            // Calculate the sums for each kode_akun
            foreach ($transactions as $transaction) {
                if ($transaction['kode_akun'] == 113) {
                    $sumKodeAkun113 += ($transaction['debt'] ?? 0) - ($transaction['potongan'] ?? 0);
                } elseif ($transaction['kode_akun'] == 411) {
                    $sumKodeAkun411 += $transaction['kredit'] ?? 0 - ($transaction['potongan'] ?? 0);
                }
            }

            // Add the result for the date to the final collection
            $result->push([
                'date' => $date,
                'kode_akun_113' => $sumKodeAkun113,
                'kode_akun_411' => $sumKodeAkun411,
            ]);
        });

        return $result;

        return response()->json($result);

    }
}
