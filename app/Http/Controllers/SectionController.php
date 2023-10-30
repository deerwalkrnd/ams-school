<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Student;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        //dd($sections);
        return view('section.index',compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        $students = Student::all();
        return view('section.create')->with(compact('sections','students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sections = new Section;
        //dd($request);
        $sections->section_name=trim($request->section_name);
        $sections->section_type = trim($request->section_type);
        $sections->student_id =trim($request->student_id);
        //dd($sections);
        $sections->save();
        return redirect('/section')->with('success', "Section added Successfully");
    }


    /** 
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sections = Section::find($id);
        $students = Student::all();
        return view('section.edit')->with(compact('sections','students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sections = Section::find($id);
        $sections->section_name=$request->input('section_name');
        $sections->section_type=$request->input('section_type');
        $sections->student_id=$request->input('student_id');
        $sections->update();
        return redirect('/section')->with('success','Section Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sections = Section::find($id);
        $sections->delete();
        return redirect(route('section.index'))->with('success','Successfully Deleted!');
    }
}
