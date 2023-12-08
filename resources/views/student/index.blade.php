<x-main-layout>
    <a href="{{ route('student.create') }}">Add a Student</a>
    <div class="table_container mt-3">
        <table class="_table mx-auto amsTable" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Student's Name</th>
                    <th>Roll Number</th>
                    <th>Email Address</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->roll_no }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->section->name }}</td>
                        <td>{{ $student->status }}</td>
                        <td class="">
                            <a href="{{ route('student.edit', ['id' => $student->id]) }}" class="btn btn-success">Edit</a>
                            <a href="{{ route('student.delete', ['id' => $student->id]) }}"
                                class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='4'>No Teachers Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-main-layout>
