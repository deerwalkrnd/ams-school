@extends('layouts.admin.app')
<body>
    <div class="below_header">
        @include('layouts.admin.formTabs', ['title' => ucfirst(Request::segment(1))])
    </div>
    {{ $slot }}
</body>

