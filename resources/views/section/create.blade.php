<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>

<body>
    <form action="{{route('section.store')}}" method="post">
        @csrf
        <div>
            <label for="name">Section Name</label>
            <input type="text" name="name" placeholder="Enter Section Name">
        </div>
        <div>
            <label >Section Type</label>
            <input type="text" name="type" placeholder="Enter Section Type">
        </div>

        <select name="student_id">
            @foreach ($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }}</option>
            @endforeach
        </select>

        
        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
