<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Farmer;
use App\Models\Wholesaler;
use App\Models\Retailer;
use App\Models\MillersSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StakeholderController extends Controller
{
    // ১. লিস্ট দেখা এবং সার্চিং (আপনার CustomerController স্টাইলে)
    public function index(Request $request)
    {
        $stakeholders = Stakeholder::when($request->search, function($query) use($request) {
            return $query->whereAny([
                "name",
                "email",
                "phone",
                "role",
                "address"
            ], "LIKE" , "%".$request->search."%" );
        })->orderBy("id", "desc")->paginate(10);

        return view("stakeholder.index", compact("stakeholders"));
    }

    public function create()
    {
        return view("stakeholder.create");
    }

    // ২. ডাটা সেভ লজিক (ট্রানজ্যাকশন সহ যাতে দুই টেবিলে ডাটা ঠিক থাকে)
    public function save(Request $request)
    {
        $request->validate([
            "name"  => "required|min:3",
            "phone" => "required|unique:stakeholders,phone",
            "role"  => "required|in:farmer,miller,wholesaler,retailer",
        ]);

        DB::beginTransaction();
        try {
            // স্টেকহোল্ডার প্রোফাইল সেভ
            $stakeholder = new Stakeholder();
            $stakeholder->name    = $request->name;
            $stakeholder->email   = $request->email;
            $stakeholder->phone   = $request->phone;
            $stakeholder->role    = $request->role;
            $stakeholder->address = $request->address;
            $stakeholder->nid     = $request->nid;
            $stakeholder->save();

            // রোল অনুযায়ী সাব-টেবিলে ডাটা সেভ (আপনার ফিউচার ডিটেইলসের জন্য)
            if ($request->role == 'farmer') {
                $farmer = new Farmer();
                $farmer->stakeholder_id = $stakeholder->id;
                $farmer->land_area      = $request->land_area;
                $farmer->farmer_card_no = $request->farmer_card_no;
                $farmer->save();
            }
            elseif ($request->role == 'miller') {
                $miller = new MillersSupplier();
                $miller->stakeholder_id = $stakeholder->id;
                $miller->factory_license = $request->factory_license;
                $miller->save();
            }
            // Wholesaler ও Retailer এর জন্য একই ভাবে লজিক দিতে পারেন...

            DB::commit();
            return redirect("stakeholder")->with("success", ucfirst($request->role) . " created successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", "Error: " . $e->getMessage());
        }
    }

    // ৩. এডিট লজিক
    public function edit($id)
    {
        // মেইন ডাটা এবং তার সাথে রিলেটেড ডাটা একবারে নিয়ে আসা
        $stakeholder = Stakeholder::find($id);
        return view("stakeholder.edit", compact("stakeholder"));
    }

    // ৪. আপডেট লজিক
    public function update(Request $request, $id)
    {
        $stakeholder = Stakeholder::find($id);
        $stakeholder->name    = $request->name;
        $stakeholder->email   = $request->email;
        $stakeholder->phone   = $request->phone;
        $stakeholder->address = $request->address;
        $stakeholder->update();

        // সাব-টেবিল আপডেট লজিক (যদি প্রয়োজন হয়)
        if ($stakeholder->role == 'farmer') {
            Farmer::where('stakeholder_id', $id)->update([
                'land_area' => $request->land_area,
                'farmer_card_no' => $request->farmer_card_no
            ]);
        }

        return redirect("stakeholder")->with("success", "Stakeholder updated successfully");
    }

    // ৫. ডিলিট লজিক
    public function delete($id)
    {
        $stakeholder = Stakeholder::find($id);

        // যেহেতু আপনি Foreign Key Constraint দেননি, তাই ম্যানুয়ালি সাব-টেবিল ডিলিট করতে হবে
        if ($stakeholder->role == 'farmer') {
            Farmer::where('stakeholder_id', $id)->delete();
        }
        // ... অন্যান্য রোলের জন্য ডিলিট লজিক

        $stakeholder->delete();
        return redirect("stakeholder")->with("success", "Stakeholder deleted");
    }
}
