<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit </title>
</head>

<body>
    <form action="{{route('user.update',['id'=>$users->id])}}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name"> User name</label>
            <input type="text" name="name" value="{{$users->name}}">
        </div>
        <div>
            <label > Email</label>
            <input type="email" name="email" value="{{$users->email}}">
        </div>
        <button class="btn btn-primary" type="submit">Update</button>

    </form>

</body>

</html>
