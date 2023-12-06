<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Page of Grade</title>
</head>

<body>
    @if (session()->has('success'))
        <div class="alert">
            {{ session()->get('success') }}
        </div>
    @else
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif
    @endif
    <section class="bulkbtn col-md-3">
        <a href="{{route('student.bulkUpload')}}">
            <button class="btn btn-primary">
                <i class='bx bx-add-to-queue'></i>
                    Bulk Upload
            </button>
        </a>
    </section>
        <table border="1px">
            <a href="{{ route('student.create') }}">Add a Students</a>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Roll Number</th>
                    <th>email</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->roll_no }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->section->name }}</td>
                        <td>{{$student->status}}</td>
                        <td>
                            <a href="{{ route('student.edit', ['id' => $student->id]) }}"
                                class="btn btn-success">Edit</a>
                            <a href="{{ route('student.delete', ['id' => $student->id]) }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

</body>
<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>

</html>
