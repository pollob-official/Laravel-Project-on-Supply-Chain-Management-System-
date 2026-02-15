<?php

namespace App\Http\Controllers;

use App\Models\ProductJourney;
use App\Models\Product;
use App\Models\Stakeholder;
use App\Models\Batch; // ব্যাচ মডেল যোগ করা হয়েছে
use App\Models\ActivityLog;
use App\Services\BatchInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $batches = Batch::with("product")->orderBy('id', 'desc')->get();
        $product_journeys = ProductJourney::all();

        return view("admin.journey.create", compact("products", "stakeholders", "batches", "product_journeys"));
    }

    // ৪. ডাটা সেভ করার মেথড (নতুন কলামসহ)
    public function save(Request $request, BatchInventoryService $inventory)
    {
        $request->validate([
            'batch_id' => 'required|integer',
            'product_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'buyer_id' => 'required|integer',
            'current_stage' => 'required|string',
            'buying_price' => 'required|numeric',
            'profit_percent' => 'required|numeric',
            'quantity_moved' => 'nullable|numeric|min:0',
        ]);

        $buying_price = $request->buying_price ?? 0;
        $extra_cost   = $request->extra_cost ?? 0;
        $profit_percent = $request->profit_percent ?? 0;

        $base_amount = $buying_price + $extra_cost;
        $profit_amount = ($base_amount * $profit_percent) / 100;
        $selling_price = $base_amount + $profit_amount;

        $usedBefore = $inventory->getUsedQuantity((int) $request->batch_id);
        $moveQty = $request->quantity_moved ? (float) $request->quantity_moved : 0;
        $batch = Batch::find($request->batch_id);

        if ($batch && $moveQty > 0 && ($usedBefore + $moveQty) > $batch->total_quantity) {
            return back()->withInput()->withErrors([
                'quantity_moved' => 'This movement exceeds available batch quantity.',
            ]);
        }

        $journey = new ProductJourney();
        $journey->tracking_no   = 'TRK-' . strtoupper(Str::random(10));
        $journey->product_id    = $request->product_id;
        $journey->batch_id      = $request->batch_id;
        $journey->seller_id     = $request->seller_id;
        $journey->buyer_id      = $request->buyer_id;
        $journey->buying_price  = $buying_price;
        $journey->extra_cost    = $extra_cost;
        $journey->profit_margin = $profit_amount;
        $journey->selling_price = $selling_price;
        $journey->current_stage = $request->current_stage;
        $journey->quantity_moved = $request->quantity_moved;
        $journey->quantity_unit  = $request->quantity_unit;
        $journey->loss_quantity  = $request->loss_quantity;
        $journey->location      = $request->location;      // নতুন কলাম
        $journey->quality_status = $request->quality_status ?? 'Good'; // নতুন কলাম
        $journey->remarks       = $request->remarks;       // নতুন কলাম
        $journey->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'journey_created',
            'subject_type' => ProductJourney::class,
            'subject_id' => $journey->id,
            'changes' => json_encode($journey->toArray()),
        ]);

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
    public function update(Request $request, $id, BatchInventoryService $inventory)
    {
        $request->validate([
            'batch_id' => 'required|integer',
            'product_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'buyer_id' => 'required|integer',
            'current_stage' => 'required|string',
            'buying_price' => 'required|numeric',
            'profit_percent' => 'required|numeric',
            'quantity_moved' => 'nullable|numeric|min:0',
        ]);

        $journey = ProductJourney::findOrFail($id);

        $buying_price = $request->buying_price ?? 0;
        $extra_cost   = $request->extra_cost ?? 0;
        $profit_percent = $request->profit_percent ?? 0;

        $base_amount = $buying_price + $extra_cost;
        $profit_amount = ($base_amount * $profit_percent) / 100;
        $selling_price = $base_amount + $profit_amount;

        $usedBefore = $inventory->getUsedQuantity((int) $request->batch_id) - ($journey->quantity_moved ?? 0);
        $moveQty = $request->quantity_moved ? (float) $request->quantity_moved : 0;
        $batch = Batch::find($request->batch_id);

        if ($batch && $moveQty > 0 && ($usedBefore + $moveQty) > $batch->total_quantity) {
            return back()->withInput()->withErrors([
                'quantity_moved' => 'This movement exceeds available batch quantity.',
            ]);
        }

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
            'quantity_moved' => $request->quantity_moved,
            'quantity_unit'  => $request->quantity_unit,
            'loss_quantity'  => $request->loss_quantity,
            'location'       => $request->location,
            'quality_status' => $request->quality_status,
            'remarks'        => $request->remarks,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'journey_updated',
            'subject_type' => ProductJourney::class,
            'subject_id' => $journey->id,
            'changes' => json_encode($journey->getChanges()),
        ]);

        return redirect("admin/journey")->with("success", "Record updated successfully.");
    }

    // ৭. সফট ডিলিট (আপনি যেমনটা চেয়েছিলেন)
