@extends('layouts.teacher.app')

@section('title', 'Dashboard')

@section('content')

    @include('teacher.dashboard.midsection')
    <!-- upper cards section -->

    @isset($section)
        <div class="cardclass2">
            <div class="row gx-2 shadow p-3 mb-3 bg-body rounded d-flex justify-content-center">
                <div class="card-icon col-sm-1">
                    <i class='bookicon bx bxs-book-reader bx-md'></i>
                </div>

                <div class="col-sm-3">
                    <div class="card-body">
                        <h5 class="card-title"><b>Grade: {{ ucfirst($section->grade->name) }}</b></h5><br>
                        <h5 class="card-title"><b>Section: {{ ucfirst($section->name) }}</b></h5>

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="takeAttendancebtn">
                        <a class="btn btn-success" href="{{route('attendance.create')}}"> </i> Take Attendance</a>

                    </div>

                </div>

                <div class="col-sm-2">
                    <div class="card-present d-flex">
                        <span class="present">
                            <h5><b>Present :</b></h5>
                        </span>
                        <span class="presentnum ps-2">
                            <h5><b>{{auth()->user()->section->getPresentCount(auth()->user())}}</b></h5>
                        </span>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="card-absent d-flex">
                        <span class="absent">
                            <h5><b>Absent :</b></h5>
                        </span>
                        <span class="absentnum ps-2">
                            <h5><b>{{auth()->user()->section->getAbsentCount(auth()->user())}}</b></h5>
                        </span>
                    </div>
                </div>


            </div>


        </div>
    @else
        <div class="cardclass2">
            <div class="row gx-2 shadow p-3 mb-3 bg-body rounded d-flex justify-content-center">

                You have not been assigned to any grade.

            </div>
        </div>
    @endisset

@endsection
