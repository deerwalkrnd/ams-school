@extends('layouts.teacher.app')

@section('title', 'Attendance Report')
@if (auth()->user()->section)
    @section('content')
        <div class="below_header">
            <h1>Attendance Report</h1>

        </div>
        <form action="{{ route('teacher-report.search') }}">

            <div class="row align-items-center">
                <div class="col-md-6 mt-5">
                    <div class="align-items-center">
                        <label for="student" class=" col-md-4 form-label">Student</label>
                        <select id="student" name="student" class="col-md-4 form-control form-select  form-select-sm ">
                            <option disabled selected>--Choose Student--</option>
                            @foreach ($allStudents as $student)
                                <option value="{{ $student->roll_no }}"> {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 mt-5">
                    <div class="row align-items-center">
                        <label for="start_date" class="col form-label"> Start Date</label>
                        <input id="start_date" name="start_date" type="date" class="col-md-4 form-control"
                            onchange="evaluateDate()">
                    </div>
                </div>
                <div class="col-md-3 mt-5">
                    <div class="row align-items-center">
                        <label for="end_date" class="col form-label"> End Date</label>
                        <input id="end_date" name="end_date" type="date" class="col-md-4 form-control"
                            onchange="evaluateDate()">
                    </div>
                </div>
            </div>
            <div class="offset-md-6 col-md-6 my-5 pe-5 d-flex justify-content-end">
                <button class="btn btn-success px-3 py-2" id="search_submit">Search</button>
            </div>
        </form>
        <form action="{{ route('teacher-report.download') }}" method="POST">
            @csrf
            <input type="hidden" id="studentDownload" name="student">
            <input type="hidden" id="startDateDownload" name="start_date">
            <input type="hidden" id="endDateDownload" name="end_date">
            <div class="offset-md-6 col-md-6 mb-5 pe-5 d-flex justify-content-end">
                <button class="btn btn-success px-3 py-2" id="download_submit">
                    <i class="fas fa-file-download me-2"></i>Download
                </button>
            </div>
        </form>
        <table class="_table mx-auto mb-5">
            <thead>
                <tr class="table_title">
                    <th class="border-end fw-bolder">Student's Name</th>
                    @forelse ($attendanceDates as $date)
                        <th class="text-center border-end">
                            {{ $date }}
                        </th>
                    @empty
                        <td colspan="3" class="text-center">
                            <h5>
                                No Attendance Taken
                            </h5>
                        </td>
                    @endforelse
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        @if ($student->status == 'active')
                            <td class="border-end">{{ $student->name }}</td>
                        @endif
                        @forelse ($student->getAttendances($startDate??null, $endDate??null) as $dateOfAttendance)
                            <td class="border-end text-center">
                                @if ($dateOfAttendance['present'] > 0)
                                    @for ($i = 1; $i <= $dateOfAttendance['present']; $i++)
                                        <span class="attendanceSymbol presentSymbol text-success">P</span>
                                    @endfor
                                @endif
                                @if ($dateOfAttendance['absent'] > 0)
                                    @for ($j = 1; $j <= $dateOfAttendance['absent']; $j++)
                                        <span class="attendanceSymbol absentSymbol text-danger">A</span>
                                    @endfor
                                @endif

                            </td>
                        @empty
                            <td class="text-center border-end"> Attendance has not been taken. </td>
                        @endforelse
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total_class">
                    <td class="border-end fw-bolder "> Total Classes</td>
                    <td class="border-end fw-bolder text-center" colspan="{{ $attendanceDates->count() }}">
                        {{ auth()->user()->getTotalClasses($startDate ?? null, $endDate ?? null) }}</td>
                </tr>
            </tfoot>
        </table>
    @endsection
@else
    @section('content')
        <h5 class="text-center">
            <div class="shadow p-3 mb-5 bg-body rounded">
                No Batch Assigned to Teacher
            </div>
        </h5>
    @endsection
@endif
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#student').select2();

        });
    </script>
    {{-- Script To Update subject,student and dates on change --}}
    <script>
        $(document).ready(function() {
            //get the parameters from the browser url
            var student = getUrlParameter('student');
            var startDate = getUrlParameter('start_date');
            var endDate = getUrlParameter('end_date');
            //set the end and start date max to today
            let endDateInput = document.getElementById('end_date');
            let startDateInput = document.getElementById('start_date');
            endDateInput.max = new Date().toISOString().split("T")[0];
            startDateInput.max = new Date().toISOString().split("T")[0];
            //set the hidden inputs of download form
            document.getElementById('studentDownload').value = student;
            document.getElementById('startDateDownload').value = startDate;
            document.getElementById('endDateDownload').value = endDate;


            if (student) {
                $('#student').val(student).trigger('change');
            }

            if (startDate) {
                $('#start_date').val(startDate);
            }
            if (endDate) {
                $('#end_date').val(endDate);
            }

        });

        $('#student').change(function() {
            //set the hidden value
            $('#studentDownload').val($(this).val());
        });


        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };
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
        let endDateInput = document.getElementById('end_date');
        let startDateInput = document.getElementById('start_date');
        let submitBtn = document.getElementById('search_submit');

        function evaluateDate() {
            let endDateInputVal = endDateInput.value;
            let startDateInputVal = startDateInput.value;
            //set the hiddent input values
            document.getElementById('startDateDownload').value = startDateInputVal;
            document.getElementById('endDateDownload').value = endDateInputVal;

            if (endDateInputVal < startDateInputVal && endDateInputVal) {
                Toast.fire({
                    icon: 'error',
                    title: 'End date should be later than start date'
                })

                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }
    </script>
@endsection
