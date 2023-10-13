<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit </title>
</head>

<body>
    <form action="{{route('grade.update',['id'=>$grades->id])}}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name"> Grade Name</label>
            <input type="text" name="name" value="{{$grades->name}}">
        </div>
        <div>
            <label > Start Date</label>
            <input type="date" name="start_date" value="{{$grades->start_date}}">
        </div>
        <div>
            <label>End Date</label>
            <input type="date" name="end_date" value="{{$grades->end_date}}">
        </div>
        <button class="btn btn-primary" type="submit">Update</button>

    </form>

</body>

</html>
