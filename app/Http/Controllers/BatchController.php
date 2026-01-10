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
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    /**
     * Display a listing of batches with advanced search.
     */
    public function index(Request $request)
    {
        $batches = Batch::with(['product', 'farmer'])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where("batch_no", "LIKE", "%{$request->search}%")
                      ->orWhere("seed_brand", "LIKE", "%{$request->search}%")
                      ->orWhere("certification_type", "LIKE", "%{$request->search}%")
                      // অ্যাডভান্সড সার্চ: প্রোডাক্ট বা কৃষকের নাম দিয়েও সার্চ হবে
                      ->orWhereHas('product', function($sq) use ($request) {
                          $sq->where('name', 'LIKE', "%{$request->search}%");
                      })
                      ->orWhereHas('farmer', function($sq) use ($request) {
                          $sq->where('name', 'LIKE', "%{$request->search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10);

        return view("admin.batches.index", compact("batches"));
    }

    public function create()
    {
        $products = Product::all();
        $farmers = Stakeholder::where('role', 'farmer')->get();
        return view("admin.batches.create", compact("products", "farmers"));
    }

    /**
     * Store Function: QR জেনারেশন ফিক্সড
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'initial_farmer_id' => 'required',
            'total_quantity' => 'required|numeric|min:1',
            'manufacturing_date' => 'required|date',
            'farmer_price' => 'nullable|numeric',
            'processing_cost' => 'nullable|numeric',
            'target_retail_price' => 'nullable|numeric',
        ]);

        $data = $request->all();

        // ইউনিক ব্যাচ নম্বর
        $batch_no = 'SAGRI-' . date('ymd') . '-' . strtoupper(Str::random(4));
        $data['batch_no'] = $batch_no;

        // কিউআর কোড ম্যানেজমেন্ট
        if (!File::exists(public_path('uploads/qrcodes'))) {
            File::makeDirectory(public_path('uploads/qrcodes'), 0777, true);
        }
        $qr_path = 'uploads/qrcodes/' . $batch_no . '.svg';

        // ইনটেলফেন্স ফিক্সড রাউট কল
        QrCode::format('svg')->size(300)->margin(1)
            ->color(25, 135, 84)
            ->generate(route('public.trace', ['batch_no' => $batch_no]), public_path($qr_path));

        $data['qr_code'] = $qr_path;
        $data['qc_status'] = 'pending';
        $data['safety_score'] = 100;

        Batch::create($data);

        return redirect()->route('batches.index')->with('success', 'Global Standard Batch initiated successfully.');
    }

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
        $request->validate([
            'total_quantity' => 'required|numeric',
            'manufacturing_date' => 'required|date',
        ]);

        $batch->update($request->all());
        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Smart QC Approval
     */
    public function approve(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $analysis = $this->runSafetyAnalysis($batch);

        $batch->update([
            'qc_status'       => $analysis['is_safe'] ? 'approved' : 'rejected',
            'safety_score'    => $analysis['score'],
            'quality_grade'   => $request->quality_grade ?? ($analysis['score'] >= 80 ? 'A' : 'B'),
            'qc_officer_name' => Auth::check() ? Auth::user()->name : 'POLLOB AHMED',
            'qc_remarks'      => $analysis['message'] . " | " . $request->remarks,
            'moisture_level'  => $request->moisture_level ?? $batch->moisture_level,
            'current_location'=> 'QC Certified Warehouse'
        ]);

        $type = $analysis['is_safe'] ? 'success' : 'error';
        return back()->with($type, "Analysis Complete: Score {$analysis['score']}%");
    }

    private function runSafetyAnalysis($batch)
    {
        $score = 100;
        $isSafe = true;
        $reasons = [];

        if ($batch->sowing_date && $batch->harvest_date) {
            $days = Carbon::parse($batch->sowing_date)->diffInDays(Carbon::parse($batch->harvest_date));
            if ($days < 90) { $score -= 30; $reasons[] = "Short growth cycle"; $isSafe = false; }
        }

        if ($batch->last_pesticide_date && $batch->harvest_date) {
            $gap = Carbon::parse($batch->last_pesticide_date)->diffInDays(Carbon::parse($batch->harvest_date));
            if ($gap < 7) { $score -= 50; $reasons[] = "Toxic Residue Risk"; $isSafe = false; }
        }

        $message = empty($reasons) ? "GAP Certified & Safe for Consumption." : "Issues: " . implode(', ', $reasons);

        return ['is_safe' => $isSafe, 'score' => max($score, 0), 'message' => $message];
    }

    // --- Soft Delete Functionality ---
    public function delete($id) {
        Batch::findOrFail($id)->delete();
        return back()->with('success', 'Batch moved to trash successfully.');
    }

    public function trashed() {
        $batches = Batch::onlyTrashed()->with(['product', 'farmer'])->latest()->paginate(10);
        return view("admin.batches.trashed", compact("batches"));
    }

    public function restore($id) {
        Batch::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('batches.index')->with('success', 'Batch restored.');
    }

    public function force_delete($id) {
        $batch = Batch::withTrashed()->findOrFail($id);
        if ($batch->qr_code && File::exists(public_path($batch->qr_code))) {
            File::delete(public_path($batch->qr_code));
        }
        $batch->forceDelete();
        return back()->with('success', 'Batch permanently deleted.');
    }

    public function traceProduct($batch_no) {
        $batch = Batch::with(['product', 'farmer'])->where('batch_no', $batch_no)->firstOrFail();
        return view('public.trace', compact('batch'));
    }
}
