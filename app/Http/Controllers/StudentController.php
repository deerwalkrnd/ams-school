<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('student.index')->with(compact('students'));
    }

    public function create()
    {
        $grades = Grade::all();
        $students = Student::all();
        return view('student.create')->with(compact('students', 'grades'));
    }
    public function store(StudentRequest $request)
    {
        $data = $request->validated();
        $students = Student::create($data);
        return redirect('/student')->with('success', "Student added Successfully Created");
    }
    public function edit($id)
    {
        $students = Student::find($id);
        $grades = Grade::all();
        return view('student.edit')->with(compact('students', 'grades'));
    }
    public function update(StudentRequest $request, $id)
    {
        $data = $request->validated();
        $student = Student::find($id);
        if (!$student) {
            return redirect('/student')->with('error', "Student not found");
        }
        $student->update($data);
  
    }



    public function delete($id)
    {
        $students = Student::find($id);
        $students->delete();
        return redirect(route('student.index'))->with('success', 'Student Deleted Successfully');
    }
}
