@extends('layouts.admin.app')

<body>
    <div class="below_header">
        @include('layouts.admin.formTabs', ['title' => ucfirst(Request::segment(1))])
    </div>
    @if (session()->has('success'))
        <div class="alert">
            {{ session()->get('success') }}
        </div>
    @endif
    {{ $slot }}
</body>

<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>
