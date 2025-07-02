<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use Illuminate\Http\Request;
use App\Models\Grade;
use Exception;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function index()
    {
        try {
            $grades = Grade::all();
            return view('admin.grade.index')->with(compact('grades'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching grades: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function create()
    {
        try {
            $grades = Grade::all();
            return view('admin.grade.create')->with(compact('grades'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching grades for creation: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function store(GradeRequest $request)
    {
        try {
            $data = $request->validated();
            $newTask = Grade::create($data);
            return redirect(route('grade.index'))->with('success', "Grade stored successfully");
        } catch (Exception $e) {
            Log::error('Error occurred while storing grade: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function edit($id)
    {
        try {
            $grades = Grade::find($id);
            return view('admin.grade.edit')->with(compact('grades'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching grade for edit: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
    public function update(GradeRequest $request, $id)
    {
        try {
            $grades = Grade::find($id);
            $data = $request->validated();
            $grades->update($data);
            return redirect(route('grade.index'))->with('success', "Grade updated successfully");
        } catch (Exception $e) {
            Log::error('Error occurred while updating grade: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function delete($id)
    {
        try {
            $grades = Grade::find($id);
            $grades->delete();
            return redirect(route('grade.index'))->with('success', 'Grade deleted Successfully');
        } catch (Exception $e) {
            Log::error('Error occurred while deleting grade: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
}
