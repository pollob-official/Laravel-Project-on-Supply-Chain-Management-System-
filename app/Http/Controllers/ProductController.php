<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ১. ইনডেক্স লিস্ট (Advanced Search সহ)
    public function index(Request $request)
    {
        $products = Product::with(['category', 'unit'])
            ->when($request->search, function($query) use($request) {
                return $query->where(function($q) use($request) {
                    $q->where("name", "LIKE", "%" . $request->search . "%")
                      ->orWhere("sku", "LIKE", "%" . $request->search . "%")
                      ->orWhere("product_type", "LIKE", "%" . $request->search . "%");
                });
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.product.index", compact("products"));
    }

    // ২. ট্র্যাশ লিস্ট

     public function trashed()
    {
        $products = Product::onlyTrashed()->with(['category', 'unit'])->orderBy("id", "desc")->paginate(10);
        return view("admin.product.trashed", compact("products"));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        $units = Unit::all();
        return view("admin.product.create", compact('categories', 'units'));
    }

    // ৩. সেভ মেথড (Image handling as per your CustomerController style)
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'category_id' => 'required',
            'sku' => 'required|unique:products,sku',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:2048',
        ]);

        $imgname = null;
        if ($request->hasFile("image")) {
            $slg = Str::slug($request->name);
            $imgname = $slg . "-" . time() . "." . $request->file("image")->extension();
            $request->file("image")->storeAs("photo/product", $imgname, "public");
        }

        Product::create([
            'category_id'    => $request->category_id,
            'unit_id'        => $request->unit_id,
            'name'           => $request->name,
            'sku'            => $request->sku,
            'product_type'   => $request->product_type,
            'purchase_price' => $request->purchase_price ?? 0,
            'sale_price'     => $request->sale_price ?? 0,
            'alert_quantity' => $request->alert_quantity ?? 0,
            'image'          => $imgname,
        ]);

        return redirect("admin/product")->with("success", "Product added to inventory!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', 1)->get();
        $units = Unit::all();
        return view("admin.product.edit", compact("product", "categories", "units"));
    }

    // ৫. আপডেট মেথড
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // পুরাতন ছবি ডিলিট করা (আপনার স্টাইল অনুযায়ী)
            if ($product->image && Storage::disk('public')->exists('photo/product/' . $product->image)) {
                Storage::disk('public')->delete('photo/product/' . $product->image);
            }

            $imgname = Str::slug($request->name) . "-" . time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('photo/product', $imgname, 'public');
            $product->image = $imgname;
        }

        $product->update([
            'category_id'    => $request->category_id,
            'unit_id'        => $request->unit_id,
            'name'           => $request->name,
            'sku'            => $request->sku,
            'product_type'   => $request->product_type,
            'purchase_price' => $request->purchase_price,
            'sale_price'     => $request->sale_price,
            'alert_quantity' => $request->alert_quantity,
        ]);

        return redirect("admin/product")->with("success", "Product updated successfully");
    }

    // ৬. সফট ডিলিট (ফাইল ডিলিট করবেন না, কারণ এটি ট্র্যাশে থাকবে)
    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        return redirect("admin/product")->with("success", "Product moved to trash");
    }

    // ৭. রিস্টোর
    public function restore($id)
    {
        Product::withTrashed()->find($id)->restore();
        return redirect("admin/product")->with("success", "Product restored successfully");
    }

    // ৮. পার্মানেন্ট ডিলিট (ইমেজসহ ডিলিট)
    public function force_delete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->image && Storage::disk('public')->exists("photo/product/" . $product->image)) {
            Storage::disk('public')->delete("photo/product/" . $product->image);
        }

        $product->forceDelete();
        return redirect("admin/product/trashed")->with("success", "Product permanently deleted");
    }
}
