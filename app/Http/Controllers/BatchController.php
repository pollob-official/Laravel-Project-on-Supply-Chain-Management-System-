<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::with(['product', 'farmer'])
            ->when($request->search, function($query) use($request) {
                return $query->where("batch_no", "LIKE", "%" . $request->search . "%");
            })
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("admin.batches.index", compact("batches"));
    }

    public function trashed()
    {
        $batches = Batch::onlyTrashed()->with(['product', 'farmer'])->orderBy("id", "desc")->paginate(10);
        return view("admin.batches.trashed", compact("batches"));
    }

    public function create()
    {
        $products = Product::all();
        $farmers = Stakeholder::where('role', 'farmer')->get();
        return view("admin.batches.create", compact("products", "farmers"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'initial_farmer_id' => 'required',
            'total_quantity' => 'required|numeric',
            'manufacturing_date' => 'required|date',
        ]);

        $batchNo = 'B-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $path = public_path('uploads/qrcodes/');

        // File ফাসাদ ব্যবহার
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $qrName = $batchNo . '.svg';
        QrCode::format('svg')->size(250)->margin(1)->generate(url('/trace/'.$batchNo), $path . $qrName);

        Batch::create([
            'batch_no'           => $batchNo,
            'product_id'         => $request->product_id,
            'initial_farmer_id'  => $request->initial_farmer_id,
            'total_quantity'     => $request->total_quantity,
            'qr_code'            => 'uploads/qrcodes/' . $qrName,
            'manufacturing_date' => $request->manufacturing_date,
            'expiry_date'        => $request->expiry_date,
        ]);

        return redirect()->route('batches.index')->with("success", "Batch created successfully!");
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
        $batch->update($request->all());
        return redirect()->route('batches.index')->with("success", "Batch updated successfully");
    }

    public function delete($id)
    {
        Batch::find($id)->delete();
        return redirect()->route('batches.index')->with("success", "Moved to trash");
    }

    public function restore($id)
    {
        Batch::withTrashed()->find($id)->restore();
        return redirect()->route('batches.index')->with("success", "Restored successfully");
    }

    public function force_delete($id)
    {
        $batch = Batch::withTrashed()->find($id);
        if ($batch) {
            if (File::exists(public_path($batch->qr_code))) {
                File::delete(public_path($batch->qr_code));
            }
            $batch->forceDelete();
        }
        return redirect()->route('batches.trashed')->with("success", "Deleted permanently");
    }
}
