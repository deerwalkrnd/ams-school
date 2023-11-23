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
        <div class="alert">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif
    <form action="{{ route('grade.store') }}" method="post">
        @csrf
        <div>
            <label for="name"> Grade Name</label>
            <input type="text" name="name" placeholder="Enter Grade Name" required>
        </div>
        <div>
            <label> Start Date</label>
            <input type="date" name="start_date" placeholder="Start Date" required>
        </div>
        <div>
            <label>End Date</label>
            <input type="date" name="end_date" placeholder="End Date" required>
        </div>
        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>
<script>
    setTimeout(() => {
        document.querySelector('.alert').style.display = 'none';
    }, 2000);
</script>

</html>
