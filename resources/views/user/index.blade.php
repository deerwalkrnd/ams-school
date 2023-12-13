<x-main-layout>
    <h1 class="heading"> {{ $pageTitle }}</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="button_container container ">
        <a href="{{ route('user.create') }}" class="btn btn-primary add_button">Add</a>
    </div>
    <div class="table_container mt-3">
        <table class="_table mx-auto amsTable" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Teacher's Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                {{ ucfirst($role->roles) }}
                            @endforeach
                        </td>
                        <td class="">
                            <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-success">Edit</a>
                            <a href="{{ route('user.delete', ['id' => $user->id]) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='4'>No Teachers Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-main-layout>

</body>
