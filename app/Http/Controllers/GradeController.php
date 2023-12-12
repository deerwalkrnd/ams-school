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
        $pageTitle = "Grade List";
        return view('grade.index')->with(compact('grades', 'pageTitle'));
    }

    public function create()
    {
        $grades = Grade::all();
        $pageTitle = "Add New Grade";
        return view('grade.create')->with(compact('grades', 'pageTitle'));
    }

    public function store(GradeRequest $request)
    {
        $data = $request->validated();
        $newTask = Grade::create($data);
        return redirect(route('grade.index'))->with('success', "Stored Successfully");
    }

    public function edit($id)
    {
        $grades = Grade::find($id);
        $pageTitle = "Edit Grade Information";
        return view('grade.edit')->with(compact('grades', 'pageTitle'));
    }
    public function update(GradeRequest $request, $id)
    {
        $grades = Grade::find($id);
        $data = $request->validated();
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
