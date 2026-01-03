<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    // ১. শুধুমাত্র কৃষকদের তালিকা দেখানো (Search সহ)
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
            ->paginate(10);

        return view("farmer.index", compact("farmers"));
    }

    // ২. ট্র্যাশ লিস্ট (সফট ডিলিট হওয়া কৃষক)
    public function trashed()
    {
        $farmers = Stakeholder::onlyTrashed()
            ->where('role', 'farmer')
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("farmer.trashed", compact("farmers"));
    }

    public function create()
    {
        return view("farmer.create");
    }

    // ৩. নতুন কৃষক সেভ করা
    public function store(Request $request)
    {
        // স্টেকহোল্ডার টেবিলে ডাটা সেভ
        $stakeholder = new Stakeholder();
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->role    = 'farmer'; // ফিক্সড রোল
        $stakeholder->address = $request->address;
        $stakeholder->nid     = $request->nid;
        $stakeholder->save();

        // ফারমার টেবিলে অতিরিক্ত ডাটা সেভ
        Farmer::create([
            'stakeholder_id' => $stakeholder->id,
            'land_area'      => $request->land_area,
            'farmer_card_no' => $request->farmer_card_no
        ]);

        return redirect()->route('farmer.index')->with("success", "Farmer Created successfully!");
    }

    // ৪. এডিট পেজ (রিলেশনসহ)
    public function edit($id)
    {
        $farmer = Stakeholder::with('farmer')->where('role', 'farmer')->findOrFail($id);
        return view("farmer.edit", compact("farmer"));
    }

    // ৫. আপডেট মেথড (updateOrCreate ব্যবহার করা হয়েছে সেফটির জন্য)
    public function update(Request $request, $id)
    {
        $stakeholder = Stakeholder::findOrFail($id);

        // মেইন ডাটা আপডেট
        $stakeholder->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
            'nid'     => $request->nid,
        ]);

        // ফারমার টেবিল আপডেট (ডাটা না থাকলে তৈরি হবে)
        Farmer::updateOrCreate(
            ['stakeholder_id' => $id],
            [
                'land_area'      => $request->land_area,
                'farmer_card_no' => $request->farmer_card_no
            ]
        );

        return redirect()->route('farmer.index')->with("success", "Farmer updated successfully");
    }

    // ৬. সফট ডিলিট
    public function destroy($id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $stakeholder->delete(); // মেইন টেবিল ডিলিট

        // সাব-টেবিল ফারমার ডিলিট
        Farmer::where('stakeholder_id', $id)->delete();

        return redirect()->route('farmer.index')->with("success", "Farmer moved to trash");
    }

    // ৭. রিস্টোর করা
    public function restore($id)
    {
        Stakeholder::withTrashed()->where('id', $id)->restore();
        Farmer::withTrashed()->where('stakeholder_id', $id)->restore();

        return redirect()->route('farmer.index')->with("success", "Farmer restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট
    public function forceDelete($id)
    {
        $stakeholder = Stakeholder::withTrashed()->findOrFail($id);

        // ফারমার টেবিল থেকে স্থায়ীভাবে মুছে ফেলা
        Farmer::withTrashed()->where('stakeholder_id', $id)->forceDelete();

        // স্টেকহোল্ডার টেবিল থেকে স্থায়ীভাবে মুছে ফেলা
        $stakeholder->forceDelete();

        return redirect()->route('farmer.trashed')->with("success", "Farmer permanently deleted");
    }
}
