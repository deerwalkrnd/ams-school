<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
    
            Student::create([
                'name' => $row['name'],
                'roll_no' => $row['roll_no'],
                'email' => $row['email'],
                'section_id' => $row['section_id'],
                'status' => 'active',
            ]);
        }
    }
}
