@extends('layouts.admin.app')
@section('title')
Admin Attendance
@endsection

@section('content')
<div class="below_header">
    <h1 class="heading"> Admin Attendance </h1>
    <div class="underline mx-auto hr_line"></div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    </ul>
</div>
<form action="{{ route('attendance.takeAttendance') }}">
    <div class="row">
        <div class="col-md-8 row align-items-end">
            <label for="grade" class=" col-sm-2 align-items-end">Grade</label>
            <div class="col-sm-10">
                <select id="section" name="section" class="form-control form-select  form-select-sm">
                    <option disabled selected>--Choose Grade--</option>
                    @foreach ($sections as $section)
                    <option value="{{ $section->id }}" {{ !empty(request('section')) && request('section')==$section->id
                        ? 'selected' : '' }}>
                        Grade {{ $section->grade->name }} - Section
                        {{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-end align-content-end">
            <div>
                <button class="btn btn-success px-3 py-2" id="search_submit">Search</button>
            </div>
        </div>
    </div>
</form>
<hr>

<!-- table start -->
<div class="table_container mt-5">

    @if(isset($students) && $students->isNotEmpty())
    <div id="teacherInfo" data-teacher-id="{{ $students->first()->section->user->id }}"></div>
    @endif

    @if (isset($students))
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <table class="_table mx-auto">
            <thead>
                <tr class="table_title">
                    <th class="border-end">Roll</th>
                    <th class="border-end">Name</th>
                    <th class="border-end"><i class='bx bxs-down-arrow text-primary'></i></th>
                    <th class="border-end">Absent Comment</th>
                </tr>
            </thead>
            <tr class="table_date">
                <th class="border-end"></th>
                <th class="border-end"></th>
                <th class="border-end">
                    {{ date('M/d') }}
                </th>
                <th class="border-end"></th>
            </tr>
            @foreach ($students as $student)
            <tr>
                <td class="border-end roll_no">{{ $student->roll_no }}</td>
                <td class="border-end">{{ $student->name }}</td>

                <td class="border-end student_attendance_status">
                    <div onclick="toggleState(this)" class="attendance-state" id="attendance_{{ $student->roll_no }}"
                        data-attendance-state="1">
                        <img class="attendance_img" src="{{ asset('assets/images/P.svg') }}"
                            id="r_{{ $student->roll_no }}">
                    </div>
                </td>
                <td>
                    <input type="text" name="comment" id="comment{{ $student->roll_no }}" placeholder="Reason:" required
                        disabled>
                </td>
            </tr>
            @endforeach
        </table>

        {{-- @include('admin.attendance._form') --}}
        <div class="justify-content-center text-end my-3 me-5">
            <button class="btn btn-success my-2 me-5" id="attendance_submit">Submit</button>
        </div>
    </form>
    @else
    <div class="shadow py-3 px-1 text-center">
        <h5>Please Select Grade First</h5>
    </div>
    @endif
</div>

<!-- table end -->
<!--Container Main end-->
@endsection
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type='text/javascript'>
    $(document).ready(function() {
            $('#section').select2();

        });
</script>
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
        let teacherId = document.getElementById('teacherInfo')?.getAttribute('data-teacher-id') ?? null;

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
                        url: "{{ route('admin.attendance.store') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "attendances": student,
                            "teacher": teacherId,
                            },
                        success: function(data) {
                            Toast.fire({
                                icon: 'success',
                                title: data.msg
                            });
                            setTimeout(() => {
                                window.location.replace(
                                    "{{ route('attendance.index') }}");
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