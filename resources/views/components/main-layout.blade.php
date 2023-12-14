@extends('layouts.admin.app')
@if (session()->has('success'))
    <div class="alert alert-success ">
        {{ session()->get('success') }}
    </div>
@endif
@if (session()->has('error'))
    <div class="alert alert-danger ">
        {{ session()->get('error') }}
    </div>
@endif

<body>
    <div class="below_header">
        @include('layouts.admin.formTabs', ['title' => ucfirst(Request::segment(1))])
    </div>
    {{ $slot }}
</body>

<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>
