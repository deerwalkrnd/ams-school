<table class="_table mx-auto mb-5">
    <thead>
        <tr class="table_title">
            <th>Student's Name</th>
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
                <td class="border-end">{{ $student->name }}</td>
                @forelse ($student->getAttendances($startDate??null, $endDate??null) as $dateOfAttendance)
                    <td class="border-end">
                        @if ($dateOfAttendance['present'] > 0)
                            @for ($i = 1; $i <= $dateOfAttendance['present']; $i++)
                                <span class="attendanceSymbol presentSymbol">P</span>
                            @endfor
                        @endif
                        @if ($dateOfAttendance['absent'] > 0)
                            @for ($j = 1; $j <= $dateOfAttendance['absent']; $j++)
                                <span class="attendanceSymbol absentSymbol">A</span>
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
        <tr>
            <td class="border-end"> Total Classes</td>
            <td colspan="{{ $attendanceDates->count() }}">
                {{ $teacher->getTotalClasses($startDate ?? null, $endDate ?? null) }}</td>
        </tr>
    </tfoot>
</table>