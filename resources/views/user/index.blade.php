<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Page of User</title>
</head>

<body>
    <table border="1px">
        <a href="{{ route('user.create') }}">Add a User</a>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->role as $role)
                        {{ucfirst($role->roles)}}
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-success">Edit</a>
                        <a href="{{ route('user.delete', ['id' => $user->id]) }}">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
