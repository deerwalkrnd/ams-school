<x-edit-layout>
    @section('title', 'Edit Student')
    <h1 class="heading"> Edit Student</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="anchor_tag">
        <a href="/student">
            <h5 class="go_back">←</h5>
        </a>
    </div>
    <form action="{{ route('student.update', ['id' => $students->id]) }}" method="post">
        @csrf
        @method('PUT')
        <label for="name"> Student Name<span class="star">*</span></label>
        <div class="input_container">
            <input type="text" name="name" value="{{ $students->name }}">
        </div>
        <label>Roll Number<span class="star">*</span></label>
        <div class="input_container">
            <input type="number" name="roll_no" value="{{ $students->roll_no }}">
        </div>
        <label>Email<span class="star">*</span></label>
        <div class="input_container">
            <input type="email" name="email" value="{{ $students->email }}">
        </div>
        <label for="section">Section<span class="star">*</span></label>
            <div class="input_container">
            <select name="section_id" class="select_container" >
                @foreach ($sections as $section)
                @if($section->grade)
                    <option value="{{ $section->id }}">Grade:{{$section->grade->name}}-Section:{{ $section->name }}</option>
                    @else
                    <option value="{{ $section->id }}">Grade: N/A -Section:{{ $section->name }}</option>
                    @endif
                @endforeach
            </select>
            </div>

        <br>
        <button class="btn btn-success" type="submit">Update</button>

    </form>
</x-edit-layout>
