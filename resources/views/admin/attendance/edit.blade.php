@extends('layouts.admin.app')

@section('title', 'Edit Attendance Page')

@section('content')
    <div class="below_header">
        <!-- <h1>Attendance</h1> -->
        @if($user->section->grade)
        <h1>Daily Attendance : Class {{ $user->section->grade->name }}
            - Sec {{ $user->section->name }}</h1>
            {{-- @else
            <h1>Daily Attendance : Class N/A
                - Sec {{ $user->section->name }}</h1> --}}
            @endif
    </div>

    <div class="table_container mt-5">
        <form method="POST" action="{{ route('attendance.update', $user) }}">
            @csrf
            @method('PUT')
            @include('admin.attendance._form')
            <div class="row justify-content-center">
                <div class="justify-content-center text-end my-3 me-5">
                    <button class="btn btn-primary my-2 me-5" id="attendance_submit">Submit</button>
                </div>
            </div>
        </form>

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
                commentBox.focus();
            } else {
                commentBox.disabled = true;
            }
        }

        let submit = document.getElementById("attendance_submit");

        submit.addEventListener("click", function(event) {
            event.preventDefault();
            let student = prepareData();
            $.ajax({
                type: "PUT",
                url: "{{ route('attendance.update', $user) }}",
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
                        window.location.replace("{{ route('attendance.index') }}");
                    }, 3000);
                    submit.prop('disabled', true);
                },

                error: function(data) {
                    Toast.fire({
                        icon: 'error',
                        title: data.msg
                    })
                }
            });
        });


        let addAttendance = document.getElementById('attendance_add');

        addAttendance.addEventListener("click", function(event) {
            event.preventDefault();
            count++;
            if (count <= maxCount) {
                $('.table_title').find('th:last').prev().attr('colspan', count);
                $('table tr').each(function() {
                    let id = $(this).find('td:last').prev().children(".attendance-state").first().attr(
                    'id');
                    $($(this).find('td:last').prev().clone()).insertBefore($(this).find('td:last'));
                    $(this).find('td:last').prev().children(".attendance-state").first().attr('id', id +
                        '_' + count);
                });
            } else {
                count--;
                Toast.fire({
                    icon: 'error',
                    title: 'Cannot add more than maximum class allowed per day'
                })
            }

        });

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
                    student.push({
                        'rollNo': rollNo,
                        'attendanceStatus': studentAttendanceState
                    });
                }

            });

            return student;
        }
    </script>
@endsection
