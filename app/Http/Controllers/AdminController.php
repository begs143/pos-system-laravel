<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
class AdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

    $startThisMonth = $now->copy()->startOfMonth();
    $endThisMonth   = $now->copy()->endOfMonth();

    $startLastMonth = $now->copy()->subMonthNoOverflow()->startOfMonth();
    $endLastMonth   = $now->copy()->subMonthNoOverflow()->endOfMonth();


    $thisMonthSales = (float) DB::table('sales')
        ->whereBetween('sale_date', [$startThisMonth->toDateString(), $endThisMonth->toDateString()])
        ->sum('total_amount');

    $thisMonthPurchase = (float) DB::table('purchase_orders')
        ->whereBetween('po_date', [$startThisMonth->toDateString(), $endThisMonth->toDateString()])
        ->sum('total_amount');


    $lastMonthSales = (float) DB::table('sales')
        ->whereBetween('sale_date', [$startLastMonth->toDateString(), $endLastMonth->toDateString()])
        ->sum('total_amount');

    $lastMonthPurchase = (float) DB::table('purchase_orders')
        ->whereBetween('po_date', [$startLastMonth->toDateString(), $endLastMonth->toDateString()])
        ->sum('total_amount');


    $totalProfit = $thisMonthSales - $thisMonthPurchase;
    $profitLastMonth = $lastMonthSales - $lastMonthPurchase;

    $percentChange = function (float $current, float $previous) {
        if ($previous == 0.0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    };

    $salesChangePercent    = $percentChange($thisMonthSales, $lastMonthSales);
    $purchaseChangePercent = $percentChange($thisMonthPurchase, $lastMonthPurchase);
    $profitChangePercent   = $percentChange($totalProfit, $profitLastMonth);


    $totalReturns = 0;
    $returnsChangePercent = 0;


    $totalExpenseAmount = $thisMonthPurchase;
    $expenseChangePercent = $purchaseChangePercent;


    $suppliersCount = (int) DB::table('suppliers')->count();
    $customersCount = (int) DB::table('users')->where('role', 'user')->count();
    $ordersCount    = (int) DB::table('sales')->count();


    $lowStock = DB::table('stock_balances as sb')
        ->join('products as p', 'p.id', '=', 'sb.product_id')
        ->select('p.id', 'p.name', 'sb.quantity_on_hand')
        ->where('sb.quantity_on_hand', '<=', 10)
        ->orderBy('sb.quantity_on_hand', 'asc')
        ->limit(5)
        ->get();


    $topSelling = DB::table('sale_items as si')
        ->join('products as p', 'p.id', '=', 'si.product_id')
        ->select('p.id', 'p.name', DB::raw('SUM(si.quantity) as units_sold'))
        ->groupBy('p.id', 'p.name')
        ->orderByDesc('units_sold')
        ->limit(5)
        ->get();


    $recentSales = DB::table('sales')
        ->orderByDesc('sale_date')
        ->limit(5)
        ->get();
   $salesChart = DB::table('sales')
    ->selectRaw('MONTH(sale_date) as month, SUM(total_amount) as total')
    ->whereYear('sale_date', now()->year)
    ->groupByRaw('MONTH(sale_date)')
    ->pluck('total', 'month');

$purchaseChart = DB::table('purchase_orders')
    ->selectRaw('MONTH(po_date) as month, SUM(total_amount) as total')
    ->whereYear('po_date', now()->year)
    ->groupByRaw('MONTH(po_date)')
    ->pluck('total', 'month');
    $chartData = [
    'labels' => $months ?? [],
    'sales' => $salesData ?? [],
    'purchase' => $purchaseData ?? [],
];

$customerKey = null;
if (\Illuminate\Support\Facades\Schema::hasColumn('sales', 'customer_id')) $customerKey = 'customer_id';
if (\Illuminate\Support\Facades\Schema::hasColumn('sales', 'user_id')) $customerKey = 'user_id';

$firstTimeCustomers = 0;
$returnCustomers = 0;

if ($customerKey) {
    $customerSalesCounts = DB::table('sales')
        ->select($customerKey, DB::raw('COUNT(*) as cnt'))
        ->whereNotNull($customerKey)
        ->groupBy($customerKey)
        ->pluck('cnt');

    $firstTimeCustomers = $customerSalesCounts->filter(fn($c) => $c == 1)->count();
    $returnCustomers = $customerSalesCounts->filter(fn($c) => $c >= 2)->count();
} else {
    // fallback if no customer id column exists
    $firstTimeCustomers = 0;
    $returnCustomers = 0;
}
$year = now()->year;

// sales grouped by month (sale_date)
$salesByMonth = DB::table('sales')
    ->selectRaw('MONTH(sale_date) as m, SUM(total_amount) as total')
    ->whereYear('sale_date', $year)
    ->groupByRaw('MONTH(sale_date)')
    ->pluck('total', 'm');

// purchase grouped by month (po_date)
$purchaseByMonth = DB::table('purchase_orders')
    ->selectRaw('MONTH(po_date) as m, SUM(total_amount) as total')
    ->whereYear('po_date', $year)
    ->groupByRaw('MONTH(po_date)')
    ->pluck('total', 'm');

$labels = [];
$salesData = [];
$purchaseData = [];

for ($m = 1; $m <= 12; $m++) {
    $labels[] = \Carbon\Carbon::create()->month($m)->format('M');
    $salesData[] = (float) ($salesByMonth[$m] ?? 0);
    $purchaseData[] = (float) ($purchaseByMonth[$m] ?? 0);
}

// Prepare 12 months
$months = [];
$salesData = [];
$purchaseData = [];

for ($i = 1; $i <= 12; $i++) {
    $months[] = date('M', mktime(0, 0, 0, $i, 1));
    $salesData[] = $salesChart[$i] ?? 0;
    $purchaseData[] = $purchaseChart[$i] ?? 0;
}
    return view('admin.dashboard', compact(
        'thisMonthSales',
        'thisMonthPurchase',
        'salesChangePercent',
        'purchaseChangePercent',

        'totalProfit',
        'profitChangePercent',

        'totalReturns',
        'returnsChangePercent',

        'totalExpenseAmount',
        'expenseChangePercent',

        'suppliersCount',
        'customersCount',
        'ordersCount',

        'lowStock',
        'topSelling',
        'recentSales',
        'months',
'salesData',
'purchaseData',
   'chartData',
   'firstTimeCustomers',
'returnCustomers',
'labels',
'salesData',
'purchaseData'

 ));
    }
    public function userRole(Request $request)
    {

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('pages.user.user-role', compact('users'));
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->route('admin.user-role')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('User creation failed: '.$e->getMessage());

            // Show friendly error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the user.');
        }
    }

    public function destroy($id)
    {
        try {

            $user = User::findOrFail($id);
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            // Delete the user
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {

            \Log::error('User deletion failed: '.$e->getMessage());

            // Redirect back with friendly error message
            return redirect()->back()->with('error', 'Something went wrong while deleting the user.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,'.$user->id,
                'username' => 'required|string|max:255|unique:users,username,'.$user->id,
                'role' => 'required|in:admin,user',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('admin.user-role')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('User update failed: '.$e->getMessage());

            // Redirect back with input and friendly error message
            return redirect()->back()->withInput()->with('error', 'Something went wrong while updating the user.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('pages.user.edit', compact('user'));
    }
}
