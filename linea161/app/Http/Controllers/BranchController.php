<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;

class BranchController extends Controller
{
    function getAll(){
        return Branch::all();
    }

    function getOne($id){
        return Branch::find($id);
    }



    function getStops($id){
        $branch = Branch::find($id);
        return $branch->stops;
    
    }


    function add(Request $req){
         $branch = new Branch;
         $branch->name = $req->name;
         $branch->frequency = $req->frequency;
         $branch->km_value = $req->km_value;

         $branch->save();
         return "Ok";
     }
     
     function update($id,Request $req){
         $branch = Branch::findOrFail($id);
         $branch->name = $req->name;
         $branch->frequency = $req->frequency;
         $branch->km_value = $req->km_value;
         $branch->save();
         return "Ok";
     }
     function delete($id){
        Branch::findOrFail($id)->delete();
     }
    
    
    
    
}
