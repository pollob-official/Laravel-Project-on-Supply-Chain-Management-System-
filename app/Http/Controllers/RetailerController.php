<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Retailer;
use Illuminate\Http\Request;

class RetailerController extends Controller
{
    // ১. রিটেইলার লিস্ট দেখা (সার্চ ফাংশনালিটিসহ)
    public function index(Request $request)
    {
        $retailers = Stakeholder::where('role', 'retailer')
            ->with('retailer')
            ->when($request->search, function($query) use($request) {
                return $query->where(function($q) use($request) {
                    $q->where("name", "LIKE", "%" . $request->search . "%")
                      ->orWhere("phone", "LIKE", "%" . $request->search . "%")
                      ->orWhereHas('retailer', function($sub) use($request) {
                          $sub->where('shop_name', 'LIKE', "%" . $request->search . "%")
                              ->orWhere('market_name', 'LIKE', "%" . $request->search . "%");
                      });
                });
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.retailer.index", compact("retailers"));
    }

    // ২. ট্র্যাশ লিস্ট (সফট ডিলিট ডাটা)
    public function trashed()
    {
        $retailers = Stakeholder::where('role', 'retailer')
            ->onlyTrashed()
            ->with('retailer')
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.retailer.trashed", compact("retailers"));
    }

    public function create()
    {
        return view("admin.retailer.create");
    }

    // ৩. ডাটা সংরক্ষণ (Stakeholder + Retailer Table)
    public function save(Request $request)
    {
        $stakeholder = new Stakeholder();
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->role    = 'retailer';
        $stakeholder->address = $request->address;
        $stakeholder->nid     = $request->nid;
        $stakeholder->save();

        // রিটেইলার সাব-টেবিলে ডাটা ইনসার্ট
        Retailer::create([
            'stakeholder_id' => $stakeholder->id,
            'shop_name'      => $request->shop_name,
            'tin_no'         => $request->tin_no,
            'market_name'    => $request->market_name,
        ]);

        return redirect("admin/retailer")->with("success", "Retailer added successfully!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $retailer = Stakeholder::with('retailer')->findOrFail($id);
        return view("admin.retailer.edit", compact("retailer"));
    }

    // ৫. আপডেট (updateOrCreate লজিক নিশ্চিত করা হয়েছে)
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

        Retailer::updateOrCreate(
            ['stakeholder_id' => $id],
            [
                'shop_name'   => $request->shop_name,
                'tin_no'      => $request->tin_no,
                'market_name' => $request->market_name,
            ]
        );

        return redirect("admin/retailer")->with("success", "Retailer updated successfully");
    }

    // ৬. সফট ডিলিট
    public function delete($id)
    {
        $stakeholder = Stakeholder::find($id);
        if ($stakeholder) {
            $stakeholder->delete();
            Retailer::where('stakeholder_id', $id)->delete();
        }
        return redirect("admin/retailer")->with("success", "Moved to Trash");
    }

    // ৭. রিস্টোর (ট্র্যাশ থেকে ফেরত আনা)
    public function restore($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);
        if ($stakeholder) {
            $stakeholder->restore();
            Retailer::withTrashed()->where('stakeholder_id', $id)->restore();
        }
        return redirect("admin/retailer")->with("success", "Retailer restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট
    public function force_delete($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);
        if ($stakeholder) {
            Retailer::withTrashed()->where('stakeholder_id', $id)->forceDelete();
            $stakeholder->forceDelete();
        }
        return redirect("admin/retailer/trashed")->with("success", "Retailer permanently deleted");
    }
}
