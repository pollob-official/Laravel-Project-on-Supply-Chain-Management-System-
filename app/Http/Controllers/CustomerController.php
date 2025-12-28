<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{

    function index(Request $request)
    {
        // $customers = Customer::all()->toArray();
        // echo '<pre>';
        // print_r( $customers);
        // echo '</pre>';
        // return view("customer.index", ["customers"=>$customers]);

        //  query builder
        // $customers= DB::table("customers")->get();
        // $customers= DB::select("select * from lav_customers");

        // $customers = Customer::orderBy("id", "desc")->paginate(8);


        $customers = Customer::when($request->search, function($query) use($request) {
          return $query->whereAny([
            "name",
            "email",
             "id",
             "phone"
        ], "LIKE" , "%".$request->search."%" );

        })->orderBy("id", "desc")->paginate(8);




        return view("customer.index", compact("customers"));
    }



    function trashed()
    {
        $customers = Customer::onlyTrashed()->orderBy("id", "desc")->paginate(8);
        return view("customer.trashed", compact("customers"));
    }
    function force_delete($id)
    {
         Customer::withTrashed()->find($id)->forceDelete();
        return redirect("customer/trashed");
    }

    function restore($id)
    {
         Customer::withTrashed()->find($id)->restore();
        return redirect("customer");
    }



    function create()
    {
        return view("customer.create");
    }
    function edit($id)
    {
        $customer = Customer::find($id);
        return view("customer.edit", compact("customer"));
    }


    function save(Request $request)
    {
        // required | email | min | max | numeric | unique | confirmed | nullable

        // print_r($request->all());

        $request->validate(
            [
                "name" => "required|min:3",
                "email" => "email|unique:customers,email",
                'photo' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ],
            [
                "name.required" => "Please give a name"
            ]
        );

        $image = "";
        $imgname = "";
        if ($request->hasFile("photo")) {
            // $image= $request->file("photo")->store("photo/customer", "public");
            $slg=strtolower(str_replace(" ", "-", $request->name));
            $imgname =  $slg . "." . $request->file("photo")->extension();
            $request->file("photo")->storeAs("photo/customer", $imgname, "public");
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->photo = $imgname;
        $customer->save();

        //   echo "saved";
        return redirect("customer")->with("success", "Customer Created successfully");
    }


    function update(Request $request, $id)
    {
        // print_r($request->all());
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        if ($request->hasFile('photo')) {
            if ($customer->photo && Storage::disk('public')->exists('photo/customer/' . $customer->photo)) {
                Storage::disk('public')->delete('photo/customer/' . $customer->photo);
            }
            $imgname = $request->name . '.' . $request->file('photo')->extension();
            $request->file('photo')->storeAs('photo/customer', $imgname, 'public');
            $customer->photo = $imgname;
        }
        $customer->update();
        return redirect("customer")->with("success", "Customer updated successfully");
    }



    function delete($id)
    {
        //  Customer::find($id)->delete();
        $customer = Customer::find($id);

        if ($customer->photo && Storage::disk('public')->exists("/photo/customer/" . $customer->photo)) {
            Storage::disk('public')->delete("/photo/customer/" . $customer->photo);
        }

        $customer->delete();
        return redirect("customer");
    }
}
