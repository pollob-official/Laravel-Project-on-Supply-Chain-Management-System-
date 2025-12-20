<?php

namespace App\Http\Controllers;

use App\Models\Event_type;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
      function index()
    {
        // $event_types=["event_type" => "BirthDay"];
        // print_r(compact('event_types'));

        $event_types = Event_type::all() ;
        return view("event_type.index", ["event_types" => $event_types]);
    }

    function create(){
        return view("event_type.create");
    }

    function edit ($id){
        $event_type = Event_type::find($id);
        return view ("event_type.edit", compact("event_type"));
    }


    function save (Request $request){
        $event_type = new Event_type();
        $event_type->name = $request->name;
        $event_type->save();

        //   echo "saved";
        return redirect("event_type");
    }

     function update(Request $request, $id){
        // print_r($request->all());
        $event_type = Event_type ::find($id);
        $event_type->name = $request->name;


        $event_type->update();
        return redirect("event_type")->with("success", "Event_type updated successfully");
    }

     function delete($id){
        Event_type::findOrFail($id)->delete();
        return redirect("event_type");
    }
}
