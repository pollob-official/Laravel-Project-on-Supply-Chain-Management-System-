<?php

namespace App\Http\Controllers;

use App\Models\ProductJourney;
use App\Models\Product;
use App\Models\Stakeholder;
use App\Models\Batch; // ব্যাচ মডেল যোগ করা হয়েছে
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductJourneyController extends Controller
{
    // ১. হ্যান্ডওভার হিস্টোরি দেখা (নতুন কলামসহ)
    public function index(Request $request)
    {
        // batch রিলেশনসহ ডাটা নিয়ে আসা
        $journeys = ProductJourney::with(['product', 'seller', 'buyer', 'batch'])
            ->when($request->search, function($query) use($request) {
                return $query->where("tracking_no", "LIKE", "%" . $request->search . "%")
                             ->orWhere("current_stage", "LIKE", "%" . $request->search . "%")
                             ->orWhere("location", "LIKE", "%" . $request->search . "%");
            })
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("admin.journey.index", compact("journeys"));
    }

    // ২. ট্র্যাশ লিস্ট
    public function trashed()
    {
        $journeys = ProductJourney::onlyTrashed()->with(['product', 'batch'])->orderBy("id", "desc")->paginate(10);
        return view("admin.journey.trashed", compact("journeys"));
    }

    // ৩. নতুন হ্যান্ডওভার পেজ (ব্যাচ লিস্ট পাঠানো হয়েছে)
   public function create()
    {
        $products = Product::all();
        $stakeholders = Stakeholder::all();

        // শুধুমাত্র Active ব্যাচগুলো নিচ্ছি (যদি status কলাম থাকে)
        // অথবা সরাসরি সব ব্যাচ নিচ্ছি আপনার আগের লজিক অনুযায়ী
        $batches = Batch::orderBy('id', 'desc')->get();

        return view("admin.journey.create", compact("products", "stakeholders", "batches"));
    }

    // ৪. ডাটা সেভ করার মেথড (নতুন কলামসহ)
    public function save(Request $request)
    {
        $buying_price = $request->buying_price ?? 0;
        $extra_cost   = $request->extra_cost ?? 0;
        $profit_percent = $request->profit_percent ?? 0;

        $base_amount = $buying_price + $extra_cost;
        $profit_amount = ($base_amount * $profit_percent) / 100;
        $selling_price = $base_amount + $profit_amount;

        $journey = new ProductJourney();
        $journey->tracking_no   = 'TRK-' . strtoupper(Str::random(10));
        $journey->product_id    = $request->product_id;
        $journey->batch_id      = $request->batch_id; // নতুন কলাম
        $journey->seller_id     = $request->seller_id;
        $journey->buyer_id      = $request->buyer_id;
        $journey->buying_price  = $buying_price;
        $journey->extra_cost    = $extra_cost;
        $journey->profit_margin = $profit_amount;
        $journey->selling_price = $selling_price;
        $journey->current_stage = $request->current_stage;
        $journey->location      = $request->location;      // নতুন কলাম
        $journey->quality_status = $request->quality_status ?? 'Good'; // নতুন কলাম
        $journey->remarks       = $request->remarks;       // নতুন কলাম
        $journey->save();

        return redirect("admin/journey")->with("success", "Product Handover recorded successfully!");
    }

    // ৫. এডিট মেথড
    public function edit($id)
    {
        $journey = ProductJourney::find($id);
        $products = Product::all();
        $stakeholders = Stakeholder::all();
        $batches = Batch::all();

        if (!$journey) {
            return redirect("admin/journey")->with("error", "Record not found!");
        }

        return view("admin.journey.edit", compact("journey", "products", "stakeholders", "batches"));
    }

    // ৬. আপডেট মেথড
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
            'product_id'     => $request->product_id,
            'batch_id'       => $request->batch_id,
            'seller_id'      => $request->seller_id,
            'buyer_id'       => $request->buyer_id,
            'buying_price'   => $buying_price,
            'extra_cost'     => $extra_cost,
            'profit_margin'  => $profit_amount,
            'selling_price'  => $selling_price,
            'current_stage'  => $request->current_stage,
            'location'       => $request->location,
            'quality_status' => $request->quality_status,
            'remarks'        => $request->remarks,
        ]);

        return redirect("admin/journey")->with("success", "Record updated successfully.");
    }

    // ৭. সফট ডিলিট (আপনি যেমনটা চেয়েছিলেন)
public function delete($id) {
    $journey = ProductJourney::findOrFail($id);
    $journey->delete(); // এটি ডাটাবেজ থেকে মুছবে না, শুধু deleted_at কলামে সময় বসাবে
    return redirect()->back()->with('success', 'Record moved to trash successfully.');
}

    // ৮. রিস্টোর
    public function restore($id)
    {
        $journey = ProductJourney::withTrashed()->find($id);
        if ($journey) {
            $journey->restore();
        }
        return redirect("admin/journey/trashed")->with("success", "Restored successfully");
    }

    // ৯. পার্মানেন্ট ডিলিট
    public function force_delete($id)
    {
        $journey = ProductJourney::withTrashed()->find($id);
        if ($journey) {
            $journey->forceDelete();
        }
        return redirect("admin/journey/trashed")->with("success", "Permanently Deleted");
    }

    // ১০. পাবলিক ট্রেস (ব্যাচ ভিত্তিক পুরো ইতিহাস দেখাবে)
    public function public_trace($tracking_no)
    {
        // প্রথমে এই ট্র্যাকিং নাম্বার দিয়ে ওই রেকর্ডটি খুঁজে বের করি
        $current_record = ProductJourney::where('tracking_no', $tracking_no)->first();

        if (!$current_record) {
            return redirect('/')->with('error', 'Invalid Tracking Number!');
        }

        // এবার ওই একই ব্যাচের (batch_id) শুরু থেকে শেষ পর্যন্ত সব জার্নি দেখাবো
        $history = ProductJourney::with(['product', 'seller', 'buyer', 'batch'])
                    ->where('batch_id', $current_record->batch_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        return view("admin.journey.trace", compact("history", "tracking_no", "current_record"));
    }
}
