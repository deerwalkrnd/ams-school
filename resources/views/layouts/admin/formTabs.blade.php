@props(['title'])
<h1 class="heading">{{ $title }}</h1>
<div class="w-100 pt-3">
    <div class="wrapper-nav">
        <nav class="nav nav-tabs list mt-2 d-flex justify-content-around " id="myTab" role="tablist">
            <a class="nav-item nav-link nav_item below_header_btn" href="{{ route('user.index') }}" role="tab"
                aria-controls="public" aria-expanded="true">Users</a>
                <a class="nav-item nav-link nav_item below_header_btn" href="{{ route('grade.index') }}" role="tab">Grade</a>

                <a class="nav-item nav-link nav_item below_header_btn" href="{{ route('section.index') }}" role="tab">Section</a>
                <a class="nav-item nav-link nav_item below_header_btn" href="{{ route('student.index') }}" role="tab">Students</a>
        </nav>
    </div>
</div>
