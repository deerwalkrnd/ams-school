<x-edit-layout>
    <form action="{{ route('grade.update', ['id' => $grades->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name"> Grade Name<span class="star">*</span></label>
            <div class="input_container">
            <input type="text" name="name" value="{{ $grades->name }}" required>
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
        <button class="btn btn-primary" type="submit">Update</button>
    </form>
</x-edit-layout>
