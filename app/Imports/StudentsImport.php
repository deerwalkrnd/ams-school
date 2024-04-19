<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class StudentsImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use Importable,SkipsErrors, SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $grade = Grade::where('name', 'like', '%' . $row['grade'] . '%')->first();
            $section = Section::where([
                ['name', $row['section']],
                ['grade_id', $grade->id]
            ])->first();

            unset($row['grade']);
            unset($row['section']);

            if ($grade != null) {
                $row['grade_id'] = $grade->id;
            }
            if ($section != null) {
                $row['section_id'] = $section->id;
            }

            Validator::make($row->toArray(), [
                'name' => ['required'],
                'email' => ['required','email'],
                'roll_no' => ['required'],
                'section_id' => ['required', 'exists:sections,id'],
                'grade_id' => ['required', 'exists:grades,id']
            ])->validate();

            Student::updateOrCreate(
                ['roll_no' => $row['roll_no']],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'section_id' => $row['section_id'],
                    'grade_id' => $row['grade_id'],
                    'status' => 'active',
                ]
            );


        }
    }

}