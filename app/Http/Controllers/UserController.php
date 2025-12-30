<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request){
    //   $users= User::all();
      $users= User::select("id","name","email")->paginate(15);
      return view("pages.erp.user.index", ["users"=>$users]);

      //   return view("pages.erp.user.index", compact("users"));

   }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view("pages.erp.user.create");
    }


    public function store(Request $request)
    {
       $user= new User();
       $user->name= $request->name;
       $user->email= $request->email;
       $user->password= Hash::make($request->password);
       $user->save();
       return redirect("system/users")->with("success", "User Save
       Successfully");
    }

    /**
     * Display the specified resource.
     */

    // model binding
    // public function show(User $user)
    public function show(string $id)
    {
        $user= User::find($id);
        // $user= User::where("id",$id)->get();
        return view("pages.erp.user.view", compact('user'));
        // print_r($user);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view("pages.erp.user.edit", compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $user= User::findOrFail($id);
       $user->name= $request->name;
       $user->email= $request->email;
       $user->password= Hash::make($request->password);
       $user->save();
       return redirect("system/users")->with("success", "User updated
       Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
         return redirect("system/users")->with("success", "User deleted
        Successfully");
    }

    function trashed()
    {
        $users = User::onlyTrashed()->orderBy("id", "desc")->paginate(8);
        return view("pages.erp.user.trashed", compact("users"));



    }

   function restore($id)
    {
        User::withTrashed()->find($id)->restore();
        return redirect()->route("users.index");
    }

    function force_delete($id)
    {
        User::withTrashed()->find($id)->forceDelete();
        return redirect()->route("user.trashed");
    }
}
