@extends('layouts.admin.app')

<body>
    <div class="below_header">
        {{-- <h1 class="heading">Create User</h1> --}}
        {{-- @include('layouts.admin.formTabs') --}}
    </div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    {{ $slot }}
    <button class="btn btn-success submit_button" type="submit">Add</button>

</body>

<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>
