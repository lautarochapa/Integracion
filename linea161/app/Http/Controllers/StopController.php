<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stop;

class StopController extends Controller
{
    function getAll(){
        return Stop::all();
    }

    function getOne($id){
        return Stop::find($id);
    }

    function delete($id){
        Stop::findOrFail($id)->delete();
     }

     function add(Request $req){
        $stop = new Stop;
        $stop->name = $req->name;
        $stop->sequence = $req->sequence;
        $stop->branch_id = $req->branch;
        $stop->latitude = $req->latitude;
        $stop->longitude = $req->longitude;

        $stop->save();
        return "Ok";
    }

    function update($id,Request $req){
        $stop = Stop::findOrFail($id);
        $stop->name = $req->name;
        $stop->sequence = $req->sequence;
        $stop->latitude = $req->latitude;
        $stop->longitude = $req->longitude;
        $stop->save();
        return "Ok";
    }

}
