<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use App\Models\Attendance;
use App\Models\Old_Attendances;
use App\Models\Old_Students;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use App\Models\Grade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index()
    {
        try {
            $grades = Grade::all();
            return view('admin.grade.index')->with(compact('grades'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }

    public function create()
    {
        try {
            $grades = Grade::all();
            return view('admin.grade.create')->with(compact('grades'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }

    public function store(GradeRequest $request)
    {
        try {
            $data = $request->validated();
            $newTask = Grade::create($data);
            return redirect(route('grade.index'))->with('success', "Grade stored successfully");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }

    public function edit($id)
    {
        try {
            $grades = Grade::find($id);
            return view('admin.grade.edit')->with(compact('grades'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }
    public function update(GradeRequest $request, $id)
    {
        try {
            $grades = Grade::find($id);
            $data = $request->validated();
            $grades->update($data);
            return redirect(route('grade.index'))->with('success', "Grade updated successfully");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('error', 'Oops! Error Occured. Please Try Again Later.');
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try{
        $grades = Grade::find($id);
        $sections = Section::where('grade_id', $grades->id)->get();
        foreach ($sections as $section) {
            $students = Student::where('section_id', $section->id)->get();
            foreach ($students as $student) {
                $attendances = Attendance::where('student_id', $student->id)->get();
                foreach ($attendances as $attendance) {
                    Old_Attendances::create([
                        'student_name' => $student->name,
                        'teacher_name' => $section->user->name,
                        'grade_name' => $grades->name,
                        'section_name' => $section->name,
                        'present' => $attendance->present,
                        'absent' => $attendance->absent,
                        'date' => $attendance->date,
                    ]);
                    $attendance->delete();
                }
                
                Old_Students::create([
                    'roll_no'=>$student->roll_no,
                    'name'=>$student->name,
                    'email'=>$student->email,
                    'section'=>$section->name,
                    'grade'=>$grades->name,
                    'status'=>$student->status,
                ]);
                $student->delete();
            }
            $section->delete();
        }


        $grades->delete();
        DB::commit();
        return redirect(route('grade.index'))->with('success', 'Grade deleted Successfully');
        }catch(Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());  
            return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
        }
    }
}
