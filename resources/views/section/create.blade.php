<x-create-layout>
    @section('title', 'Add Section')
    <h1 class="heading"> {{ $pageTitle }}</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="anchor_tag">
        <a href="/section">
            <h5 class="go_back">←</h5>
        </a>
    </div>
    <div class="form_container">
    <form action="{{ route('section.store') }}" method="post">
        @csrf
        <div class="">
            <label for="name"> Section Name<span class="star">*</span></label>
            <div class="input_container">
                <input type="text" name="name" placeholder="Enter Section Name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="">Grade<span class="star">*</span></label>
            <div class="input_container">
                <select name="grade_id" class="select_container">
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="">Teacher<span class="star">*</span></label>
            <div class="input_container">
                <select name="user_id" class="select_container">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button class="btn btn-success submit_button" type="submit">Add</button>
    </form>
</div>
</x-create-layout>
