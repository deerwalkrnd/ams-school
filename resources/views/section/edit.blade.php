<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit </title>
</head>

<body>
    <form action="{{route('section.update',['id'=>$sections->id])}}" method="post">
        @csrf
        @method('PUT')
        <div>
            <label for="name"> Section Name</label>
            <input type="text" name="name" value="{{$sections->name}}">
        </div>
        <div>
            <div class="form-group">
                <label for="Choose Section Type">Choose Section Type</label>
                <select name="type" class="form-control">
                    <option value="optional">Optional</option>
                    <option value="compulsory">Compulsory</option>
                </select>
            </div>
        </div>
        <select name="grade_id">
            @foreach ($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" type="submit">Add</button>

    </form>

</body>

</html>
