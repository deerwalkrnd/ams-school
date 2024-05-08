<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Grade;
use App\Models\User;
use App\Http\Requests\SectionRequest;
use Exception;
use Illuminate\Support\Facades\Log;
class SectionController extends Controller
{
    public function index()
    {
        try{$sections = Section::all();
        return view('admin.section.index')->with(compact('sections'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
    public function create()
    {
        try{$sections = Section::all();
        $grades = Grade::all();
        $users = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->get();
        return view('admin.section.create')->with(compact('sections', 'grades', 'users'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
    public function store(SectionRequest $request)
    {
        try{$data = $request->validated();
        $section = Section::create($data);
        return redirect(route('section.index'))->with('success', "Section Successfully Created");
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function edit($id)
    {
        try{$sections = Section::find($id);
        $grades = Grade::all();
        $users = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->get();
        return view('admin.section.edit')->with(compact('sections', 'grades', 'users'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
    public function update(SectionRequest $request, $id)
{
    try{$data = $request->validated();
    $section = Section::find($id);
    $section->update($data);
    return redirect(route('section.index'))->with('success', "Section Updated Successfully");
}catch(Exception $e) {
    Log::error($e->getMessage());  
    return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
}
}

    public function delete($id)
    {
        try{$sections = Section::find($id);
        $sections->delete();
        return redirect(route('section.index'))->with('success', 'Deleted Successfully');
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
}
