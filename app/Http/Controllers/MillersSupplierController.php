<?php
namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\MillersSupplier;
use Illuminate\Http\Request;

class MillersSupplierController extends Controller {
    public function index(Request $request) {
        $millers = Stakeholder::with('miller')->where('role', 'miller')->orderBy("id", "desc")->paginate(10);
        return view("miller.index", compact("millers"));
    }

    public function store(Request $request) {
        $stakeholder = Stakeholder::create(array_merge($request->all(), ['role' => 'miller']));
        MillersSupplier::create(['stakeholder_id' => $stakeholder->id, 'factory_license' => $request->factory_license]);
        return redirect()->route('miller.index')->with("success", "Miller Created!");
    }

    public function edit($id) {
        $miller = Stakeholder::with('miller')->where('role', 'miller')->findOrFail($id);
        return view("miller.edit", compact("miller"));
    }

    public function update(Request $request, $id) {
        Stakeholder::findOrFail($id)->update($request->all());
        MillersSupplier::updateOrCreate(['stakeholder_id' => $id], ['factory_license' => $request->factory_license]);
        return redirect()->route('miller.index')->with("success", "Updated successfully");
    }

    public function delete($id) {
        Stakeholder::findOrFail($id)->delete();
        MillersSupplier::where('stakeholder_id', $id)->delete();
        return redirect()->route('miller.index')->with("success", "Moved to Trash");
    }

    public function trashed() {
        $millers = Stakeholder::onlyTrashed()->where('role', 'miller')->paginate(10);
        return view("miller.trashed", compact("millers"));
    }

    public function restore($id) {
        Stakeholder::withTrashed()->where('id', $id)->restore();
        MillersSupplier::withTrashed()->where('stakeholder_id', $id)->restore();
        return redirect()->route('miller.index')->with("success", "Restored");
    }

    public function force_delete($id) {
        MillersSupplier::withTrashed()->where('stakeholder_id', $id)->forceDelete();
        Stakeholder::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('miller.trashed')->with("success", "Deleted Permanently");
    }
}
