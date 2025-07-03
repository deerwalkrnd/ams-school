<?php

namespace App\Http\Controllers;

use App\Models\ArchivedAttendance;
use App\Models\ArchivedGrade;
use App\Models\ArchivedSection;
use App\Models\ArchivedStudent;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    //
    public function archive($id)
    {
        DB::beginTransaction();

        try {
            $grade = Grade::with('section.student.attendances')->findOrFail($id);

            // Archive Grade
            $archivedGrade = ArchivedGrade::create($grade->only(['name', 'start_date', 'end_date']));

            foreach ($grade->section as $section) {
                // Archive Section
                $archivedSection = ArchivedSection::create([
                    'name' => $section->name,
                    'grade_id' => $archivedGrade->id,
                    'user_id' => $section->user_id,
                ]);

                foreach ($section->student as $student) {
                    // Archive Student
                    $archivedStudent = ArchivedStudent::create([
                        'roll_no' => $student->roll_no,
                        'name' => $student->name,
                        'email' => $student->email,
                        'section_id' => $archivedSection->id,
                        'status' => $student->status,
                    ]);

                    // Archive Attendances
                    foreach ($student->attendances as $attendance) {
                        ArchivedAttendance::create([
                            'student_id' => $archivedStudent->id,
                            'teacher_id' => $attendance->teacher_id,
                            'date' => $attendance->date,
                            'present' => $attendance->present,
                            'absent' => $attendance->absent,
                            'comment' => $attendance->comment,
                        ]);
                    }

                    $student->attendances()->delete();
                    $student->delete();
                }

                $section->delete();
            }

            $grade->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Grade and all related data archived successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Archiving failed: '.$e->getMessage());
        }
    }

    public function show_all_archive()
    {
        try {
            $grades = ArchivedGrade::all();
            $sections = ArchivedSection::all();
            $teachers = User::all();

            $attendances = ArchivedAttendance::with([
                'student:id,name,roll_no,section_id',
                'student.section:id,name,grade_id',
                'student.section.grade:id,name',
                'teacher:id,name',
            ])->latest()->paginate(3000); // Adjust per page limit as needed

            return view('admin.archive.index', compact('attendances', 'grades', 'sections', 'teachers'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch archived data: '.$e->getMessage());
        }
    }
}
