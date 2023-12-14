<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Grade;
use App\Models\User;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        $pageTitle = "Section List";
        return view('section.index')->with(compact('sections', 'pageTitle'));
    }
    public function create()
    {
        $sections = Section::all();
        $grades = Grade::all();
        $users = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->get();
        $pageTitle = "Add New Section";
        return view('section.create')->with(compact('sections', 'grades', 'users', 'pageTitle'));
    }
    public function store(SectionRequest $request)
    {
        $data = $request->validated();
        $section = Section::create($data);
        return redirect('/section')->with('success', "Section Successfully Created");
    }

    public function edit($id)
    {
        $sections = Section::find($id);
        $grades = Grade::all();
        $users = User::all();
        $pageTitle = "Edit Section Information";
        return view('section.edit')->with(compact('sections', 'grades', 'users', 'pageTitle'));
    }
    public function update(SectionRequest $request, $id)
{
    $data = $request->validated();
    $section = Section::find($id);
    $section->update($data);
    return redirect('/section')->with('success', "Section Updated Successfully");
}

    public function delete($id)
    {
        $sections = Section::find($id);
        $sections->delete();
        return redirect(route('section.index'))->with('status', 'Deleted Successfully');
    }
}
