<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;use Illuminate\Http\Request;

class DashboardController extends Controller
{
 public function index()
    {
 $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');      // /admin/dashboard
    }

    // cashier / inventory / other → user dashboard
    return redirect()->route('user.dashboard');           // /user/dashboard




    // /user/dashboard
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $sumFrom = function (string $table, array $preferredColumns, $from = null, $to = null) {
            if (!Schema::hasTable($table)) return 0;

            $col = null;
            foreach ($preferredColumns as $c) {
                if (Schema::hasColumn($table, $c)) { $col = $c; break; }
            }
            if (!$col) return 0;

            $q = DB::table($table);
            if ($from && $to && Schema::hasColumn($table, 'created_at')) {
                $q->whereBetween('created_at', [$from, $to]);
            }

            return (float) $q->sum($col);
        };

        $countFrom = function (string $table) {
            if (!Schema::hasTable($table)) return 0;
            return (int) DB::table($table)->count();
        };


        $totalSales = $sumFrom('sales', ['grand_total', 'total', 'total_amount', 'amount', 'net_total'], $startOfMonth, $endOfMonth);


        $totalPurchase = $sumFrom('purchase_orders', ['grand_total', 'total', 'total_amount', 'amount', 'net_total'], $startOfMonth, $endOfMonth);


        $totalExpenses = 0;
        if (Schema::hasTable('stock_movements')) {
            $qtyCol = Schema::hasColumn('stock_movements', 'quantity') ? 'quantity' : (Schema::hasColumn('stock_movements', 'qty') ? 'qty' : null);
            $typeCol = Schema::hasColumn('stock_movements', 'type') ? 'type' : null;

            if ($qtyCol && $typeCol) {
                $totalExpenses = (float) DB::table('stock_movements')
                    ->where($typeCol, 'out')
                    ->when(Schema::hasColumn('stock_movements', 'created_at'), fn($q) => $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]))
                    ->sum($qtyCol);
            }
        }

        // INVOICE DUE (if sales has due/balance column)
        $invoiceDue = 0;
        if (Schema::hasTable('sales')) {
            $dueCol = null;
            foreach (['due', 'due_amount', 'balance', 'remaining_balance'] as $c) {
                if (Schema::hasColumn('sales', $c)) { $dueCol = $c; break; }
            }
            if ($dueCol) {
                $invoiceDue = (float) DB::table('sales')
                    ->when(Schema::hasColumn('sales', 'created_at'), fn($q) => $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]))
                    ->sum($dueCol);
            }
        }

        // ---------- Top selling products (from sale_items) ----------
        $topSelling = collect();
        if (Schema::hasTable('sale_items')) {
            $productIdCol = Schema::hasColumn('sale_items', 'product_id') ? 'product_id' : null;
            $qtyCol       = Schema::hasColumn('sale_items', 'quantity') ? 'quantity' : (Schema::hasColumn('sale_items', 'qty') ? 'qty' : null);

            if ($productIdCol && $qtyCol) {
                $topSelling = DB::table('sale_items as si')
                    ->select('p.id', 'p.name', DB::raw("SUM(si.$qtyCol) as units_sold"))
                    ->join('products as p', 'p.id', '=', "si.$productIdCol")
                    ->groupBy('p.id', 'p.name')
                    ->orderByDesc('units_sold')
                    ->limit(5)
                    ->get();
            }
        }

        // ---------- Low stock (from stock_balances or products) ----------
        $lowStock = collect();

        // Prefer stock_balances if it has qty
        if (Schema::hasTable('stock_balances')) {
            $productIdCol = Schema::hasColumn('stock_balances', 'product_id') ? 'product_id' : null;
            $qtyCol       = Schema::hasColumn('stock_balances', 'quantity') ? 'quantity' : (Schema::hasColumn('stock_balances', 'qty') ? 'qty' : null);

            if ($productIdCol && $qtyCol) {
                // Use products.alert_quantity (if exists) as threshold
                $alertCol = Schema::hasColumn('products', 'alert_quantity') ? 'alert_quantity'
                         : (Schema::hasColumn('products', 'reorder_level') ? 'reorder_level' : null);

                $q = DB::table('stock_balances as sb')
                    ->select('p.id', 'p.name', DB::raw("sb.$qtyCol as quantity"))
                    ->join('products as p', 'p.id', '=', "sb.$productIdCol");

                if ($alertCol) {
                    $q->whereRaw("sb.$qtyCol <= p.$alertCol");
                } else {
                    $q->whereRaw("sb.$qtyCol <= 10"); // fallback threshold
                }

                $lowStock = $q->orderBy('quantity')->limit(5)->get();
            }
        }

        // Fallback to products table if stock_balances isn’t usable
        if ($lowStock->isEmpty() && Schema::hasTable('products')) {
            $qtyCol   = Schema::hasColumn('products', 'quantity') ? 'quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
            $alertCol = Schema::hasColumn('products', 'alert_quantity') ? 'alert_quantity'
                     : (Schema::hasColumn('products', 'reorder_level') ? 'reorder_level' : null);

            if ($qtyCol) {
                $q = DB::table('products')->select('id', 'name', DB::raw("$qtyCol as quantity"));
                if ($alertCol) $q->whereColumn($qtyCol, '<=', $alertCol);
                else $q->where($qtyCol, '<=', 10);

                $lowStock = $q->orderBy('quantity')->limit(5)->get();
            }
        }

        $recentSales = collect();
        if (Schema::hasTable('sales')) {
            $recentSales = DB::table('sales')
                ->orderByDesc(Schema::hasColumn('sales','created_at') ? 'created_at' : 'id')
                ->limit(5)
                ->get();
        }


        $suppliersCount = $countFrom('suppliers');
        $customersCount = $countFrom('users'); // If you have a customers table later, change this.
        $ordersCount    = $countFrom('sales');

        return view('dashboard', compact(
            'totalSales',
            'totalPurchase',
            'totalExpenses',
            'invoiceDue',
            'topSelling',
            'lowStock',
            'recentSales',
            'suppliersCount',
            'customersCount',
            'ordersCount'



        ));
    }
}
