<x-edit-layout>
    @section('title', 'Edit User')
    <h1 class="heading"> Edit User</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="anchor_tag">
        <a href="/user">
            <h5 class="go_back">←</h5>
        </a>
    </div>
    <form action="{{ route('user.update', ['user' => $users->id]) }}" method="post"  class="mt-5 shadow p-3 ">
        @csrf
        @method('PUT')
        <label for="name">User name<span class="star">*</span></label>
        <div class="input_container">
            <input type="text" name="name" value="{{ $users->name }}">
        </div>
        <label>Email<span class="star">*</span></label>
        <div class="input_container">
            <input type="email" name="email" value="{{ $users->email }}">
        </div>
        <div class="col-md-8">
            <div class="row align-items-center">
                <label for="role" class=" col-md-3 form-label">Role<span class="star">*</span></label>
                <div class="input_container">
                    <div class="col-md-2 form-check form-check-inline">
                        <input class="form-check-input" id="admin" type="radio" name="role[]" value="1"
                            {{ in_array(1, old('roles', $users->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label class="form-check-label" for="admin">
                            Admin
                        </label>
                    </div>
                    <div class="col-md-3 form-check form-check-inline ms-1">
                        <input class="form-check-input" id="teacher" type="radio" name="role[]" value="2"
                            {{ in_array(2, old('roles', $users->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label class="form-check-label" for="teacher">
                            Teacher
                        </label>
                    </div>
                </div>
            </div>
            <button class="btn btn-success" type="submit">Update</button>
        </div>
    </form>
</x-edit-layout>
