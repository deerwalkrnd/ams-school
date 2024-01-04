@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')

    @include('admin.dashboard.midsection')

    <!-- view teachers section -->
    <div class="table_container">
        <table class="_table mx-auto amsTable" id="amsTable">
            <thead>
                <tr class="table_title">
                    <th>S.N</th>
                    <th>Teacher's Name</th>
                    <th>Email Address</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->last_login }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
