@extends('layouts.admin.app')

<body>
    <div class="below_header">
        <h1>Edit User</h1>
        @include('layouts.admin.formTabs')
    </div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    {{ $slot }}
</body>

<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>
