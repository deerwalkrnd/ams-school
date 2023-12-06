@extends('layouts.admin.app')

<body>
    <div class="below_header">
        <h1>Grade</h1>
        @include('layouts.admin.formTabs')
    </div>
    @if (session()->has('success'))
        <div class="alert">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{ route('grade.create') }}">Add a Grade</a>
    <div class="table_container mt-3">
        <table class="_table mx-auto amsTable" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Grade</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $grade->name }}</td>
                        <td>{{ $grade->start_date }}</td>
                        <td>{{ $grade->end_date }}</td>
                        <td class="">
                            <a href="{{ route('grade.edit', ['id' => $grade->id]) }}" class="btn btn-success">Edit</a>
                            <a href="{{ route('grade.delete', ['id' => $grade->id]) }}"
                                class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='5'>No Grades Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>

</html>
