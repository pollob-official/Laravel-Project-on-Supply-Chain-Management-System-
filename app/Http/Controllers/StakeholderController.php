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
    // ১. লিস্ট দেখা (Eager Loading নিশ্চিত করা হয়েছে যাতে ডাটা ঠিকমতো লোড হয়)
    public function index(Request $request)
    {
        $stakeholders = Stakeholder::with(['farmer', 'miller', 'wholesaler', 'retailer'])
            ->when($request->search, function($query) use($request) {
                return $query->whereAny([
                    "name", "email", "phone", "role", "address"
                ], "LIKE", "%" . $request->search . "%");
            })->orderBy("id", "desc")->paginate(5);

        return view("admin.stakeholder.index", compact("stakeholders"));
    }

    // ২. ট্র্যাশ লিস্ট (সফট ডিলিট হওয়া ডাটা দেখার জন্য)
    public function trashed()
    {
        $stakeholders = Stakeholder::onlyTrashed()->orderBy("id", "desc")->paginate(8);
        return view("admin.stakeholder.trashed", compact("stakeholders"));
    }

    public function create()
    {
        return view("admin.stakeholder.create");
    }

    // ৩. সেভ করার মেথড
    public function save(Request $request)
    {
        $stakeholder = new Stakeholder();
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->role    = $request->role;
        $stakeholder->address = $request->address;
        $stakeholder->nid     = $request->nid;
        $stakeholder->external_code = $request->external_code;
        $stakeholder->gln      = $request->gln;
        $stakeholder->save();

        $id = $stakeholder->id;
        $role = strtolower($request->role); // সেফটি চেক

        if ($role == 'farmer') {
            Farmer::create(['stakeholder_id' => $id, 'land_area' => $request->land_area, 'farmer_card_no' => $request->farmer_card_no]);
        } elseif ($role == 'miller') {
            MillersSupplier::create(['stakeholder_id' => $id, 'factory_license' => $request->factory_license]);
        } elseif ($role == 'wholesaler') {
            Wholesaler::create(['stakeholder_id' => $id, 'trade_license' => $request->trade_license]);
        } elseif ($role == 'retailer') {
            Retailer::create(['stakeholder_id' => $id, 'shop_name' => $request->shop_name]);
        }

        return redirect("admin/stakeholder")->with("success", "Stakeholder Created successfully!");
    }

    // ৪. এডিট মেথড (Eager Loading ব্যবহার করা হয়েছে যাতে রিলেশন ডাটা null না আসে)
    public function edit($id)
    {
        $stakeholder = Stakeholder::with(['farmer', 'miller', 'wholesaler', 'retailer'])->find($id);

        if (!$stakeholder) {
            return redirect("admin/stakeholder")->with("error", "Stakeholder not found!");
        }

        return view("admin.stakeholder.edit", compact("stakeholder"));
    }

    // ৫. আপডেট মেথড (Safe Side: updateOrCreate ব্যবহার করা হয়েছে)
    public function update(Request $request, $id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $stakeholder->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
            'nid'     => $request->nid,
            'external_code' => $request->external_code,
            'gln'           => $request->gln,
        ]);

        $role = strtolower($stakeholder->role);

        // যেহেতু আপনার কিছু সাব-টেবিলে ডাটা নেই, তাই updateOrCreate ডাটা থাকলে আপডেট করবে, না থাকলে তৈরি করবে।
        if ($role == 'farmer') {
            Farmer::updateOrCreate(
                ['stakeholder_id' => $id],
                ['land_area' => $request->land_area, 'farmer_card_no' => $request->farmer_card_no]
            );
        } elseif ($role == 'miller') {
            MillersSupplier::updateOrCreate(
                ['stakeholder_id' => $id],
                ['factory_license' => $request->factory_license]
            );
        } elseif ($role == 'wholesaler') {
            Wholesaler::updateOrCreate(
                ['stakeholder_id' => $id],
                ['trade_license' => $request->trade_license]
            );
        } elseif ($role == 'retailer') {
            Retailer::updateOrCreate(
                ['stakeholder_id' => $id],
                ['shop_name' => $request->shop_name]
            );
        }

        return redirect("admin/stakeholder")->with("success", "Updated successfully");
    }

    // ৬. সফট ডিলিট (সাব-টেবিলসহ)
    public function delete($id)
    {
        $stakeholder = Stakeholder::find($id);
        if ($stakeholder) {
            $stakeholder->delete();

            $role = strtolower($stakeholder->role);
            if ($role == 'farmer') Farmer::where('stakeholder_id', $id)->delete();
            if ($role == 'miller') MillersSupplier::where('stakeholder_id', $id)->delete();
            if ($role == 'wholesaler') Wholesaler::where('stakeholder_id', $id)->delete();
            if ($role == 'retailer') Retailer::where('stakeholder_id', $id)->delete();
        }
        return redirect("admin/stakeholder")->with("success", "Moved to Trash");
    }

    // ৭. রিস্টোর (সাব-টেবিলসহ)
    public function restore($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);
        if ($stakeholder) {
            $stakeholder->restore();

            $role = strtolower($stakeholder->role);
            if ($role == 'farmer') Farmer::withTrashed()->where('stakeholder_id', $id)->restore();
            if ($role == 'miller') MillersSupplier::withTrashed()->where('stakeholder_id', $id)->restore();
            if ($role == 'wholesaler') Wholesaler::withTrashed()->where('stakeholder_id', $id)->restore();
            if ($role == 'retailer') Retailer::withTrashed()->where('stakeholder_id', $id)->restore();
        }
        return redirect("admin/stakeholder")->with("success", "Restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট
    public function force_delete($id)
    {
        $stakeholder = Stakeholder::withTrashed()->find($id);
        if ($stakeholder) {
            $role = strtolower($stakeholder->role);
            if ($role == 'farmer') Farmer::withTrashed()->where('stakeholder_id', $id)->forceDelete();
            if ($role == 'miller') MillersSupplier::withTrashed()->where('stakeholder_id', $id)->forceDelete();
            if ($role == 'wholesaler') Wholesaler::withTrashed()->where('stakeholder_id', $id)->forceDelete();
            if ($role == 'retailer') Retailer::withTrashed()->where('stakeholder_id', $id)->forceDelete();

            $stakeholder->forceDelete();
        }
        return redirect("admin/stakeholder/trashed")->with("success", "Permanently Deleted");
    }
}
