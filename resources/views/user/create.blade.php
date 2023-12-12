<x-create-layout>
    <h1 class="heading"> {{ $pageTitle }}</h1>
    <div class="anchor_tag">
        <a href="/user">
            <h5 class="go_back">Go back</h5>
        </a>
    </div>
    <form action="{{ route('user.store') }}" method="post">
        @csrf
        <div class="container">
            <label for="name"> Full Name<span class="plus">+</span></label>
            <div class="input_container">
                <input type="text" name="name" placeholder="Enter Grade Name" required>
            </div>
        </div>
        <div class="container">
            <label>Email<span class="plus">+</span></label>
            <div class="input_container">
                <input type="email" name="email" placeholder="Email" required>
            </div>
        </div>
        <div class="container">
            <label> Password<span class="plus">+</span></label>
            <div class="input_container">
                <input type="password" name="password" placeholder="password" required>
            </div>
        </div>
        <div class="col-md-8 mt-4">
            <div class="row align-items-center">
                <label for="role" class=" col-md-3 form-label" required>Role<span class="plus">+</span></label>
                <div class="col-md-2 form-check form-check-inline">
                    <input class="form-check-input" id="admin" type="radio" name="role[]" value="1">
                    <label class="form-check-label" for="admin">
                        Admin
                    </label>
                </div>
                <div class="col-md-3 form-check form-check-inline ms-1">
                    <input class="form-check-input" id="teacher" type="radio" name="role[]" value="2">
                    <label class="form-check-label" for="teacher">
                        Teacher
                    </label>
                </div>
            </div>
        </div>
        <button class="btn btn-success submit_button" type="submit">Add</button>
    </form>
</x-create-layout>
