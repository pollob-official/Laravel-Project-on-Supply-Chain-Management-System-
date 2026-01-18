<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('admin.purchase.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required|numeric|min:1',
            'price' => 'required',
            'purchase_date' => 'required|date',
        ]);

        // ডাটাবেস ট্রানজেকশন ব্যবহার করছি যাতে ভুল হলে ডাটা সেভ না হয়
        DB::transaction(function () use ($request) {
            // ১. পারচেজ রেকর্ড সেভ
            Purchase::create($request->all());

            // ২. প্রোডাক্টের স্টক আপডেট (আগের স্টকের সাথে নতুন যোগ হবে)
            $product = Product::find($request->product_id);
            $product->increment('stock', $request->qty);
        });

        return redirect('admin/product')->with('success', 'Stock Updated Successfully!');
    }
}
