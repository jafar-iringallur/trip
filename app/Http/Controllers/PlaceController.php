<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PlaceController extends Controller
{
   public function index(){
       return view('dashboard.places.index');
   }

   public function getData(Request $request){
    $users = Place::query();

    return DataTables::of($users)->make(true);
}
public function add(Request $request){
    $validator = \Validator::make($request->all(), [
        'name' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ]);
    }
    $user = Place::where('name',$request->name)->first();
    if(isset($user)){
        return response()->json([
            'success' => false,
            'message' => "Already Exist",
        ]);
    }


 
    $sub_user = new Place();
    $sub_user->name = $request->name;
    $sub_user->longitude = $request->longitude;
    $sub_user->latitude = $request->latitude;
    $sub_user->save();
    return response()->json([
        'success' => true,
        'message' => "added",
    ]);
}

public function delete(Request $request){
    $validator = \Validator::make($request->all(), [
        'id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ]);
    }
    $user = Place::where('id',$request->id)->first();
    if(!isset($user)){
        return response()->json([
            'success' => false,
            'message' => "Not found",
        ]);
    }
    $user->delete();
    return response()->json([
        'success' => true,
        'message' => "deleted",
    ]);
}
}
