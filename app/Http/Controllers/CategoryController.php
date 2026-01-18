<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ১. ক্যাটাগরি তালিকা (Search সহ)
    public function index(Request $request)
    {
        $categories = Category::when($request->search, function($query) use($request) {
                return $query->where("name", "LIKE", "%" . $request->search . "%")
                             ->orWhere("slug", "LIKE", "%" . $request->search . "%");
            })
            ->orderBy("id", "desc")
            ->paginate(5);

        return view("admin.category.index", compact("categories"));
    }

    // ২. ক্রিয়েট পেজ
    public function create()
    {
        return view("admin.category.create");
    }

    // ৩. নতুন ক্যাটাগরি সেভ করা
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Category::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name), // নাম থেকে অটো স্ল্যাগ তৈরি হবে
            'description' => $request->description,
            'is_active'   => $request->is_active ?? 1,
        ]);

        return redirect('admin/category')->with("success", "Category created successfully!");
    }

    // ৪. এডিট পেজ
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view("admin.category.edit", compact("category"));
    }

    // ৫. আপডেট মেথড
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => $request->is_active,
        ]);

        return redirect('admin/category')->with("success", "Category updated successfully");
    }

    // ৬. সরাসরি ডিলিট (যেহেতু Soft Delete নেই)
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect('admin/category')->with("success", "Category deleted permanently");
    }
}
