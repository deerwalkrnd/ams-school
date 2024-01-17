@extends('layouts.admin.app')

<body>
    <div class="below_header">
        {{-- <h1 class="heading">Create User</h1> --}}
        {{-- @include('layouts.admin.formTabs') --}}
    </div>
    {{ $slot }}
</body>

<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>
