<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Section;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('grade.index')->with(compact('grades'));
    }

    public function create()
    {
        $grades = Grade::all();
        return view('grade.create')->with(compact('grades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);

        $newTask = Grade::create($data);
        return redirect(route('grade.index'));
    }


    public function edit($id)
    {
        $grades = Grade::find($id);
        return view('grade.edit', compact('grades'));
    }
    public function update(Request $request,$id){
        $grades=Grade::find($id);
        $grades->name=$request->input('name');
        $grades->start_date=$request->input('start_date');
        $grades->end_date=$request->input('end_date');
        $grades->update();
        return redirect(route('grade.index'))->with('status',"Stored Successfully");

    }

    public function delete($id){
        $grades=Grade::find($id);
        $grades->delete();
        return redirect(route('grade.index'))->with('status','Deleted Successfully');
    }
}
