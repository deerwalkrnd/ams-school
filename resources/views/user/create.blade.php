<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create </title>
</head>

<body>
    <form action="{{route('user.store')}}" method="post">
        @csrf
        <div>
            <label for="name"> Full Name</label>
            <input type="text" name="name" placeholder="Enter Grade Name">
        </div>
        <div>
            <label > Email</label>
            <input type="email" name="email" placeholder="Email">
        </div>
        <div>
            <label > Password</label>
            <input type="password" name="password" placeholder="password">
        </div>
        {{-- <div>
            <label>Role</label>
            <input type="text" name="role" placeholder="roles">
        </div> --}}
        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
