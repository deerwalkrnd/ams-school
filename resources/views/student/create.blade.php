<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create </title>
</head>

<body>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <form action="{{route('student.store')}}" method="post">
        @csrf
        <div>
            <label for="name"> Student Name</label>
            <input type="text" name="name" placeholder="Enter Student Name" required>
        </div>
        <div>
            <label >Roll Number</label>
            <input type="text" name="roll_no" placeholder="Enter Roll no" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter Email" required>
        </div>

        <select name="section_id">
            @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>



        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>
<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>

</html>
