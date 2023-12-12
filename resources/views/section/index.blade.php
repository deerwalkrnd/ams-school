<x-main-layout>
    <h1 class="heading"> {{ $pageTitle }}</h1>
    <a href="{{ route('section.create') }}" class="btn btn-primary add_button">Add</a>
    <div class="table_container mt-3">
        <table class="_table mx-auto amsTable" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Section Name</th>
                    <th>Type</th>
                    <th>Grade</th>
                    <th>Teacher</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $section->name }}</td>
                        <td>{{ $section->type }}</td>
                        <td>{{ $section->grade->name }}</td>
                        <td>{{ $section->user->name }}</td>
                        <td class="">
                            <a href="{{ route('section.edit', ['id' => $section->id]) }}" class="btn btn-success">Edit</a>
                            <a href="{{ route('section.delete', ['id' => $section->id]) }}"
                                class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='4'>No Sections Available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
</x-main-layout>
