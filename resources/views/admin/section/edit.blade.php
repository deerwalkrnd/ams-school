<x-edit-layout>
    @section('title','Edit Section')
    <h1 class="heading"> Edit Section </h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="anchor_tag">
        <a href="/section">
            <h5 class="go_back">←</h5>
        </a>
    </div>
    <form action="{{ route('section.update', ['section' => $sections->id]) }}" method="post" class="mt-5 shadow p-3 ">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Section Name<span class="star">*</span></label>
            <div class="input_container">
                <input type="text" name="name" value="{{ $sections->name }}" required>
            </div>
        </div>
        <label for="">Grade<span class="star">*</span></label>
        <div class="input_container">
            <select name="grade_id">
                @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>
        <label for="">Teacher<span class="star">*</span></label>
        <div class="input_container">
            <select name="user_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button class="btn btn-success" type="submit">Update</button>

    </form>

</x-edit-layout>
