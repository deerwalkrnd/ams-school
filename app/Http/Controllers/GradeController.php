<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\Grade;
use Illuminate\Support\Facades\Log;
class GradeController extends Controller
{
    public function index()
    {
        try{$grades = Grade::all();
        return view('admin.grade.index')->with(compact('grades'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function create()
    {
        try{$grades = Grade::all();
        return view('admin.grade.create')->with(compact('grades'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function store(GradeRequest $request)
    {
        try{$data = $request->validated();
        $newTask = Grade::create($data);
        return redirect(route('grade.index'))->with('success', "Grade stored successfully");
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function edit($id)
    {
        try{$grades = Grade::find($id);
        return view('admin.grade.edit')->with(compact('grades'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
    public function update(GradeRequest $request, $id)
    {
        try{$grades = Grade::find($id);
        $data = $request->validated();
        $grades->update($data);
        return redirect(route('grade.index'))->with('success', "Grade updated successfully");
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function delete($id)
    {
        try{$grades = Grade::find($id);
        $grades->delete();
        return redirect(route('grade.index'))->with('success', 'Grade deleted Successfully');
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
}
