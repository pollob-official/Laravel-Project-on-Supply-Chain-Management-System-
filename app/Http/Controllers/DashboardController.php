<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\ProductJourney;
use App\Models\Stakeholder; // আপনার স্টেকহোল্ডার মডেলের নাম অনুযায়ী
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // ১. উপরের ৪টি কার্ডের জন্য সামারি ডাটা
        $total_batches = Batch::count();
        $total_stakeholders = Stakeholder::count();
        $total_revenue = ProductJourney::sum('selling_price');
        $total_profit = ProductJourney::sum('profit_margin');

        // ২. চার্টের জন্য গত ৭ দিনের লাভের ডাটা
        // আপনার টেবিলের নাম lav_product_journeys হলেও মডেল ProductJourney ব্যবহার করলে লারাভেল অটো খুঁজে নিবে
        $chart_data = ProductJourney::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(profit_margin) as profit')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // ৩. রিসেন্ট কিছু হ্যান্ডওভার (সবার নিচে দেখানোর জন্য - ঐচ্ছিক)
        $recent_journeys = ProductJourney::with(['batch', 'product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_batches',
            'total_stakeholders',
            'total_revenue',
            'total_profit',
            'chart_data',
            'recent_journeys'
        ));
    }
}
