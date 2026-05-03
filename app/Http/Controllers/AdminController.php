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

    // --- DATE RANGES (THIS MONTH / LAST MONTH) -----------------
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




    $totalExpenseAmount = $thisMonthPurchase;


    $totalSales       = $thisMonthSales;
    $totalPurchase    = $thisMonthPurchase;
    $totalexpense   =$totalExpenseAmount;

    $invoiceDue    = 0;

    $totalProfit      = $thisMonthSales - $thisMonthPurchase;
    $profitLastMonth  = $lastMonthSales - $lastMonthPurchase;





    // --- PERCENT CHANGE HELPER ---------------------------------
    $percentChange = function (float $current, float $previous) {
        if ($previous == 0.0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    };

    $salesChangePercent    = $percentChange($thisMonthSales, $lastMonthSales);
    $purchaseChangePercent = $percentChange($thisMonthPurchase, $lastMonthPurchase);
    $profitChangePercent   = $percentChange($totalProfit, $profitLastMonth);

    // --- RETURNS & EXPENSES (PLACEHOLDER / SIMPLE LOGIC) -------
    $totalReturns          = 0;
    $returnsChangePercent  = 0;

    $totalExpenseAmount    = $thisMonthPurchase;
    $expenseChangePercent  = $purchaseChangePercent;

    // --- BASIC COUNTS ------------------------------------------
    $suppliersCount = (int) DB::table('suppliers')->count();

    // Use customers table instead of users role
    $customersCount = (int) DB::table('users')->count();

    $ordersCount    = (int) DB::table('sales')->count();

    // --- LOW STOCK PRODUCTS ------------------------------------
    $lowStock = DB::table('stock_balances as sb')
        ->join('products as p', 'p.id', '=', 'sb.product_id')
        ->select('p.id', 'p.name', 'sb.quantity_on_hand')
        ->where('sb.quantity_on_hand', '<=', 10)
        ->orderBy('sb.quantity_on_hand', 'asc')
        ->limit(5)
        ->get();

    // --- TOP SELLING PRODUCTS ----------------------------------
    $topSelling = DB::table('sale_items as si')
        ->join('products as p', 'p.id', '=', 'si.product_id')
        ->select('p.id', 'p.name', DB::raw('SUM(si.quantity) as units_sold'))
        ->groupBy('p.id', 'p.name')
        ->orderByDesc('units_sold')
        ->limit(5)
        ->get();

    // --- RECENT SALES ------------------------------------------
    $recentSales = DB::table('sales')
        ->orderByDesc('sale_date')
        ->limit(5)
        ->get();

    // --- SALES vs PURCHASE CHART DATA (PER MONTH, CURRENT YEAR) -
    $year = now()->year;

    $salesByMonth = DB::table('sales')
        ->selectRaw('MONTH(sale_date) as m, SUM(total_amount) as total')
        ->whereYear('sale_date', $year)
        ->groupByRaw('MONTH(sale_date)')
        ->pluck('total', 'm');

    $purchaseByMonth = DB::table('purchase_orders')
        ->selectRaw('MONTH(po_date) as m, SUM(total_amount) as total')
        ->whereYear('po_date', $year)
        ->groupByRaw('MONTH(po_date)')
        ->pluck('total', 'm');

    // arrays used in Blade & JS for chart
    $months       = [];
    $salesData    = [];
    $purchaseData = [];

    for ($m = 1; $m <= 12; $m++) {
        $months[]       = Carbon::create()->month($m)->format('M');
        $salesData[]    = (float) ($salesByMonth[$m] ?? 0);
        $purchaseData[] = (float) ($purchaseByMonth[$m] ?? 0);
    }

    // Optional: packed array if you want
    $chartData = [
        'labels'   => $months,
        'sales'    => $salesData,
        'purchase' => $purchaseData,
    ];

    // --- CUSTOMER OVERVIEW (FIRST TIME vs RETURN) --------------
    // Here we use customers.created_at (last 6 months)
    $start6Months = Carbon::now()->subMonths(6)->startOfDay();

    $firstTimeCustomers = (int) DB::table('users')
        ->where('created_at', '>=', $start6Months)
        ->count();

    $returnCustomers = (int) DB::table('users')
        ->where('created_at', '<', $start6Months)
        ->count();


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

    'totalSales',
    'totalPurchase',
    'totalexpense',
    'invoiceDue',
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
            'role' => 'required|in:admin,inventory,cashier',
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
       $user = User::findOrFail($id);

    // Validation
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'nullable|email|unique:users,email,' . $user->id,
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'role'     => 'required|in:admin,inventory,cashier',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // Update user data
    $user->name     = $validated['name'];
    $user->email    = $validated['email'] ?? null;
    $user->username = $validated['username'];
    $user->role     = $validated['role'];

    if (!empty($validated['password'])) {
        $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
    }

    $user->save();

    return redirect()
        ->route('admin.user-role')
        ->with('success', 'User updated successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('pages.user.edit', compact('user'));
    }
}
