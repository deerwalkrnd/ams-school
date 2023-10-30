<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Grade;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;

class SectionController extends Controller
{
   public function index(){
    $sections=Section::all();
    return view('section.index')->with(compact('sections'));
   }
   public function create(){
    $sections=Section::all();
    $grades=Grade::all();
    return view('section.create')->with(compact('grades','sections'));
}
public function store(Request $request){
    $section = new Section;
    $section->name=trim($request->name);
    $section->type=trim($request->type);
    $section->grade_id=trim($request->grade_id);
    $section->save();
    return redirect('/section')->with('success', "Section Successfully Created");
}
public function edit($id){
    $sections=Section::find($id);
    $grades=Grade::all();
    return view('section.edit')->with(compact('sections','grades'));
}
public function update(Request $request,$id){
    $sections=Section::find($id);
    $sections->name=$request->input('name');
    $sections->type=$request->input('type');
    $sections->grade_id=$request->input('grade_id');
    $sections->update();
    return redirect('/section')->with('success', "User Updated Successfully ");
}
public function delete($id){
    $sections=Section::find($id);
    $sections->delete();
    return redirect(route('section.index'))->with('status','Deleted Successfully');
}
}
