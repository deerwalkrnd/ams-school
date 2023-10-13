<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create </title>
</head>

<body>
    <form action="{{route('grade.store')}}" method="post">
        @csrf
        <div>
            <label for="name"> Grade Name</label>
            <input type="text" name="name" placeholder="Enter Grade Name">
        </div>
        <div>
            <label > Start Date</label>
            <input type="date" name="start_date" placeholder="Start Date">
        </div>
        <div>
            <label>End Date</label>
            <input type="date" name="end_date" placeholder="End Date">
        </div>
        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
