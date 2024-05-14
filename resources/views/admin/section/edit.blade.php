<x-edit-layout>
    @section('title','Edit Section')
    <h1 class="heading"> Edit Section </h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="anchor_tag">
        <a href="/section">
            <h5 class="go_back">←</h5>
        </a>
    </div>
    <form action="{{ route('section.update', ['id' => $sections->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Section Name<span class="star">*</span></label>
            <div class="input_container">
                <input type="text" name="name" value="{{ $sections->name }}" required>
            </div>
        </div>
        
        <label for="grade_id">Grade<span class="star">*</span></label>
<div class="input_container">
    <select name="grade_id" id="grade_id">
        <option value="" disabled>Select a Grade</option>
        @foreach ($grades as $grade)
            <option value="{{ $grade->id }}" {{ $grade->id == $sections->grade_id ? 'selected' : '' }}>{{ $grade->name }}</option>
        @endforeach
    </select>
</div>

        
<label for="user_id">Teacher<span class="star">*</span></label>
<div class="input_container">
    <select name="user_id" id="user_id">
        <option value="" disabled>Select a Teacher</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ $user->id == $sections->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
        @endforeach
    </select>
</div>

        
        <br>
        <button class="btn btn-success" type="submit">Update</button>

    </form>

</x-edit-layout>