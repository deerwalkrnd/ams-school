@extends('layouts.admin.app')


<div class="below_header">
    <h1 class="heading"> Admin Attendance </h1>
    <div class="underline mx-auto hr_line"></div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    </ul>
</div>

<form action= "">
    @csrf
    <div class="row">
        <div class="col-md-6 mt-5">
            <div class="align-items-center">
                <label for="grade" class=" col-md-4 form-label">Grade</label>

                <select id="grade" name="grade" class="col-md-4 form-control form-select  form-select-sm ">
                    <option disabled selected>--Choose Grade--</option>
                    @foreach ($grades as $grade)
                        @foreach ($grade->section as $section)
                            <option value="{{ $section->id }}"> Grade {{ $grade->name }} - Section
                                {{ $section->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row w-100">
        <div class="offset-md-6 col-md-6 mb-3 pe-5 d-flex justify-content-end">
            <button class="btn btn-success px-3 py-2" id="search_submit">Search</button>
        </div>
    </div>
</form>
<hr>
@section('content')
    <!-- table start -->
    <div class="table_container mt-5">

        <form method="POST" action= "{{ route('attendance.store') }}">
            <table class="_table mx-auto">
                <tr class="table_title">
                    <th class="border-end">Roll</th>
                    <th class="border-end">Name</th>
                    <th colspan="{{ $attendanceDates->count()}}" class="text-center border-end">Status</th>

                    @if (!$attendanceDates->has(now()->format('M/d')))
                        <th class="border-end"><i class='bx bxs-down-arrow text-primary'></i></th>
                    @endif
                    <th class="border-end">Absent Comment</th>
                </tr>
                <tr class="table_date">
                    <th colspan="2" class="border-end"></th>
                    @foreach ($attendanceDates as $date => $attendanceDate)
                        <th class="border-end"> {{ $date }}</th>
                    @endforeach

                    @if ($attendanceDates->isEmpty())
                        <th colspan="1"></th>
                    @endif
                    <th colspan="1">
                        @if (!$attendanceDates->has(now()->format('M/d')))
                            {{ date('M/d') }}
                        @endif
                    </th>
                </tr>
                @foreach (auth()->user()->students as $student)
                {{-- {{dd(auth()->user())}} --}}
                <tr>
                    <td class="border-end roll_no">{{ $student->roll_no }}</td>
                    <td class="border-end">{{ $student->name }}</td>

                    @forelse ($student->getAttendances(\Carbon\Carbon::now()->subDays(6), null, 6) as $dateOfAttendance)
                        <td class="border-end">
                            @if ($dateOfAttendance['present'] > 0)
                                <span class="attendanceSymbol presentSymbol">P</span>
                            @endif
                            @if ($dateOfAttendance['absent'] > 0)
                                <span class="attendanceSymbol absentSymbol">A</span>
                            @endif

                        </td>
                    @empty
                        <td class="text-center border-end"> Attendance has not been taken. </td>
                    @endforelse
                    @if (!$attendanceDates->has(now()->format('M/d')))
                        <td class="border-end student_attendance_status">
                            <div onclick="toggleState(this)" class="attendance-state"
                                id="attendance_{{ $student->roll_no }}" data-attendance-state= "1">
                                <img class="attendance_img" src="{{ asset('assets/images/P.svg') }}"
                                    id="r_{{ $student->roll_no }}">
                            </div>
                        </td>
                    @endif
                    <td>
                        <input type="text" name="comment" id="comment{{ $student->roll_no }}" placeholder="Reason:" required
                            disabled>
                    </td>
                </tr>
            @endforeach


            {{-- @include('admin.attendance._form') --}}
            <div class="justify-content-center text-end my-3 me-5">
                <button class="btn btn-success my-2 me-5" id="attendance_submit">Submit</button>
            </div>
    </div>
    </form>

    <!-- table end -->
    </div>
    <!--Container Main end-->
@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });


        let imageLink = {
            0: "A.svg",
            1: "P.svg",
        };

        function toggleState(el) {
            let id = el.id;
            let attendanceState = el.getAttribute("data-attendance-state");
            attendanceState = attendanceState == 1 ? 0 : 1;
            el.setAttribute("data-attendance-state", attendanceState);
            el.children[0].setAttribute("src", "http://" + window.location.host + "/assets/images/" + imageLink[
                attendanceState]);

            //Comment Box Logic Start Here
            let commentBoxId = "comment" + id.split("_")[1];
            let commentBox = document.getElementById(commentBoxId);

            commentBox.disabled = true;
            if (attendanceState == 0) {
                commentBox.disabled = false;
                commentBox.value = '';
                commentBox.focus();
            } else {
                commentBox.disabled = true;
            }
        }


        // Submit Attendance
        let submit = document.getElementById("attendance_submit");
        submit.addEventListener("click", function(event) {
            event.preventDefault();

            Swal.fire({
                title: "Are you Sure?",
                text: "Once submitted, it cannot be changed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Submit it!"
            }).then((result) => {
                // console.log(result.isConfirmed);
                if (result.isConfirmed) {
                    let student = prepareData();
                    // console.log(student);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "{{ route('attendance.store') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "attendances": student,
                        },
                        success: function(data) {
                            Toast.fire({
                                icon: 'success',
                                title: data.msg
                            });
                            setTimeout(() => {
                                window.location.replace(
                                    "{{ route('attendance.create') }}");
                            }, 3000);
                            submit.prop('disabled', true);
                        },
                        error: function() {
                            // console.log("error");
                            Toast.fire({
                                icon: 'error',
                                title: "Sorry Attendance Could not be Submitted. Please Try Again."
                            });
                        },
                    });
                } else if (result.isConfirmed === false) {
                    Swal.fire({
                        icon: "info",
                        title: "Cancelled",
                        text: "Attendance Submission Cancelled"
                    });
                }
            })
        });



        // Prepare Data for Taking Attendance
        function prepareData() {
            var student = new Array();
            $('table tr').each(function() {
                var studentAttendanceState = {
                    'present': 0,
                    'absent': 0,
                    'comment': ''
                };
                let rollNo = $(this).find('td.roll_no').text();

                if (rollNo != "") {
                    let attendanceStates = $(this).find('td.student_attendance_status').each(function() {
                        let attendanceState = $(this).children(".attendance-state").attr(
                            "data-attendance-state");
                        if (attendanceState == 1) {
                            studentAttendanceState.present++;
                        } else if (attendanceState == 0) {
                            studentAttendanceState.absent++;

                            // Retrieve comments if the student is absent
                            let commentBoxId = "comment" + rollNo;
                            let commentBox = document.getElementById(commentBoxId);
                            studentAttendanceState.comment = commentBox.value;
                        }
                    });
                    // console.log(studentAttendanceState);
                    student.push({
                        'rollNo': rollNo,
                        'attendanceStatus': studentAttendanceState,


                    });
                }
            });

            return student;
        }
    </script>
@endsection
