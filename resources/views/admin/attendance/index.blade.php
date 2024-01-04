@extends('layouts.admin.app')

@section('title','Today\'s Attendances')

@section('content')
<div class="below_header">
    <h1>Today's Attendances</h1>
</div>
<div class="col-sm-2">
    <div class="takeAttendancebtn">
        <a class="btn btn-primary" href="{{route('attendance.takeAttendance')}}"> </i> Take  Attendance</a>
    </div>
</div>
@forelse ($users as $user)
<div class="cardclass2">
    <div class="row gx-2 shadow p-3 mb-3 bg-body rounded d-flex justify-content-center">
        <div class="card-icon col-sm-1">
            <i class='bookicon bx bxs-book-reader bx-md'></i>
        </div>

        <div class="col-sm-3">
            <div class="card-body">
                <h5 class="card-title"><b>Grade: {{ ucfirst($user->section->grade->name) }}</b></h5><br>
                <h5 class="card-title"><b>Section: {{ ucfirst($user->section->name) }}</b></h5>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="takeAttendancebtn">
                <a class="btn btn-primary" href="{{route('attendance.edit', $user->id)}}"> </i> Edit Attendance</a>

            </div>

        </div>

        <div class="col-sm-2">
            <div class="card-present d-flex">
                <span class="present">
                    <h5><b>Present :</b></h5>
                </span>
                <span class="presentnum ps-2">
                    <h5><b>{{$user->section->getPresentCount($user)}}</b></h5>
                </span>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="card-absent d-flex">
                <span class="absent">
                    <h5><b>Absent :</b></h5>
                </span>
                <span class="absentnum ps-2">
                    <h5><b>{{$user->section->getAbsentCount($user)}}</b></h5>
                </span>
            </div>
        </div>


    </div>


</div>

@empty
<div class="cardclass2" >
    <div class="row gx-2 shadow p-3 mb-3 bg-body rounded d-flex justify-content-center">

        No Any Attendances Has Been Taken Today.

    </div>
</div>

@endforelse


@endsection
