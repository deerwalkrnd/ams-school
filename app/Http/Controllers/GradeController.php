<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use Illuminate\Http\Request;
use App\Models\Grade;

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
        $grades = Grade::find($id);
        $data = $request->validated();
        $grades->update($data);
        return redirect(route('grade.index'))->with('success', "Grade updated successfully");
    }

    public function delete($id)
    {
        $grades = Grade::find($id);
        $grades->delete();
        return redirect(route('grade.index'))->with('success', 'Grade deleted Successfully');
    }
}
