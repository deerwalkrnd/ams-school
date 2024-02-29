<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Imports\StudentsImport;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;



class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        $sections = Section::all();
        $grades = Grade::all();
        return view('admin.student.index')->with(compact('students', 'sections', 'grades'));
    }

    public function create()
    {
        $grades = Grade::all();
        $sections = Section::all();
        $students = Student::all();
        return view('admin.student.create')->with(compact('grades', 'sections', 'students'));
    }
    public function store(StudentRequest $request)
    {
        $data = $request->validated();
        $students = Student::create($data);
        return redirect(route('student.index'))->with('success', "Student Successfully Created");
    }
    public function edit($id)
    {
        $students = Student::find($id);
        $grades = Grade::all();
        $sections = Section::all();

        return view('admin.student.edit')->with(compact('students', 'grades', 'sections'));
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

    public function getBulkUpload()
    {
        return view('admin.student.bulkUpload');
    }
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'student_csv' => 'required|mimes:csv,xlsx,txt'
        ]);

        $extension =$request->file('student_csv')->extension();
        $fileName=time().'.'.$extension;
        $path=$request->file('student_csv')->storeAs('public/csv',$fileName);

        $studentImport = new StudentsImport;

        $studentImport->import($path);

        if($studentImport->failures()->isNotEmpty()){
            return redirect(route('student.getBulkUpload'))->withFailures($studentImport->failures());
        }
        Storage::delete($path);
        return redirect(route('student.index'))->with('success', 'Student Uploaded Successfully');

    }

    public function bulkSample(){
          $file = public_path('files/sample.xlsx');
          return response()->download($file);
    }
}
