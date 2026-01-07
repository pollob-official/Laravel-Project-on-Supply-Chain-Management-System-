<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\MillersSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MillersSupplierController extends Controller
{
    // ১. মিলারদের তালিকা (Search সহ)
    public function index(Request $request)
    {
        $millers = Stakeholder::with('miller') // স্টেকহোল্ডার মডেলে রিলেশন নাম 'miller' হলে
            ->where('role', 'miller')
            ->when($request->search, function($query) use($request) {
                return $query->where(function($q) use($request) {
                    $q->where("name", "LIKE", "%" . $request->search . "%")
                      ->orWhere("phone", "LIKE", "%" . $request->search . "%")
                      ->orWhere("nid", "LIKE", "%" . $request->search . "%");
                });
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.miller.index", compact("millers"));
    }

    // ২. ট্র্যাশ লিস্ট
    public function trashed()
    {
        $millers = Stakeholder::onlyTrashed()
            ->where('role', 'miller')
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("admin.miller.trashed", compact("millers"));
    }

    public function create()
    {
        return view("admin.miller.create");
    }

    // ৩. নতুন মিলার সেভ করা
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $stakeholder = Stakeholder::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'role'    => 'miller',
                'address' => $request->address,
                'nid'     => $request->nid,
            ]);

            MillersSupplier::create([
                'stakeholder_id'    => $stakeholder->id,
                'factory_license'   => $request->factory_license,
                'milling_capacity'  => $request->milling_capacity,
                'storage_unit_type' => $request->storage_unit_type,
            ]);
        });

        return redirect('miller')->with("success", "Miller profile created successfully!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $miller = Stakeholder::with('miller')->where('role', 'miller')->findOrFail($id);
        return view("admin.miller.edit", compact("miller"));
    }

    // ৫. আপডেট মেথড
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

            MillersSupplier::updateOrCreate(
                ['stakeholder_id' => $id],
                [
                    'factory_license'   => $request->factory_license,
                    'milling_capacity'  => $request->milling_capacity,
                    'storage_unit_type' => $request->storage_unit_type,
                ]
            );
        });

        return redirect('miller')->with("success", "Miller profile updated successfully");
    }

    // ৬. সফট ডিলিট
    public function delete($id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $stakeholder->delete();

        // সাব-টেবিল সফট ডিলিট
        MillersSupplier::where('stakeholder_id', $id)->delete();

        return redirect('miller')->with("success", "Miller moved to trash");
    }

    // ৭. রিস্টোর করা
    public function restore($id)
    {
        Stakeholder::withTrashed()->where('id', $id)->restore();
        MillersSupplier::withTrashed()->where('stakeholder_id', $id)->restore();

        return redirect('miller')->with("success", "Miller profile restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট
    public function force_delete($id)
    {
        MillersSupplier::withTrashed()->where('stakeholder_id', $id)->forceDelete();
        Stakeholder::withTrashed()->findOrFail($id)->forceDelete();

        return redirect('miller/trashed')->with("success", "Miller permanently deleted");
    }
}
