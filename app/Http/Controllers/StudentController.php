<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Imports\StudentsImport;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        try {
            $students = Student::all();
            $sections = Section::all();
            $grades = Grade::all();
            return view('admin.student.index')->with(compact('students', 'sections', 'grades'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching students: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function create()
    {
        try {
            $grades = Grade::all();
            $sections = Section::all();
            $students = Student::all();
            return view('admin.student.create')->with(compact('grades', 'sections', 'students'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching grades and sections: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
    public function store(StudentRequest $request)
    {
        try {
            $data = $request->validated();
            $students = Student::create($data);
            return redirect(route('student.index'))->with('success', "Student added Successfully Created");
        } catch (Exception $e) {
            Log::error('Error occurred while storing student: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
    public function edit($id)
    {
        try {
            $students = Student::find($id);
            $grades = Grade::all();
            $sections = Section::all();

            return view('admin.student.edit')->with(compact('students', 'grades', 'sections'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching student for edit: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
    public function update(StudentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $student = Student::find($id);
            $student->update($data);
            return redirect(route('student.index'))->with('success', 'Student Edited Successfully');
        } catch (Exception $e) {
            Log::error('Error occurred while updating student: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function delete($id)
    {
        try {
            $students = Student::find($id);
            $students->delete();
            return redirect(route('student.index'))->with('success', 'Student Deleted Successfully');
        } catch (Exception $e) {
            Log::error('Error occurred while deleting student: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function getBulkUpload()
    {
        return view('admin.student.bulkUpload');
    }
    public function bulkUpload(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'student_csv' => 'required|mimes:csv,xlsx,txt'
            ]);

            $extension = $request->file('student_csv')->extension();
            $fileName = time() . '.' . $extension;
            $path = $request->file('student_csv')->storeAs('public/csv', $fileName);

            $studentImport = new StudentsImport;

            $studentImport->import($path);

            if ($studentImport->failures()->isNotEmpty()) {
                return redirect(route('student.getBulkUpload'))->withFailures($studentImport->failures());
            }
            Storage::delete($path);
            DB::commit();
            return redirect(route('student.index'))->with('success', 'Student Uploaded Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error occurred while bulk uploading students: ' . $e->getMessage());
            return redirect(route('student.getBulkUpload'))->with('error', 'Something went wrong');
        }
    }

    public function bulkSample()
    {
        try {
            $file = public_path('files/sample.xlsx');
            $file_name = "student_bilk_upload_sample.xlsx";
            if (File::exists($file)) {
                return response()->download($file, $file_name);
            } else {
                return redirect(route('student.getBulkUpload'))->with('error', 'File not found');
            }
        } catch (Exception $e) {
            Log::error('Error occurred while downloading bulk sample: ' . $e->getMessage());
            return redirect(route('student.getBulkUpload'))->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
}
