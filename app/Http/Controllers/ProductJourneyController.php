<?php

namespace App\Http\Controllers;

use App\Models\ProductJourney;
use App\Models\Product;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductJourneyController extends Controller
{
    // ১. হ্যান্ডওভার হিস্টোরি দেখা (List)
    public function index(Request $request)
    {
        $journeys = ProductJourney::with(['product', 'seller', 'buyer'])
            ->when($request->search, function($query) use($request) {
                return $query->where("tracking_no", "LIKE", "%" . $request->search . "%")
                             ->orWhere("current_stage", "LIKE", "%" . $request->search . "%");
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.journey.index", compact("journeys"));
    }

    // ২. ট্র্যাশ লিস্ট (সফট ডিলিট হওয়া ডাটা)
    public function trashed()
    {
        $journeys = ProductJourney::onlyTrashed()->orderBy("id", "desc")->paginate(10);
        return view("admin.journey.trashed", compact("journeys"));
    }

    // ৩. নতুন হ্যান্ডওভার পেজ
    public function create()
    {
        $products = Product::all();
        $stakeholders = Stakeholder::all();
        return view("admin.journey.create", compact("products", "stakeholders"));
    }

    // ৪. ডাটা সেভ করার মেথড (পার্সেন্টেজ লজিকসহ)
    public function save(Request $request)
    {
        $buying_price = $request->buying_price ?? 0;
        $extra_cost   = $request->extra_cost ?? 0;
        $profit_percent = $request->profit_percent ?? 0; // আমরা ব্লেড থেকে পার্সেন্টেজ নিচ্ছি

        // লজিক: (কেনা দাম + অতিরিক্ত খরচ) এর ওপর % হিসাব করে প্রফিট বের করা
        $base_amount = $buying_price + $extra_cost;
        $profit_amount = ($base_amount * $profit_percent) / 100;
        $selling_price = $base_amount + $profit_amount;

        $journey = new ProductJourney();
        $journey->tracking_no   = 'TRK-' . strtoupper(Str::random(10));
        $journey->product_id    = $request->product_id;
        $journey->seller_id     = $request->seller_id;
        $journey->buyer_id      = $request->buyer_id;
        $journey->buying_price  = $buying_price;
        $journey->extra_cost    = $extra_cost;
        $journey->profit_margin = $profit_amount; // ডাটাবেসে টাকা হিসেবেই সেভ হবে
        $journey->selling_price = $selling_price;
        $journey->current_stage = $request->current_stage;
        $journey->save();

        return redirect("journey")->with("success", "Product Handover recorded with " . $profit_percent . "% profit!");
    }

    // ৫. এডিট মেথড
    public function edit($id)
    {
        $journey = ProductJourney::find($id);
        $products = Product::all();
        $stakeholders = Stakeholder::all();

        if (!$journey) {
            return redirect("journey")->with("error", "Record not found!");
        }

        return view("admin.journey.edit", compact("journey", "products", "stakeholders"));
    }

    // ৬. আপডেট মেথড (পার্সেন্টেজ লজিকসহ)
    public function update(Request $request, $id)
    {
        $journey = ProductJourney::findOrFail($id);

        $buying_price = $request->buying_price ?? 0;
        $extra_cost   = $request->extra_cost ?? 0;
        $profit_percent = $request->profit_percent ?? 0;

        $base_amount = $buying_price + $extra_cost;
        $profit_amount = ($base_amount * $profit_percent) / 100;
        $selling_price = $base_amount + $profit_amount;

        $journey->update([
            'product_id'    => $request->product_id,
            'seller_id'     => $request->seller_id,
            'buyer_id'      => $request->buyer_id,
            'buying_price'  => $buying_price,
            'extra_cost'    => $extra_cost,
            'profit_margin' => $profit_amount,
            'selling_price' => $selling_price,
            'current_stage' => $request->current_stage,
        ]);

        return redirect("journey")->with("success", "Record updated successfully with " . $profit_percent . "% profit.");
    }

    // ৭. সফট ডিলিট
    public function delete($id)
    {
        $journey = ProductJourney::find($id);
        if ($journey) {
            $journey->delete();
        }
        return redirect("journey")->with("success", "Moved to Trash");
    }

    // ৮. রিস্টোর
    public function restore($id)
    {
        $journey = ProductJourney::withTrashed()->find($id);
        if ($journey) {
            $journey->restore();
        }
        return redirect("journey")->with("success", "Restored successfully");
    }

    // ৯. পার্মানেন্ট ডিলিট
    public function force_delete($id)
    {
        $journey = ProductJourney::withTrashed()->find($id);
        if ($journey) {
            $journey->forceDelete();
        }
        return redirect("journey/trashed")->with("success", "Permanently Deleted");
    }
    public function public_trace($tracking_no)
    {
        // ডাটা নিয়ে আসা
        $history = ProductJourney::with(['product', 'seller', 'buyer'])
                    ->where('tracking_no', $tracking_no)
                    ->orderBy('created_at', 'asc')
                    ->get();

        if ($history->isEmpty()) {
            return redirect('/journey')->with('error', 'Invalid Tracking Number!');
        }

        // আপনার বর্তমান ফাইল লোকেশন অনুযায়ী পাথটি হবে:
        return view("admin.journey.trace", compact("history", "tracking_no"));
    }
}
