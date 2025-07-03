<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToCollection, WithHeadingRow
{
    use Importable, SkipsErrors, SkipsFailures;

    public function collection(Collection $rows)
    {
        try {
            DB::beginTransaction();
            $expected_headers = ['roll_no', 'name', 'email', 'grade', 'section'];
            if ($rows->isEmpty()) {
                return redirect()->back()->with('error', 'The Excel file is empty.');
            }

            foreach ($rows as $index => $row) {
                // Check for missing values in the expected columns
                $rowArray = $row->toArray();
                $missing_columns = [];

                foreach ($expected_headers as $header) {
                    if (! isset($rowArray[$header]) || $rowArray[$header] === null || trim($rowArray[$header]) === '') {
                        $missing_columns[] = $header;
                    }
                }

                if (! empty($missing_columns)) {
                    return redirect()->back()->with('error', 'Missing values for columns: '.implode(', ', $missing_columns).' at Excel row index: '.($index + 2));
                }

                $grade = Grade::where('name', $row['grade'])->first();
                $section = Section::where('name', $row['section'])->first();

                // Check if the grade and section exist

                if (! $grade) {
                    return redirect()->back()->with('error', 'Grade not found at row index: '.($index + 2));
                }
                if (! $section) {
                    return redirect()->back()->with('error', 'Section not found at row index: '.($index + 2));
                }

                $validator = Validator::make($rowArray, [
                    'roll_no' => 'required|unique:students,roll_no',
                    'name' => 'required',
                    'email' => ['required', 'email'],
                    'grade' => 'required',
                    'section' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with('error', 'Validation failed at Excel row index: '.($index + 2).' - '.implode(', ', $validator->errors()->all()));
                }

                if ($grade && $section) {
                    $student = new Student();
                    $student->roll_no = $row['roll_no'];
                    $student->name = $row['name'];
                    $student->email = $row['email'];
                    $student->section_id = $section->id;
                    $student->save();
                } else {
                    Log::error('Grade or Section not found at row index: '.($index + 2));
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error importing students: '.$e->getMessage());
        }
    }
}
