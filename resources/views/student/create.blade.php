<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create </title>
</head>

<body>
    <form action="{{route('student.store')}}" method="post">
        @csrf
        <div>
            <label for="name"> Student Name</label>
            <input type="text" name="name" placeholder="Enter Student Name">
        </div>
        <div>
            <label >Roll Number</label>
            <input type="number" name="roll_no" placeholder="Enter Roll no">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter Email">
        </div>

        <select name="grade_id">
            @foreach ($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
            @endforeach
        </select>



        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
