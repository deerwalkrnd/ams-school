<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit</title>
</head>

<body>
    <form action="{{route('student.update',['id'=>$students->id])}}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name"> Student Name</label>
            <input type="text" name="name" value="{{$students->name}}">
        </div>
        <div>
            <label >Roll Number</label>
            <input type="number" name="roll_no" value="{{$students->roll_no}}">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{$students->email}}">
        </div>

        <select name="section_id">
            @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>

        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
