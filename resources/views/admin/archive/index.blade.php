<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Archived Attendances</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- Custom files -->
    <link rel="stylesheet" href="/assets/css/styles.css">


    <script src="/assets/js/main.js"></script>

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">


    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>

<body class="bg-gray-100 text-gray-800 font-sans">
    @include('layouts.admin.navbar')

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center m-6">Archived Attendances</h1>
        <div class="w-24 h-1 bg-[#d46b02] mx-auto mb-6"></div>


        {{-- <form action="{{ route('archive.search') }}" method="GET" class="mb-6">

            <div class="flex justify-between mb-4">
                <div class="w-1/2 mr-2">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50"
                        value="{{ request('start_date') }}">
                </div>
        
                <div class="w-1/2">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50"
                        value="{{ request('end_date') }}">
                </div>
            </div>
        
            <div class="flex justify-between mb-4">
                <div class="w-1/2 mr-2">
                    <label for="section" class="block text-sm font-medium text-gray-700">Section with Grade </label>
                    <select name="section" id="section"
                        class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                                Section {{ $section->name }} - Grade {{ $section->grade->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div class="flex items-end">
                    <button type="submit"
                        class="mr-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Filter</button>
                    <a href="{{ route('archive.search') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Reset</a>
                </div>
            </div>
        
        </form> --}}


        <div class="overflow-x-auto bg-white rounded-lg shadow-md p-8">
            <table class="min-w-full _table mx-auto " id="amsTable">
                <thead>
                    <tr class="table_title">
                        <th class="px-4 py-2 text-left text-sm font-semibold">S.N</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Student</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Roll No</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Section</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Grade</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Teacher</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Comment</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($attendances as $index => $attendance)
                        @php
                            $student = $attendance->student;
                            $section = $student->section ?? null;
                            $grade = $section->grade ?? null;
                        @endphp
                        <tr>
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $student->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $student->roll_no ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $section->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $grade->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $attendance->teacher->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">
                                <span class="{{ $attendance->absent ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $attendance->absent ? 'Absent' : 'Present' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $attendance->comment ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($attendances->isEmpty())
                <div class="text-center mt-4">
                    <p class="text-gray-500">No archived attendance records found.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $attendances->links() }}
        </div>
    </div>

</body>
<script>
    $(document).ready(function() {
        $('#amsTable').DataTable({
            responsive: true,
            pageLength: 250
        });
    });
</script>

</html>
