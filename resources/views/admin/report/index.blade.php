@extends('layouts.admin.app')

@section('title', 'Attendance Report')

@section('content')
    <div class="below_header">
        <h1 class="heading">Attendance Report</h1>
    </div>
    <hr class="">
    <form action="{{ route('report.search') }}">
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
            <div class="col-md-6 mt-5">
                <div class="align-items-center">
                    <label for="student" class=" col-md-4 form-label">Student</label>

                    <select id="student" name="student" class="col-md-4 form-control form-select  form-select-sm ">
                        <option disabled selected>--Choose Student--</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 my-5">
                <div class="row align-items-center">
                    <label for="start_date" class="col form-label"> Start Date</label>
                    <input id="start_date" name="start_date" type="date" class="col form-control form-control-sm"
                        onchange="evaluateDate()">
                </div>
            </div>
            <div class="col-md-3 my-5">
                <div class="row align-items-center">
                    <label for="end_date" class="col form-label"> End Date</label>
                    <input id="end_date" name="end_date" type="date" class="col form-control form-control-sm"
                        onchange="evaluateDate()">
                </div>
            </div>
        </div>
        <div class="row w-100">
            <div class="offset-md-6 col-md-6 mb-3 pe-5 d-flex justify-content-end">
                <button class="btn btn-success px-3 py-2" id="search_submit">Search</button>

            </div>
        </div>
    </form>
    <form action="{{ route('admin-report.download') }}" method="POST" id="reportDownloadForm">
        @csrf
        <input type="hidden" id="gradeDownload" name="grade">
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
            @php
            $firstStudent = $students->first();
            $attendanceDates = $firstStudent->getAttendanceDates($startDate, $endDate ?? Date::now());
        @endphp
        
        <tr class="table_title">
            <th>Student's Name</th>
            @if($attendanceDates->isNotEmpty())
                @foreach ($attendanceDates as $date)
                    <th colspan="2" class="text-center border-end">
                        {{ $date }}
                    </th>
                @endforeach
            @else
                <td colspan="3" class="text-center">
                    <h5>No Attendance Taken</h5>
                </td>
            @endif
        </tr>
        
        </thead>
        <tbody>
            @foreach ($students as $student)
            @if($student->status=='active')
                <tr>
                    <td class="border-end">{{ $student->name }}</td>
                    @forelse ($student->getAttendances($startDate, $endDate??Date::now()) as $dateOfAttendance)
                    {{-- {{dd($dateOfAttendance)}} --}}
                        <td class="border-end text-center " colspan="2">
                            @if ($dateOfAttendance['present'] > 0)
                                @for ($i = 1; $i <= $dateOfAttendance['present']; $i++)
                                    <span class="attendanceSymbol presentSymbol ">P</span>
                                @endfor
                            @endif
                            @if ($dateOfAttendance['absent'] > 0)
                                @for ($j = 1; $j <= $dateOfAttendance['absent']; $j++)
                                    <span class="attendanceSymbol absentSymbol">A</span>
                                @endfor
                            @endif
                            @if($dateOfAttendance['present']<=0 && $dateOfAttendance['absent']<=0)
                            <span class="attendanceSymbol absentSymbol">dasfk</span>
                            @endif
                        </td>
                    @empty
                        <td class="text-center border-end"> Attendance has not been taken. </td>
                    @endforelse
                </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total_class fw-bolder">
                <td class="border-end "> Total Classes</td>
                <td colspan="{{ $attendanceDates->count() }}" class="border-end text-center fw-bolder">
                    {{ $section->getTotalClasses($startDate ?? null, $endDate ?? null) }}</td>
            </tr>
        </tfoot>
    </table>

@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#grade').select2();
            $('#student').select2();

        });
    </script>
    {{-- Script To Update subject,student and dates on change --}}
    <script>
        $(document).ready(function() {
            //get the parameters from the browser url
            var grade = getUrlParameter('grade');
            var student = getUrlParameter('student');
            var startDate = getUrlParameter('start_date');
            var endDate = getUrlParameter('end_date');
            //set the end and start date max to today
            let endDateInput = document.getElementById('end_date');
            let startDateInput = document.getElementById('start_date');
            endDateInput.max = new Date().toISOString().split("T")[0];
            startDateInput.max = new Date().toISOString().split("T")[0];
            //set the hidden inputs of download form
            document.getElementById('gradeDownload').value = grade;
            document.getElementById('studentDownload').value = student;
            document.getElementById('startDateDownload').value = startDate;
            document.getElementById('endDateDownload').value = endDate;

            if (grade) {
                $('#grade').val(grade).trigger('change');
                gradeChange(grade).then(() => {
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
            }
        });

        $('#grade').change(function() {
            //first run ajax
            gradeChange($(this).val());
            //then set the hidden value
            $('#gradeDownload').val($(this).val());
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

        async function fetchGrade(gradeValue) {
            let result;
            try {
                result = await $.ajax({
                    url: "{{ route('report.gradeSearch') }}",
                    data: {
                        grade: gradeValue
                    },
                    datatype: 'json',
                });
            } catch (error) {
                console.log(error);
            }
            return result;
        }

        async function gradeChange(gradeValue) {
            $('#student').empty();
            var $student = $('#student');

            await fetchGrade(gradeValue)
                .then((data) => {

                    if (jQuery.isEmptyObject(data.students)) {
                        $student.html('<option selected disabled>---No Student assigned to the Grade---</option>')

                    } else {
                        $student.html('<option selected disabled>---Choose Student---</option>');
                        $.each(data.students, function(id, value) {
                            $student.append('<option value="' + id + '">' + value + '</option>');
                        });
                    }
                    $('#start_date').attr({
                        "min": data.start_date
                    });

                });

            $('#subject').val("");
            $('#student').val("");
        }
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

        function evaluateAttendanceFilter() {
            let attendanceFilter = document.getElementById('attendanceFilter');
            let grade = document.getElementById('grade');
            attendanceFilterVal = attendanceFilter.value;
            gradeVal = grade.selectedIndex;
            console.log(attendanceFilterVal);
            if (gradeVal <= 0 && attendanceFilterVal) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please select a grade to filter students.'
                });

                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }

        function downloadReport() {
            $('#reportDownloadForm').submit();
        }
    </script>
@endsection