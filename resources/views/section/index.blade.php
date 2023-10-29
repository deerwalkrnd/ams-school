<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Page of Section</title>
</head>

<body>
    <table border="1px">
        <a href="{{route('section.create')}}">Add a Section</a>

        <div>
                @if(session()->has('success'))
                    <div>
                        {{session('success')}}
                    </div>
                    @endif
            </div>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Name</th>
                <th>Type</th>
                <th>Students</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($sections as $section)

                    <tr>
                        <td>{{ $section->id }}</td>
                        <td>{{ $section->section_name }}</td>
                        <td>{{ $section->section_type }}</td>
                        <td>{{ $section->student->name}}</td>
                        <td>
                            <a href="{{route('section.edit',['id'=>$section->id])}}" class="btn btn-success">Edit</a>
                            <a href="{{route('section.delete',['id'=>$section->id])}}">Delete</a>
                        </td>
                    </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