public function delete($id) {
    $journey = ProductJourney::findOrFail($id);
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'journey_deleted',
        'subject_type' => ProductJourney::class,
        'subject_id' => $journey->id,
        'changes' => null,
    ]);
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

   public function audit()
    {
        $batchAudits = ProductJourney::with(['batch', 'product'])
            ->select('batch_id', 'product_id',
                DB::raw('SUM(buying_price) as total_buying'),
                DB::raw('SUM(extra_cost) as total_extra_cost'),
                DB::raw('SUM(selling_price) as total_revenue'),
                DB::raw('SUM(profit_margin) as total_profit'),
                DB::raw('MAX(created_at) as last_update')
            )
            ->groupBy('batch_id', 'product_id')
            ->latest('last_update')
            ->paginate(15);

        return view('admin.journey.audit', compact('batchAudits'));
    }

   public function priceAlerts(Request $request)
    {
        // ইউজার ইনপুট দিলে সেটা নিবে, না দিলে ডিফল্ট ২৫
        $marginLimit = $request->has('margin') ? $request->margin : 25;

        $alerts = ProductJourney::with(['batch', 'product', 'seller', 'buyer'])
            ->whereRaw('(profit_margin / (buying_price + extra_cost)) * 100 > ?', [$marginLimit])
            ->latest()
            ->paginate(15);

        return view('admin.journey.alerts', compact('alerts', 'marginLimit'));
    }

    public function priceFluctuation(Request $request)
    {
        $productId = $request->product_id;

        $query = ProductJourney::select(
                'product_id',
                DB::raw('DATE(created_at) as date'),
                DB::raw('AVG(selling_price) as avg_price')
            )
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->groupBy('product_id', 'date')
            ->orderBy('date', 'asc');

        $data = $query->get();
        $products = Product::orderBy('name')->get();

        $chartSeries = [];
        $labels = [];

        if ($data->count()) {
            $labels = $data->pluck('date')->map(function ($d) {
                return \Carbon\Carbon::parse($d)->format('M d');
            })->toArray();

            $seriesData = $data->pluck('avg_price')->map(function ($v) {
                return round($v, 2);
            })->toArray();

            $chartSeries = [
                [
                    'name' => 'Avg Selling Price',
                    'data' => $seriesData,
                ],
            ];
        }

        return view('admin.journey.fluctuation', [
            'products' => $products,
            'selectedProductId' => $productId,
            'chartLabels' => $labels,
            'chartSeries' => $chartSeries,
        ]);
    }

    public function supplyChainMap(Request $request)
    {
        $search = $request->search;
        $journeys = [];

        if ($search) {
            $journeys = ProductJourney::with(['batch', 'product', 'seller', 'buyer'])
                ->where('tracking_no', $search)
                ->orWhereHas('batch', function($q) use ($search) {
                    $q->where('batch_no', $search);
                })
                ->orderBy('created_at', 'asc') // শুরুর ধাপ আগে দেখাবে
                ->get();
        }

        return view('admin.journey.map', compact('journeys', 'search'));
    }
}
