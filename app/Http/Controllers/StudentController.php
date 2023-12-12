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
        $sections =Section::all();
        $pageTitle = "Student List";
        return view('student.index')->with(compact('students','sections', 'pageTitle'));
    }

    public function create()
    {
        $grades = Grade::all();
        $sections=Section::all();
        $students = Student::all();
        $pageTitle="Add New Student";
        return view('student.create')->with(compact('grades','sections','students','pageTitle'));
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
        $sections = Section::all();
        return view('student.edit')->with(compact('students', 'grades','sections'));
    }
    public function update(StudentRequest $request, $id)
    {
        $data = $request->validated();
        $student = Student::find($id);
        $student->update($data);
        return redirect(route('student.index'))->with('success', 'Student Edited Successfully');

    }

    public function delete($id)
    {
        $students = Student::find($id);
        $students->delete();
        return redirect(route('student.index'))->with('success', 'Student Deleted Successfully');
    }

    public function bulkUpload(){

        return view('student.bulkUpload');

    }




}
