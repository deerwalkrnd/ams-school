<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Grade;

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
            'name' => 'required | regex:/^[A-Za-z\s]+$/ | max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $newTask = Grade::create($data);

        return redirect(route('grade.index'))->with('success', "Stored Successfully");
    }

    public function edit($id)
    {
        $grades = Grade::find($id);
        return view('grade.edit', compact('grades'));
    }
    public function update(Request $request, $id)
    {
        $grades = Grade::find($id);
        $data = $request->validate([
            'name' => 'required | regex:/^[A-Za-z\s]+$/ | max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        $grades->update($data);
        return redirect(route('grade.index'))->with('success', "Stored Successfully");
    }

    public function delete($id)
    {
        $grades = Grade::find($id);
        $grades->delete();
        return redirect(route('grade.index'))->with('success', 'Deleted Successfully');
    }
}
