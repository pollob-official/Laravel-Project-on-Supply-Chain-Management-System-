<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class BatchController extends Controller
{
    /**
     * সকল সক্রিয় ব্যাচ প্রদর্শন
     */
    public function index(Request $request)
    {
        $batches = Batch::with(['product', 'farmer'])
            ->when($request->search, function ($query) use ($request) {
                return $query->where("batch_no", "LIKE", "%" . $request->search . "%")
                             ->orWhere("seed_brand", "LIKE", "%" . $request->search . "%");
            })
            ->latest()
            ->paginate(10);

        return view("admin.batches.index", compact("batches"));
    }

    /**
     * ব্যাচ তৈরির ফর্ম
     */
    public function create()
    {
        $products = Product::all();
        $farmers = Stakeholder::where('role', 'farmer')->get();
        return view("admin.batches.create", compact("products", "farmers"));
    }

    /**
     * নতুন ব্যাচ সেভ এবং কিউআর কোড জেনারেশন
     */
    public function store(Request $request)
{
    // ১. অ্যাডভান্স ভ্যালিডেশন
    $request->validate([
        'product_id' => 'required',
        'total_quantity' => 'required|numeric|min:1',
        'manufacturing_date' => 'required|date',
    ]);

    // ২. স্মার্ট ডাটা প্রসেসিং
    $data = $request->all();

    // অটোমেশন: বপন থেকে কাটার দিন গণনা (Cultivation Days)
    if ($request->sowing_date && $request->harvest_date) {
        $data['cultivation_days'] = \Carbon\Carbon::parse($request->sowing_date)
                                    ->diffInDays(\Carbon\Carbon::parse($request->harvest_date));
    }

    // ইউনিক স্মার্ট ব্যাচ নম্বর জেনারেশন
    $data['batch_no'] = 'SAGRI-' . date('ymd') . '-' . strtoupper(Str::random(4));

    // ৩. কিউআর কোড জেনারেশন (অ্যাডভান্সড ডিজাইন)
    $qr_name = $data['batch_no'] . '.svg';
    $qr_path = 'uploads/qrcodes/' . $qr_name;

    QrCode::format('svg')->size(300)->margin(1)
          ->color(25, 135, 84) 
          ->generate(route('public.trace', $data['batch_no']), public_path($qr_path));

    $data['qr_code'] = $qr_path;
    $data['qc_status'] = 'pending'; // বাই ডিফল্ট পেন্ডিং

    Batch::create($data);

    return redirect()->route('batches.index')->with('success', 'Smart Batch Active with AI Analysis.');
}
    /**
     * এডিট ফর্ম
     */
    public function edit($id)
    {
        $batch = Batch::findOrFail($id);
        $products = Product::all();
        $farmers = Stakeholder::where('role', 'farmer')->get();
        return view("admin.batches.edit", compact("batch", "products", "farmers"));
    }

    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $batch->update($request->all());
        return redirect()->route('batches.index')->with("success", "Batch Information Updated.");
    }

    /**
     * স্মার্ট কিউসি অ্যাপ্রুভাল লজিক
     */
    public function approve(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $analysis = $this->runSafetyAnalysis($batch);

        $batch->update([
            'qc_status'       => $analysis['is_safe'] ? 'approved' : 'rejected',
            'quality_grade'   => $request->quality_grade ?? 'A',
            'qc_officer_name' => auth()->user()->name ?? 'System Admin',
            'qc_remarks'      => $analysis['message'] . " | " . $request->remarks,
            'current_location'=> 'Processing Center'
        ]);

        $type = $analysis['is_safe'] ? 'success' : 'error';
        return back()->with($type, $analysis['message']);
    }

    private function runSafetyAnalysis($batch)
    {
        $isSafe = true;
        $message = "Verified: Meets Global Safety Standards.";

        if ($batch->sowing_date && $batch->harvest_date) {
            $days = Carbon::parse($batch->sowing_date)->diffInDays(Carbon::parse($batch->harvest_date));
            if ($days < 90) {
                $isSafe = false;
                $message = "Alert: Premature Harvest ($days days cycle).";
            }
        }

        if ($batch->last_pesticide_date && $batch->harvest_date) {
            $gap = Carbon::parse($batch->last_pesticide_date)->diffInDays(Carbon::parse($batch->harvest_date));
            if ($gap < 7) {
                $isSafe = false;
                $message = "Chemical Risk: Harvested within $gap days gap.";
            }
        }

        return ['is_safe' => $isSafe, 'message' => $message];
    }

    /**
     * ট্রেসেবিলিটি ডাটা (পাবলিক ভিউ)
     */
    public function traceProduct($batch_no)
    {
        $batch = Batch::with(['product', 'farmer'])->where('batch_no', $batch_no)->firstOrFail();
        return view('public.trace', compact('batch'));
    }

    /**
     * সফট ডিলিট এবং রিকভারি
     */
    public function delete($id)
    {
        Batch::findOrFail($id)->delete();
        return back()->with('success', 'Batch moved to trash.');
    }

    public function trashed()
    {
        $batches = Batch::onlyTrashed()->with(['product', 'farmer'])->paginate(10);
        return view("admin.batches.trashed", compact("batches"));
    }

    public function restore($id)
    {
        Batch::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('batches.index')->with('success', 'Batch restored successfully.');
    }

    public function force_delete($id)
    {
        $batch = Batch::withTrashed()->findOrFail($id);
        // Delete QR Code image from storage
        if ($batch->qr_code && File::exists(public_path($batch->qr_code))) {
            File::delete(public_path($batch->qr_code));
        }
        $batch->forceDelete();
        return back()->with('success', 'Batch and files permanently deleted.');
    }
}
