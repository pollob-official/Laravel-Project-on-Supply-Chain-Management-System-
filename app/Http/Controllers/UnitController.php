<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    // ১. ইউনিটের তালিকা (Search সহ)
    public function index(Request $request)
    {
        $units = Unit::when($request->search, function($query) use($request) {
                return $query->where("name", "LIKE", "%" . $request->search . "%")
                             ->orWhere("short_name", "LIKE", "%" . $request->search . "%");
            })
            ->orderBy("id", "desc")
            ->paginate(8);

        return view("unit.index", compact("units"));
    }

    // ২. ক্রিয়েট পেজ
    public function create()
    {
        return view("unit.create");
    }

    // ৩. নতুন ইউনিট সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'short_name' => 'required|max:50',
            'base_unit_value' => 'required|numeric',
        ]);

        Unit::create([
            'name'            => $request->name,
            'short_name'      => $request->short_name,
            'base_unit_value' => $request->base_unit_value,
        ]);

        return redirect('unit')->with("success", "Unit created successfully!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view("unit.edit", compact("unit"));
    }

    // ৫. আপডেট মেথড
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'short_name' => 'required|max:50',
            'base_unit_value' => 'required|numeric',
        ]);

        $unit = Unit::findOrFail($id);

        $unit->update([
            'name'            => $request->name,
            'short_name'      => $request->short_name,
            'base_unit_value' => $request->base_unit_value,
        ]);

        return redirect('unit')->with("success", "Unit updated successfully");
    }

    // ৬. সরাসরি ডিলিট
    public function delete($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect('unit')->with("success", "Unit deleted permanently");
    }
}
