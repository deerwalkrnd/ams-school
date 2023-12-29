<x-main-layout>
    @section('title', 'User List')
    <h1 class="heading"> User List</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="button_container ">
        <a href="{{ route('user.create') }}" class="btn  add_button"><i class='bx bx-add-to-queue'></i>Add</a>
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
                                {{ ucfirst($role->role) }}
                            @endforeach
                        </td>
                        <td class="">
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success">Edit</a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='5' align="center">No Users Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-main-layout>
