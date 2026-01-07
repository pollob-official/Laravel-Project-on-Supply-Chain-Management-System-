<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Transaction ব্যবহারের জন্য

class FarmerController extends Controller
{
    // ১. কৃষকদের তালিকা (Search + Crop History সহ)
    public function index(Request $request)
    {
        $farmers = Stakeholder::with('farmer')
            ->where('role', 'farmer')
            ->when($request->search, function($query) use($request) {
                return $query->where(function($q) use($request) {
                    $q->where("name", "LIKE", "%" . $request->search . "%")
                      ->orWhere("phone", "LIKE", "%" . $request->search . "%")
                      ->orWhere("nid", "LIKE", "%" . $request->search . "%");
                });
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.farmer.index", compact("farmers"));
    }

    // ২. ট্র্যাশ লিস্ট
    public function trashed()
    {
        $farmers = Stakeholder::onlyTrashed()
            ->where('role', 'farmer')
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("admin.farmer.trashed", compact("farmers"));
    }

    public function create()
    {
        return view("admin.farmer.create");
    }

    // ৩. নতুন কৃষক সেভ করা (Crop History সহ)
    public function store(Request $request)
    {
        // ডাটাবেস ট্রানজেকশন ব্যবহার করা নিরাপদ যাতে এক টেবিলে ডাটা সেভ হয়ে অন্যটায় ফেইল না করে
        DB::transaction(function () use ($request) {
            $stakeholder = Stakeholder::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'role'    => 'farmer',
                'address' => $request->address,
                'nid'     => $request->nid,
            ]);

            Farmer::create([
                'stakeholder_id' => $stakeholder->id,
                'land_area'      => $request->land_area,
                'farmer_card_no' => $request->farmer_card_no,
                'crop_history'   => $request->crop_history // ক্রপ হিস্ট্রি অ্যাড করা হলো
            ]);
        });

        return redirect('farmer')->with("success", "Farmer Created successfully!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $farmer = Stakeholder::with('farmer')->where('role', 'farmer')->findOrFail($id);
        return view("admin.farmer.edit", compact("farmer"));
    }

    // ৫. আপডেট মেথড (Crop History সহ)
    public function update(Request $request, $id)
    {
        $stakeholder = Stakeholder::findOrFail($id);

        DB::transaction(function () use ($request, $stakeholder, $id) {
            $stakeholder->update([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
                'nid'     => $request->nid,
            ]);

            Farmer::updateOrCreate(
                ['stakeholder_id' => $id],
                [
                    'land_area'      => $request->land_area,
                    'farmer_card_no' => $request->farmer_card_no,
                    'crop_history'   => $request->crop_history // ক্রপ হিস্ট্রি আপডেট করা হলো
                ]
            );
        });

        return redirect('farmer')->with("success", "Farmer updated successfully");
    }

    // ৬. সফট ডিলিট
    public function delete($id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $stakeholder->delete();

        // সাব-টেবিল সফট ডিলিট (মডেলে SoftDeletes ট্রেইট থাকলে)
        Farmer::where('stakeholder_id', $id)->delete();

        return redirect('farmer')->with("success", "Farmer moved to trash");
    }

    // ৭. রিস্টোর করা
    public function restore($id)
    {
        Stakeholder::withTrashed()->where('id', $id)->restore();
        Farmer::withTrashed()->where('stakeholder_id', $id)->restore();

        return redirect('farmer')->with("success", "Farmer restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট (আপনার forceDelete ফাংশন নাম অনুযায়ী)
    public function force_delete($id)
    {
        Farmer::withTrashed()->where('stakeholder_id', $id)->forceDelete();
        Stakeholder::withTrashed()->findOrFail($id)->forceDelete();

        return redirect('farmer/trashed')->with("success", "Farmer permanently deleted");
    }
}
