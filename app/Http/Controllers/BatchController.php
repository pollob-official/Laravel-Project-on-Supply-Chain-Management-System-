<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Stakeholder;
use App\Models\ProductJourney;
use App\Models\ActivityLog;
use App\Services\BatchRiskService;
use App\Services\DataCompletenessService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    /**
     * অ্যাডভান্সড সার্চ এবং ডায়নামিক সোর্স লোডিং
     */
    public function index(Request $request, BatchRiskService $riskService, DataCompletenessService $completenessService)
    {
        $batches = Batch::with(['product.unit', 'source']) // Product-এর সাথে Unit-ও লোড করা হয়েছে
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where("batch_no", "LIKE", "%{$request->search}%")
                      ->orWhere("seed_brand", "LIKE", "%{$request->search}%")
                      ->orWhere("certification_type", "LIKE", "%{$request->search}%")
                      ->orWhereHas('product', function($sq) use ($request) {
                          $sq->where('name', 'LIKE', "%{$request->search}%");
                      })
                      ->orWhereHas('source', function($sq) use ($request) {
                          $sq->where('name', 'LIKE', "%{$request->search}%");
                      });
                });
            })
            ->latest()
            ->paginate(15);

        $riskSummary = [];
        $completenessSummary = [];
        foreach ($batches as $batch) {
            $riskSummary[$batch->id] = $riskService->calculateRiskScore($batch);
            $completenessSummary[$batch->id] = $completenessService->scoreBatch($batch);
        }

        return view("admin.batches.index", compact("batches", "riskSummary", "completenessSummary"));
    }

    public function create()
    {
        // প্রোডাক্টের সাথে তার সংশ্লিষ্ট ইউনিট টেবিল থেকে ডাটা আনা হচ্ছে
        $products = Product::with('unit')->get();
        $stakeholders = Stakeholder::all();

        return view("admin.batches.create", compact("products", "stakeholders"));
    }

    /**
     * Store: ডায়নামিক সোর্স, প্রাইসিং এবং QR জেনারেশন
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'source_type' => 'required',
            'source_id' => 'required',
            'total_quantity' => 'required|numeric|min:0.1',
            'buying_price_per_unit' => 'required|numeric',
            'manufacturing_date' => 'required|date',
            'manual_address' => 'required',
        ]);

        $data = $request->all();
        $batch_no = 'SAGRI-' . date('ymd') . '-' . strtoupper(Str::random(5));
        $data['batch_no'] = $batch_no;

        // QR Code Generation
        if (!File::exists(public_path('uploads/qrcodes'))) {
            File::makeDirectory(public_path('uploads/qrcodes'), 0777, true);
        }
        $qr_path = 'uploads/qrcodes/' . $batch_no . '.svg';

        // পাবলিক ট্রেস রাউট যদি না থাকে তবে এরর এড়াতে চেক করুন
        QrCode::format('svg')->size(300)->margin(1)->color(25, 135, 84)
            ->generate(route('public.trace', ['batch_no' => $batch_no]), public_path($qr_path));

        $data['qr_code'] = $qr_path;
        $data['qc_status'] = 'pending';
        $data['safety_score'] = 100;

        // টোটাল কস্ট ক্যালকুলেশন
        $data['total_buying_cost'] = $request->buying_price_per_unit * $request->total_quantity;

        $batch = Batch::create($data);

        ActivityLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'action' => 'batch_created',
            'subject_type' => Batch::class,
            'subject_id' => $batch->id,
            'changes' => json_encode($batch->toArray()),
        ]);

        return redirect()->route('batches.index')->with('success', 'Global Standard Batch initiated successfully.');
    }

    public function edit($id)
    {
        $batch = Batch::findOrFail($id);
        $products = Product::with('unit')->get();
        $stakeholders = Stakeholder::all(); // এডিট পেজে সব স্টেকহোল্ডার ফিল্টারিং এর জন্য লাগবে

        return view("admin.batches.edit", compact("batch", "products", "stakeholders"));
    }

    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $request->validate([
            'product_id' => 'required',
            'total_quantity' => 'required|numeric',
            'manufacturing_date' => 'required|date',
        ]);

        $data = $request->all();

        $batch->update($data);

        ActivityLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'action' => 'batch_updated',
            'subject_type' => Batch::class,
            'subject_id' => $batch->id,
            'changes' => json_encode($batch->getChanges()),
        ]);
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
            'quality_grade'   => $request->quality_grade ?? ($analysis['score'] >= 80 ? 'Premium' : 'Standard'),
            'withholding_period_days' => $analysis['withholding_period_days'],
            'residue_risk_level'      => $analysis['residue_risk_level'],
            'qc_officer_name' => Auth::check() ? Auth::user()->name : 'SYSTEM AUDITOR',
            'qc_remarks'      => $analysis['message'] . " | " . $request->remarks,
            'current_location'=> 'QC Certified Warehouse'
        ]);

        ActivityLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'action' => 'batch_qc_approved',
            'subject_type' => Batch::class,
            'subject_id' => $batch->id,
            'changes' => json_encode($batch->getChanges()),
        ]);

        $type = $analysis['is_safe'] ? 'success' : 'error';
        return back()->with($type, "QC Analysis Complete: Score {$analysis['score']}%");
    }

    private function runSafetyAnalysis($batch)
    {
        $score = 100;
        $isSafe = true;
        $reasons = [];
        $withholdingDays = null;
        $riskLevel = null;

        if ($batch->source_type == 'farmer' && $batch->sowing_date && $batch->harvest_date) {
            $days = Carbon::parse($batch->sowing_date)->diffInDays(Carbon::parse($batch->harvest_date));
            if ($days < 85) {
                $score -= 30;
                $reasons[] = "Short growth cycle";
            }
        }

        if ($batch->last_pesticide_date && $batch->harvest_date) {
            $withholdingDays = Carbon::parse($batch->last_pesticide_date)->diffInDays(Carbon::parse($batch->harvest_date));

            if ($withholdingDays < 7) {
                $score -= 50;
                $isSafe = false;
                $riskLevel = 'high';
                $reasons[] = "Chemical residue risk (withholding < 7 days)";
            } elseif ($withholdingDays < 14) {
                $score -= 20;
                $riskLevel = 'medium';
                $reasons[] = "Moderate residue risk (withholding < 14 days)";
            } else {
                $riskLevel = 'low';
            }
        }

        $message = empty($reasons) ? "GAP Certified & Safe." : "Warning: " . implode(', ', $reasons);

        return [
            'is_safe' => $isSafe,
            'score' => max($score, 0),
            'message' => $message,
            'withholding_period_days' => $withholdingDays,
            'residue_risk_level' => $riskLevel,
        ];
    }

    public function traceProduct($batch_no) {
        $batch = Batch::with(['product.unit', 'source'])->where('batch_no', $batch_no)->firstOrFail();
        $journeys = ProductJourney::with(['seller', 'buyer'])
            ->where('batch_id', $batch->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('public.trace', compact('batch', 'journeys'));
    }

    // --- Soft Delete Functionality (Completed as requested) ---

    public function destroy($id) {
        $batch = Batch::findOrFail($id);
        $batch->delete(); // Soft Delete
        return redirect()->route('batches.index')->with('warning', 'Batch moved to trash.');
    }

    public function trashed() {
        // ট্র্যাশে থাকা ডাটা দেখানোর জন্য onlyTrashed() ব্যবহার করা হয়েছে
        $batches = Batch::onlyTrashed()->with(['product', 'source'])->latest()->paginate(10);
        return view("admin.batches.trashed", compact("batches"));
    }

    public function restore($id) {
        $batch = Batch::withTrashed()->findOrFail($id);
        $batch->restore();
        return redirect()->route('batches.index')->with('success', 'Batch restored from trash.');
    }

    public function force_delete($id) {
        $batch = Batch::withTrashed()->findOrFail($id);

        // চিরতরে ডিলিট করার আগে QR Code ফাইলটি সার্ভার থেকে মুছে ফেলা হচ্ছে
        if ($batch->qr_code && File::exists(public_path($batch->qr_code))) {
            File::delete(public_path($batch->qr_code));
        }

        $batch->forceDelete();
        return back()->with('success', 'Batch permanently deleted.');
    }
}
