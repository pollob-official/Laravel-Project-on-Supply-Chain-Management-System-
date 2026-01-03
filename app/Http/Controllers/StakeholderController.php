<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Farmer;
use App\Models\MillersSupplier;
use App\Models\Wholesaler;
use App\Models\Retailer;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    // ১. লিস্ট দেখা এবং সার্চিং
    public function index(Request $request)
    {
        $stakeholders = Stakeholder::when($request->search, function($query) use($request) {
            return $query->whereAny([
                "name", "email", "phone", "role", "address"
            ], "LIKE", "%" . $request->search . "%");
        })->orderBy("id", "desc")->paginate(8);

        return view("stakeholder.index", compact("stakeholders"));
    }

    // ২. ট্র্যাশ লিস্ট (Soft Deleted ডাটা)
    public function trashed()
    {
        $stakeholders = Stakeholder::onlyTrashed()->orderBy("id", "desc")->paginate(8);
        return view("stakeholder.trashed", compact("stakeholders"));
    }

    public function create()
    {
        return view("stakeholder.create");
    }

    // ৩. সেভ করার সহজ পদ্ধতি
    public function save(Request $request)
    {
        $stakeholder = new Stakeholder();
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->role    = $request->role;
        $stakeholder->address = $request->address;
        $stakeholder->nid     = $request->nid;
        $stakeholder->save();

        $id = $stakeholder->id;

        if ($request->role == 'farmer') {
            $farmer = new Farmer();
            $farmer->stakeholder_id = $id;
            $farmer->land_area      = $request->land_area;
            $farmer->farmer_card_no = $request->farmer_card_no;
            $farmer->save();
        }
        elseif ($request->role == 'miller') {
            $miller = new MillersSupplier();
            $miller->stakeholder_id = $id;
            $miller->factory_license = $request->factory_license;
            $miller->save();
        }
        elseif ($request->role == 'wholesaler') {
            $wholesaler = new Wholesaler();
            $wholesaler->stakeholder_id = $id;
            $wholesaler->trade_license  = $request->trade_license;
            $wholesaler->save();
        }
        elseif ($request->role == 'retailer') {
            $retailer = new Retailer();
            $retailer->stakeholder_id = $id;
            $retailer->shop_name      = $request->shop_name;
            $retailer->save();
        }

        return redirect("stakeholder")->with("success", "Stakeholder Created successfully!");
    }

    public function edit($id)
    {
        $stakeholder = Stakeholder::find($id);
        return view("stakeholder.edit", compact("stakeholder"));
    }

    // ৪. আপডেট করার সহজ পদ্ধতি
    public function update(Request $request, $id)
    {
        $stakeholder = Stakeholder::find($id);
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->address = $request->address;
        $stakeholder->nid     = $request->nid;
        $stakeholder->update();

        if ($stakeholder->role == 'farmer') {
            Farmer::where('stakeholder_id', $id)->update([
                'land_area' => $request->land_area,
                'farmer_card_no' => $request->farmer_card_no
            ]);
        }
        elseif ($stakeholder->role == 'miller') {
            MillersSupplier::where('stakeholder_id', $id)->update([
                'factory_license' => $request->factory_license
            ]);
        }
        elseif ($stakeholder->role == 'wholesaler') {
            Wholesaler::where('stakeholder_id', $id)->update([
                'trade_license' => $request->trade_license
            ]);
        }
        elseif ($stakeholder->role == 'retailer') {
            Retailer::where('stakeholder_id', $id)->update([
                'shop_name' => $request->shop_name
            ]);
        }

        return redirect("stakeholder")->with("success", "Updated successfully");
    }

    // ৫. সফট ডিলিট (Trash এ পাঠানো)
    public function delete($id)
    {
        Stakeholder::find($id)->delete();
        return redirect("stakeholder")->with("success", "Moved to Trash");
    }

    // ৬. রিস্টোর করা
    public function restore($id)
    {
        Stakeholder::withTrashed()->find($id)->restore();
        return redirect("stakeholder")->with("success", "Restored successfully");
    }

    // ৭. পার্মানেন্ট ডিলিট (Force Delete)
    public function force_delete($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);

        // সাব টেবিল থেকে ডাটা স্থায়ীভাবে ডিলিট করা
        if($stakeholder->role == 'farmer') Farmer::where('stakeholder_id', $id)->delete();
        if($stakeholder->role == 'miller') MillersSupplier::where('stakeholder_id', $id)->delete();
        if($stakeholder->role == 'wholesaler') Wholesaler::where('stakeholder_id', $id)->delete();
        if($stakeholder->role == 'retailer') Retailer::where('stakeholder_id', $id)->delete();

        $stakeholder->forceDelete();
        return redirect("stakeholder/trashed")->with("success", "Permanently Deleted");
    }
}
