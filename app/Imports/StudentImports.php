<?php

namespace App\Imports;

use App\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImports implements ToCollection,WithHeadingRow,SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    */
    public function collection(Collection $rows){
        foreach ($rows as $row){
            $section = Section::where('name','like', '%'.$row['section'].'%')->first();
            $grade=Grade::where('name','like','%'.$row['grade'].'%')->first();

    }
}
