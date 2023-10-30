<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        //return view('student.index')->with(compact('students'));      ====> ($students)
        return view('student.index',compact('students')); 
    }

    public function create(){
        $grades=Grade::all();
        $students=Student::all();
        return view('student.create')->with(compact('students','grades'));
    }
    public function store(Request $request){
        //dd($request->all());
        $student = new Student;
        $student->name=trim($request->name);
        $student->email = trim($request->email);
        $student->grade_id=trim($request->grade_id);
        $student->roll_no=trim($request->roll_no);
        dd($student);
        $student->save();
        return redirect('/student')->with('success', "Admin Successfully Created");

    }
    public function edit($id){
        $students=Student::find($id);
        $grades=Grade::all();
        return view('student.edit')->with(compact('students','grades'));

    }
    public function update(Request $request,$id){
        $students=Student::find($id);
        $students->name=$request->input('name');
        $students->email=$request->input('email');
        $students->grade_id=$request->input('grade_id');
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
