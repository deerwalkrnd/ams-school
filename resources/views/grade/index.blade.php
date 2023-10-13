<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Page of Grade</title>
</head>

<body>
    <table border="1px">
        <a href="{{route('grade.create')}}">Add a Grade</a>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Grade</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($grades as $grade)
                <tr>
                    <td>{{ $grade->id }}</td>
                    <td>{{ $grade->name }}</td>
                    <td>{{ $grade->start_date }}</td>
                    <td>{{ $grade->end_date }}</td>
                    <td>
                        <a href="{{route('grade.edit',['id'=>$grade->id])}}" class="btn btn-success">Edit</a>
                        <a href="{{route('grade.delete',['id'=>$grade->id])}}">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
