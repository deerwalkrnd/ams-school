<x-create-layout>
    <div class="anchor_tag">
        <a href="/student">
            <h5 class="go_back">Go back</h5>
        </a>
    </div>
    <form action="{{route('student.store')}}" method="post">
        @csrf
        <div>
            <label for="name"> Student Name<span class="plus">+</span></label>
            <div class="input_container">
            <input type="text" name="name" placeholder="Enter Student Name" required>
            </div>
        </div>
        <div>
            <label >Roll Number<span class="plus">+</span></label>
            <div class="input_container">
            <input type="number" name="roll_no" placeholder="Enter Roll no" required>
            </div>
        </div>
        <div>
            <label>Email<span class="plus">+</span></label>
            <div class="input_container">
                <input type="email" name="email" placeholder="Enter Email" required>
            </div>
        </div>
        <div>
            <label for="section">Section<span class="plus">+</span></label>
            <div class="input_container">
            <select name="section_id" class="select_container" >
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </form>

</x-create-layout>
