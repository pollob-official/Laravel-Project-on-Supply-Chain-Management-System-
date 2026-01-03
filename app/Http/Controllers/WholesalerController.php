<?php
namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Wholesaler;
use Illuminate\Http\Request;

class WholesalerController extends Controller {
    public function index(Request $request) {
        $wholesalers = Stakeholder::with('wholesaler')->where('role', 'wholesaler')
            ->when($request->search, function($q) use($request) {
                return $q->where("name", "LIKE", "%$request->search%");
            })->orderBy("id", "desc")->paginate(10);
        return view("wholesaler.index", compact("wholesalers"));
    }

    public function store(Request $request) {
        $stakeholder = Stakeholder::create(array_merge($request->all(), ['role' => 'wholesaler']));
        Wholesaler::create(['stakeholder_id' => $stakeholder->id, 'trade_license' => $request->trade_license]);
        return redirect()->route('wholesaler.index')->with("success", "Created successfully!");
    }

    public function edit($id) {
        $wholesaler = Stakeholder::with('wholesaler')->where('role', 'wholesaler')->findOrFail($id);
        return view("wholesaler.edit", compact("wholesaler"));
    }

    public function update(Request $request, $id) {
        Stakeholder::findOrFail($id)->update($request->all());
        Wholesaler::updateOrCreate(['stakeholder_id' => $id], ['trade_license' => $request->trade_license]);
        return redirect()->route('wholesaler.index')->with("success", "Updated successfully");
    }

    public function delete($id) {
        Stakeholder::findOrFail($id)->delete();
        Wholesaler::where('stakeholder_id', $id)->delete();
        return redirect()->route('wholesaler.index')->with("success", "Moved to Trash");
    }

    public function trashed() {
        $wholesalers = Stakeholder::onlyTrashed()->where('role', 'wholesaler')->paginate(10);
        return view("wholesaler.trashed", compact("wholesalers"));
    }

    public function restore($id) {
        Stakeholder::withTrashed()->where('id', $id)->restore();
        Wholesaler::withTrashed()->where('stakeholder_id', $id)->restore();
        return redirect()->route('wholesaler.index')->with("success", "Restored");
    }

    public function force_delete($id) {
        Wholesaler::withTrashed()->where('stakeholder_id', $id)->forceDelete();
        Stakeholder::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('wholesaler.trashed')->with("success", "Deleted Permanently");
    }
}
