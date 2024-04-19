<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Grade;
use App\Models\User;
use App\Http\Requests\SectionRequest;
use Exception;

use Illuminate\Validation\ValidationException;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('admin.section.index')->with(compact('sections'));
    }
    public function create()
    {
        $sections = Section::all();
        $grades = Grade::all();
        $users = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->get();
        return view('admin.section.create')->with(compact('sections', 'grades', 'users'));
    }
    public function store(SectionRequest $request)
{
    $data = $request->validated();

    if (Section::where('user_id', $data['user_id'])->exists()) {
        throw ValidationException::withMessages(['user_id' => 'The selected teacher is already assigned to a section.']);
    }

    $section = Section::create($data);
    return redirect(route('section.index'))->with('success', "Section Successfully Created");
}

    public function edit($id)
    {
        $sections = Section::find($id);
        $grades = Grade::all();
        $users = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->get();
        return view('admin.section.edit')->with(compact('sections', 'grades', 'users'));
    }
    public function update(SectionRequest $request, $id)
{
    $data = $request->validated();
    $section = Section::find($id);
    
    if ($section->user_id != $data['user_id']) {
        if (Section::where('user_id', $data['user_id'])->exists()) {
            throw ValidationException::withMessages(['user_id' => 'The selected teacher is already assigned to a section.']);
        }
    }
    
    $section->update($data);
    return redirect(route('section.index'))->with('success', "Section Updated Successfully");
}

public function delete($id)
{
    try {
        $section = Section::findOrFail($id);
        $section->delete();
        return redirect(route('section.index'))->with('success', 'Deleted Successfully');
    } catch (Exception $e) {
        return redirect(route('section.index'))->with('error', 'Section has related data to students.');
    }
}

}
