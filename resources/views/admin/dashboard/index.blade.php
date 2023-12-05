@extends('layouts.admin.app')

@section('title','Dashboard')

<div class="table_container mt-3">
    <table class="_table mx-auto amsTable" id="amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Teacher's Name</th>
                <th>Email Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan='4'>No Teachers Available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



