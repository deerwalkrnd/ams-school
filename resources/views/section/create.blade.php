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
            <label for="section_name">Section Name</label>
            <input type="text" name="section_name" placeholder="Enter Section Name">
        </div>
        <div>
            <label >Section Type</label>
            <input type="text" name="section_type" placeholder="Enter Section Type">
        </div>

        <select name="student_id">
            @foreach ($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }}</option>
            @endforeach
        </select>

        <button class="btn btn-primary" type="">Add</button>
        <br><br>
        <button class="btn btn-primary" type="submit">Submit</button>

    </form>

</body>

</html>
