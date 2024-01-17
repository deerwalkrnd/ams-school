@extends('layouts.admin.app')
@if (session()->has('success'))
    <div class="alert alert-success ">
        {{ session()->get('success') }}
    </div>
@endif

<body>
    {{$slot}}
</body>

<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>
