<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Carbon\Carbon;
use Exception;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('admin.grade.index')->with(compact('grades'));
    }

    public function create()
    {
        $grades = Grade::all();
        return view('admin.grade.create')->with(compact('grades'));
    }

    public function store(GradeRequest $request)
    {
        $data = $request->validated();
        $newTask = Grade::create($data);
        return redirect(route('grade.index'))->with('success', "Grade stored successfully");
    }

    public function edit($id)
    {
        $grades = Grade::find($id);
        return view('admin.grade.edit')->with(compact('grades'));
    }
    public function update(GradeRequest $request, $id)
{
    try {
        $grade = Grade::findOrFail($id);
        $data = $request->validated();
        $grade->update($data);
        
        return redirect(route('grade.index'))->with('success', "Grade updated successfully");
    } catch (Exception $e) {
        return redirect(route('grade.index'))->with('error', "An error occurred while updating the grade.");
    }
}
//     public function delete($id)
// {
//     try {
//         $grade = Grade::findOrFail($id);

//         $sections = $grade->section;

//         foreach ($sections as $section) {
            
//             $section->student()->delete();
//             $section->forcedelete();
//         }

//         $nextGradeName = (int) $grade->name + 1;
//         $nextGrade = Grade::where('name', $nextGradeName)->first();
//         $newStartDate = Carbon::now()->addWeeks(2);
//             $startDate = Carbon::parse($grade->start_date);
//             $endDate = Carbon::parse($grade->end_date);
    
//             $daysDiff = $startDate->diffInDays($endDate);
//             $newEndDate = $newStartDate->copy()->addDays($daysDiff);
    
//             $grade->start_date = $newStartDate;
//             $grade->end_date = $newEndDate;
        
//         if (!$nextGrade) {
            
//             $grade->name = $nextGradeName;
            
//         }
//         $grade->save();

//         return redirect(route('grade.index'))->with('success', 'Grade deleted Successfully');
//     } catch (Exception $e) {
//         return redirect(route('grade.index'))->with('error', 'An error occurred while deleting the grade.');
//     }
// }

    public function delete($id)
{
    try {
        $grade = Grade::findOrFail($id);

        $sections = $grade->section;

        foreach ($sections as $section) {
            $students = $section->student; 

            foreach ($students as $student) {
                $student->attendances()->delete();
                $student->update(['section_id' => NULL]); 
            }
            $section->forcedelete();
        }

        $newStartDate = Carbon::now()->addWeeks(2);
            $startDate = Carbon::parse($grade->start_date);
            $endDate = Carbon::parse($grade->end_date);
    
            $daysDiff = $startDate->diffInDays($endDate);
            $newEndDate = $newStartDate->copy()->addDays($daysDiff);
    
            $grade->start_date = $newStartDate;
            $grade->end_date = $newEndDate;
        
        $grade->save();

        return redirect(route('grade.index'))->with('success', 'Grade deleted Successfully');
    } catch (Exception $e) {
        dd($e);
        return redirect(route('grade.index'))->with('error', 'An error occurred while deleting the grade.');
    }
}

// public function delete($id)
// {
//     try {
//         $grade = Grade::findOrFail($id);
//         $grade->delete();

//         return redirect(route('grade.index'))->with('success', 'Deleted Successfully');
//     } catch (Exception $e) {
//         return redirect(route('grade.index'))->with('error', 'Grade has related data to other sections.');
//     }
// }

}
