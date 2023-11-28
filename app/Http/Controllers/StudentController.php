<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('student.index')->with(compact('students'));
    }

    public function create(){
        $grades=Grade::all();
        $sections=Section::all();
        $students=Student::all();
        return view('student.create')->with(compact('students','grades','sections'));
    }
    public function store(Request $request){
        // dd($request->all());
        $student = new Student;
        $student->name=trim($request->name);
        $student->email = trim($request->email);
        $student->section_id=trim($request->section_id);
        $student->roll_no=trim($request->roll_no);
        $student->save();
        return redirect('/student')->with('success', "Admin Successfully Created");

    }
    public function edit($id){
        $students=Student::find($id);
        $grades=Grade::all();
        $sections=Section::all();
        return view('student.edit')->with(compact('students','grades','sections'));

    }
    public function update(Request $request,$id){
        // dd($request->all());
        $students=Student::find($id);
        $students->name=$request->input('name');
        $students->email=$request->input('email');
        $students->section_id=$request->input('section_id');
        $students->roll_no=$request->input('roll_no');
        $students->update();
        return redirect('/student')->with('success', "User Updated Successfully ");
    }


    public function delete($id){
        $students=Student::find($id);
        $students->delete();
        return redirect(route('student.index'))->with('status','Deleted Successfully');
    }

}
