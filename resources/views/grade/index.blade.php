<x-main-layout>
    @section('title', 'Grade List')
    <h1 class="heading"> {{ $pageTitle }}</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="button_container  ">
        <a href="{{ route('grade.create') }}" class="btn add_button"><i class='bx bx-add-to-queue'></i>Add</a>
    </div>
    <div class="table_container mt-3">
        <table class="_table mx-auto" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Grade</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $grade->name }}</td>
                        <td>{{ $grade->start_date }}</td>
                        <td>{{ $grade->end_date }}</td>
                        <td class="">
                            <a href="{{ route('grade.edit', $grade->id) }}" class="btn btn-success">Edit</a>
                            <a href="{{ route('grade.delete', $grade->id) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='5' align="center">No Grades Available</td>
                        {{-- Colspan doesn't work with DataTables.
                        The following trick is used to suppress column count error.
                        --}}
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-main-layout>
