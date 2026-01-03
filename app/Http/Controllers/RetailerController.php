<?php
namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Retailer;
use Illuminate\Http\Request;

class RetailerController extends Controller {
    public function index(Request $request) {
        $retailers = Stakeholder::with('retailer')->where('role', 'retailer')->orderBy("id", "desc")->paginate(10);
        return view("retailer.index", compact("retailers"));
    }

    public function store(Request $request) {
        $stakeholder = Stakeholder::create(array_merge($request->all(), ['role' => 'retailer']));
        Retailer::create(['stakeholder_id' => $stakeholder->id, 'shop_name' => $request->shop_name]);
        return redirect()->route('retailer.index')->with("success", "Retailer Created!");
    }

    public function edit($id) {
        $retailer = Stakeholder::with('retailer')->where('role', 'retailer')->findOrFail($id);
        return view("retailer.edit", compact("retailer"));
    }

    public function update(Request $request, $id) {
        Stakeholder::findOrFail($id)->update($request->all());
        Retailer::updateOrCreate(['stakeholder_id' => $id], ['shop_name' => $request->shop_name]);
        return redirect()->route('retailer.index')->with("success", "Updated successfully");
    }

    public function delete($id) {
        Stakeholder::findOrFail($id)->delete();
        Retailer::where('stakeholder_id', $id)->delete();
        return redirect()->route('retailer.index')->with("success", "Moved to Trash");
    }

    public function trashed() {
        $retailers = Stakeholder::onlyTrashed()->where('role', 'retailer')->paginate(10);
        return view("retailer.trashed", compact("retailers"));
    }

    public function restore($id) {
        Stakeholder::withTrashed()->where('id', $id)->restore();
        Retailer::withTrashed()->where('stakeholder_id', $id)->restore();
        return redirect()->route('retailer.index')->with("success", "Restored");
    }

    public function force_delete($id) {
        Retailer::withTrashed()->where('stakeholder_id', $id)->forceDelete();
        Stakeholder::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('retailer.trashed')->with("success", "Deleted Permanently");
    }
}
