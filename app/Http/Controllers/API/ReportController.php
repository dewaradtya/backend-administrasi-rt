<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function monthlySummary()
    {
        $totalPenghuni = Resident::count();
        $pemasukan = Payment::select(
            DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
            DB::raw("SUM(total_amount) as total_pemasukan")
        )
            ->groupBy('month');

        $pengeluaran = Expense::select(
            DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
            DB::raw("SUM(amount) as total_pengeluaran")
        )
            ->groupBy('month');

        $result = DB::table(DB::raw("({$pemasukan->toSql()}) as p"))
            ->mergeBindings($pemasukan->getQuery())
            ->leftJoinSub($pengeluaran, 'e', 'p.month', '=', 'e.month')
            ->select(
                'p.month',
                'p.total_pemasukan',
                DB::raw("COALESCE(e.total_pengeluaran, 0) as total_pengeluaran"),
                DB::raw("p.total_pemasukan - COALESCE(e.total_pengeluaran, 0) as saldo")
            )
            ->orderBy('p.month')
            ->get();

        $totalPemasukan = $result->sum('total_pemasukan');
        $totalPengeluaran = $result->sum('total_pengeluaran');
        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'total_penghuni' => $totalPenghuni,
            'monthly' => $result,
            'total' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'saldo' => $totalSaldo,
            ]
        ]);
    }
}
