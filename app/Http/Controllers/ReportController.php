<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
