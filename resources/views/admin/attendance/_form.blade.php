<table class="_table mx-auto">
    <tr class="table_title">
        <th class="border-end">Roll</th>
        <th class="border-end">Name</th>
        <th class="text-center border-end">Status</th>
        <th class="border-end">Absent Comment</th>

    </tr>
    <tr>
        <th colspan="2" class="border-end"></th>
        <th class="border-end text-center"> {{ date('M/d') }}</th>
        <th colspan="1"></th>
    </tr>
    @foreach ($attendances as $attendance)
        <tr>
            <td class="border-end roll_no">{{ $attendance->student->roll_no }}</td>
            <td class="border-end">{{ $attendance->student->name }}</td>

            @if ($attendance->present > 0)
                <td class="border-end student_attendance_status">
                    <div onclick="toggleState(this)" class="attendance-state"
                        id="attendance_{{ $attendance->student->roll_no }}" data-attendance-state= "1">
                        <img class="attendance_img" src="{{ asset('assets/images/P.svg') }}"
                            id="r_{{ $attendance->student->roll_no }}">
                    </div>
                </td>

            @elseif($attendance->absent > 0)
                <td class="border-end student_attendance_status">
                    <div onclick="toggleState(this)" class="attendance-state"
                        id="attendance_{{ $attendance->student->roll_no }}" data-attendance-state= "0">
                        <img class="attendance_img" src="{{ asset('assets/images/A.svg') }}"
                            id="r_{{ $attendance->student->roll_no }}">
                    </div>
                </td>
            @endif
            @if($attendance->student->status!='dropped_out')
            <td>
                
                <input type="text" name="comment[{{ $attendance->student->roll_no }}]"
                    id="comment{{ $attendance->student->roll_no }}" placeholder="Reason:" {{$attendance->absent == 0 ? "disabled" : ''}}
                    value="{{$attendance->absent > 0 ? $attendance->comment ?? "" : ""}}">
                    
            </td>
            @endif

        </tr>
    @endforeach
</table>
