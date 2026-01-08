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
    // ১. ব্যাচ লিস্ট দেখা (সার্চ ও ফিল্টারসহ)
    public function index(Request $request)
    {
        $batches = Batch::with(['product', 'farmer'])
            ->when($request->search, function ($query) use ($request) {
                return $query->where("batch_no", "LIKE", "%" . $request->search . "%")
                             ->orWhere("seed_brand", "LIKE", "%" . $request->search . "%");
            })
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("admin.batches.index", compact("batches"));
    }

    // ২. ক্রিয়েট পেজ
    public function create()
    {
        $products = Product::all();
        $farmers = Stakeholder::where('role', 'farmer')->get();
        return view("admin.batches.create", compact("products", "farmers"));
    }

    // ৩. ব্যাচ স্টোর - যেখানে সব স্মার্ট লজিক কাজ করবে
    public function store(Request $request)
    {
        $request->validate([
            'product_id'         => 'required',
            'initial_farmer_id'  => 'required',
            'total_quantity'     => 'required|numeric',
            'manufacturing_date' => 'required|date',
        ]);

        // ব্যাচ নম্বর ও কিউআর পাথ জেনারেশন
        $batchNo = 'B-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $path = public_path('uploads/qrcodes/');
        if (!File::exists($path)) File::makeDirectory($path, 0777, true, true);

        // QR Code URL (স্ক্যান করলে পাবলিক ট্রেস পেজে যাবে)
        $qrName = $batchNo . '.svg';
        QrCode::format('svg')->size(300)->margin(1)->generate(url('/trace/'.$batchNo), $path . $qrName);

        // ডাটাবেস এন্ট্রি (আপনার সব নতুন আইডিয়া সহ)
        Batch::create($request->all() + [
            'batch_no'         => $batchNo,
            'qr_code'          => 'uploads/qrcodes/' . $qrName,
            'qc_status'        => 'pending',
            'current_location' => 'Farmer Field', // ইনিশিয়াল লোকেশন
        ]);

        return redirect()->route('batches.index')->with("success", "Global Standard Batch generated with Traceability Data!");
    }

    // ৪. আপডেট ফাংশন (সব ডাটা হ্যান্ডেল করবে)
    public function update(Request $request, $id)
{
    $batch = Batch::findOrFail($id);

    $request->validate([
        'product_id'         => 'required',
        'initial_farmer_id'  => 'required',
        'total_quantity'     => 'required|numeric',
        'manufacturing_date' => 'required|date',
    ]);

    // Mass assignment ব্যবহার করে সব ফিল্ড একবারে আপডেট করা
    // এখানে $request->all() আপনার সবকটি স্মার্ট ফিল্ড (grade, location, dates) অটো ধরে নেবে
    $batch->update($request->all());

    return redirect()->route('batches.index')->with("success", "Batch [ $batch->batch_no ] updated with all traceability records.");
}

    // ৫. স্মার্ট এপ্রুভাল ও সেফটি চেক (Maturity & Pesticide Analysis)
    public function approve(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $analysis = $this->runSafetyAnalysis($batch);

        $batch->update([
            'qc_status'       => $analysis['is_safe'] ? 'approved' : 'rejected',
            'quality_grade'   => $request->quality_grade ?? 'B', // A, B, C Grade
            'qc_officer_name' => auth()->user()->name ?? 'Admin',
            'qc_remarks'      => $analysis['message'] . " | " . $request->remarks,
            'current_location'=> 'Processing Unit' // এপ্রুভ হওয়ার পর স্টেজ চেঞ্জ
        ]);

        return back()->with($analysis['is_safe'] ? 'success' : 'error', $analysis['message']);
    }

    /**
     * স্মার্ট লজিক: ম্যচুরিটি ও কীটনাশক সেফটি অ্যানালাইসিস
     */
    private function runSafetyAnalysis($batch)
    {
        $isSafe = true;
        $message = "Verified: Passed Safety Standards.";

        // ক) Maturity Analysis (আদর্শ সময় ১০০ দিন ধরলে)
        if ($batch->sowing_date && $batch->harvest_date) {
            $days = Carbon::parse($batch->sowing_date)->diffInDays(Carbon::parse($batch->harvest_date));
            if ($days < 90) {
                $isSafe = false;
                $message = "Alert: Premature Harvest (Cycle: $days days). Chemical use suspected.";
            }
        }

        // খ) Pesticide Withdrawal Period (৭ দিন গ্যাপ লাগবে)
        if ($batch->pesticide_history && $batch->harvest_date) {
            // ধরি pesticide_history ফিল্ডে শেষ স্প্রে করার তারিখ থাকে (অথবা আলাদা কলাম 'last_pesticide_date')
            // এখানে লজিক সহজ করার জন্য last_pesticide_date কলাম ব্যবহার করছি
            if($batch->last_pesticide_date){
                $gap = Carbon::parse($batch->last_pesticide_date)->diffInDays(Carbon::parse($batch->harvest_date));
                if ($gap < 7) {
                    $isSafe = false;
                    $message = "Chemical Risk: Harvested only $gap days after pesticide spray!";
                }
            }
        }

        return ['is_safe' => $isSafe, 'message' => $message];
    }

    // ৬. সফট ডিলিট ও ফাইল ম্যানেজমেন্ট (আপনার রিকোয়েস্ট অনুযায়ী)
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
        return back()->with('success', 'Batch restored successfully.');
    }

    public function force_delete($id)
    {
        $batch = Batch::withTrashed()->findOrFail($id);
        if ($batch->qr_code && File::exists(public_path($batch->qr_code))) {
            File::delete(public_path($batch->qr_code));
        }
        $batch->forceDelete();
        return back()->with('success', 'Permanently deleted from server.');
    }
}
