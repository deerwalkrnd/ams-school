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
        <div class="col-md-8 mt-5">
            <div class="row align-items-center">
                <label for="role" class=" col-md-3 form-label">Role</label>
                <div class="col-md-2 form-check form-check-inline">
                    <input class="form-check-input" id="admin" type="checkbox" name="role[]" value="1" >
                    <label class="form-check-label" for="superadmin">
                        Super Admin
                    </label>
                </div>
                <div class="col-md-3 form-check form-check-inline ms-1">
                    <input class="form-check-input" id="superadmin" type="checkbox" name="role[]" value="2" >
                    <label class="form-check-label" for="teacher">
                        Teacher
                    </label>
                </div>
        </div>

        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
