<x-create-layout>
    <div class="anchor_tag">
        <a href="/section">
            <h5 class="go_back">Go back</h5>
        </a>
    </div>    <form action="{{ route('section.store') }}" method="post">
        @csrf
        <div class="container">
            <label for="name"> Section Name<span class="plus">+</span></label>
            <div class="input_container">
                <input type="text" name="name" placeholder="Enter Section Name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="Choose Section Type">Choose Section Type<span class="plus">+</span></label>
            <div class="input_container">
                <select name="type" class="select_container">
                    <option value="optional">Optional</option>
                    <option value="compulsory">Compulsory</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="">Grade<span class="plus">+</span></label>
            <div class="input_container">
                <select name="grade_id" class="select_container">
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="">Teacher<span class="plus">+</span></label>
            <div class="input_container">
                <select name="user_id" class="select_container">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </form>
</x-create-layout>
