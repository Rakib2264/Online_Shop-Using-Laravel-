<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function index()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 1)->count();

        $totalrevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        // This Month Revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentData = Carbon::now()->format('Y-m-d');
        $revenuethismonth = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $startOfMonth)
            ->where('created_at', '<=', $currentData)
            ->sum('grand_total');

        // Last month revinue

        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');
        $revenueLastmonth = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $lastMonthStartDate)
            ->where('created_at', '<=', $lastMonthEndDate)
            ->sum('grand_total');

        // last 30 days sale
        $lastThirtyDaysStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $revenueLasthirtyDaty = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $lastThirtyDaysStartDate)
            ->where('created_at', '<=', $currentData)
            ->sum('grand_total');

        return view('admin.dashboard', compact('totalOrders', 'totalProducts', 'totalCustomers', 'totalrevenue', 'revenuethismonth', 'revenueLastmonth', 'revenueLasthirtyDaty', 'lastMonthName'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
