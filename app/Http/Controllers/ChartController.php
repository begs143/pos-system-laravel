<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function salesPurchase(Request $request)
    {
        $range = $request->get('range', 'year');

        if ($range === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end   = Carbon::now()->endOfWeek();

            $sales = DB::table('sales')
                ->selectRaw('DATE(sale_date) as label, SUM(total_amount) as total')
                ->whereBetween('sale_date', [$start, $end])
                ->groupByRaw('DATE(sale_date)')
                ->pluck('total', 'label');

            $purchase = DB::table('purchase_orders')
                ->selectRaw('DATE(po_date) as label, SUM(total_amount) as total')
                ->whereBetween('po_date', [$start, $end])
                ->groupByRaw('DATE(po_date)')
                ->pluck('total', 'label');

            $labels = [];
            $salesData = [];
            $purchaseData = [];

            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $key = $d->toDateString();
                $labels[] = $d->format('D'); // Mon, Tue...
                $salesData[] = (float) ($sales[$key] ?? 0);
                $purchaseData[] = (float) ($purchase[$key] ?? 0);
            }

            return response()->json(compact('labels', 'salesData', 'purchaseData'));
        }

        if ($range === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();

            $sales = DB::table('sales')
                ->selectRaw('DAY(sale_date) as day, SUM(total_amount) as total')
                ->whereBetween('sale_date', [$start, $end])
                ->groupByRaw('DAY(sale_date)')
                ->pluck('total', 'day');

            $purchase = DB::table('purchase_orders')
                ->selectRaw('DAY(po_date) as day, SUM(total_amount) as total')
                ->whereBetween('po_date', [$start, $end])
                ->groupByRaw('DAY(po_date)')
                ->pluck('total', 'day');

            $daysInMonth = $start->daysInMonth;

            $labels = [];
            $salesData = [];
            $purchaseData = [];

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = (string)$i;
                $salesData[] = (float) ($sales[$i] ?? 0);
                $purchaseData[] = (float) ($purchase[$i] ?? 0);
            }

            return response()->json(compact('labels', 'salesData', 'purchaseData'));
        }

        // year (default): group by month
        $year = Carbon::now()->year;

        $sales = DB::table('sales')
            ->selectRaw('MONTH(sale_date) as m, SUM(total_amount) as total')
            ->whereYear('sale_date', $year)
            ->groupByRaw('MONTH(sale_date)')
            ->pluck('total', 'm');

        $purchase = DB::table('purchase_orders')
            ->selectRaw('MONTH(po_date) as m, SUM(total_amount) as total')
            ->whereYear('po_date', $year)
            ->groupByRaw('MONTH(po_date)')
            ->pluck('total', 'm');

        $labels = [];
        $salesData = [];
        $purchaseData = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create()->month($m)->format('M');
            $salesData[] = (float) ($sales[$m] ?? 0);
            $purchaseData[] = (float) ($purchase[$m] ?? 0);
        }

        return response()->json(compact('labels', 'salesData', 'purchaseData'));
    }
}
