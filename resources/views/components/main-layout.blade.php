@extends('layouts.admin.app')

<body>
    <div class="below_header">
        <h1>User</h1>
        @include('layouts.admin.formTabs')
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
