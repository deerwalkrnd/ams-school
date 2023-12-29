<x-edit-layout>
    @section('title', 'Edit Grade')
    <h1 class="heading"> Edit Grade</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="anchor_tag">
        <a href="{{ route('grade.index') }}">
            <h5 class="go_back">←</h5>
        </a>
    </div>
    <form action="{{ route('grade.update', ['grade' => $grades->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name"> Grade Name<span class="star">*</span></label>
            <div class="input_container">
                <input type="number" name="name" value="{{ $grades->name }}" required>
            </div>
        </div>
        <div>
            <label>Start Date<span class="star">*</span></label>
            <div class="input_container">
                <input type="date" name="start_date" value="{{ $grades->start_date }}" required>
            </div>
        </div>
        <div>
            <label>End Date<span class="star">*</span></label>
            <div class="input_container">
                <input type="date" name="end_date" value="{{ $grades->end_date }}" required>
            </div>
        </div>
        <br>
        <button class="btn btn-success" type="submit">Update</button>
    </form>
</x-edit-layout>
