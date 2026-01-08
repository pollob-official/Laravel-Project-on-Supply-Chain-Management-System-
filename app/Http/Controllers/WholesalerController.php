<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Wholesaler;
use Illuminate\Http\Request;

class WholesalerController extends Controller
{
    // ১. পাইকারদের তালিকা দেখা
    public function index(Request $request)
    {
        $wholesalers = Stakeholder::where('role', 'wholesaler')
            ->with('wholesaler') // Wholesaler সাব-টেবিল রিলেশন
            ->when($request->search, function($query) use($request) {
                return $query->where(function($q) use($request) {
                    $q->where("name", "LIKE", "%" . $request->search . "%")
                      ->orWhere("phone", "LIKE", "%" . $request->search . "%")
                      ->orWhereHas('wholesaler', function($subQuery) use($request) {
                          $subQuery->where('trade_license', 'LIKE', "%" . $request->search . "%");
                      });
                });
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.wholesaler.index", compact("wholesalers"));
    }

    // ২. ট্র্যাশ লিস্ট (সফট ডিলিট ডাটা)
    public function trashed()
    {
        $wholesalers = Stakeholder::where('role', 'wholesaler')
            ->onlyTrashed()
            ->with('wholesaler')
            ->orderBy("id", "desc")
            ->paginate(8);

        return view("admin.wholesaler.trashed", compact("wholesalers"));
    }

    public function create()
    {
        return view("admin.wholesaler.create");
    }

    // ৩. ডাটা সেভ করা (Stakeholder + Wholesaler Table)
    public function save(Request $request)
    {
        $stakeholder = new Stakeholder();
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->role    = 'wholesaler';
        $stakeholder->address = $request->address;
        $stakeholder->nid     = $request->nid;
        $stakeholder->save();

        // সাব-টেবিলে ডাটা সেভ
        Wholesaler::create([
            'stakeholder_id'     => $stakeholder->id,
            'trade_license'      => $request->trade_license,
            'warehouse_location' => $request->warehouse_location,
            'total_manpower'     => $request->total_manpower ?? 0,
        ]);

        return redirect("admin/wholesaler")->with("success", "Wholesaler added successfully!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $wholesaler = Stakeholder::with('wholesaler')->findOrFail($id);
        return view("admin.wholesaler.edit", compact("wholesaler"));
    }

    // ৫. আপডেট (updateOrCreate ব্যবহার করা হয়েছে যাতে ডাটা মিসিং থাকলেও এরর না দেয়)
    public function update(Request $request, $id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $stakeholder->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
            'nid'     => $request->nid,
        ]);

        Wholesaler::updateOrCreate(
            ['stakeholder_id' => $id],
            [
                'trade_license'      => $request->trade_license,
                'warehouse_location' => $request->warehouse_location,
                'total_manpower'     => $request->total_manpower ?? 0,
            ]
        );

        return redirect("admin/wholesaler")->with("success", "Wholesaler updated successfully");
    }

    // ৬. সফট ডিলিট
    public function delete($id)
    {
        $stakeholder = Stakeholder::find($id);
        if ($stakeholder) {
            $stakeholder->delete(); // Stakeholder সফট ডিলিট
            Wholesaler::where('stakeholder_id', $id)->delete(); // সাব-টেবিল সফট ডিলিট
        }
        return redirect("admin/wholesaler")->with("success", "Moved to Trash");
    }

    // ৭. রিস্টোর (ফেরত আনা)
    public function restore($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);
        if ($stakeholder) {
            $stakeholder->restore();
            Wholesaler::withTrashed()->where('stakeholder_id', $id)->restore();
        }
        return redirect("admin/wholesaler")->with("success", "Wholesaler restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট (ডাটাবেস থেকে চিরতরে মুছে ফেলা)
    public function force_delete($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);
        if ($stakeholder) {
            Wholesaler::withTrashed()->where('stakeholder_id', $id)->forceDelete();
            $stakeholder->forceDelete();
        }
        return redirect("admin/wholesaler/trashed")->with("success", "Wholesaler permanently deleted");
    }
}
