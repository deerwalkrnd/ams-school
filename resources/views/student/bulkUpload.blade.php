<x-bulk-layout>
    @section('title', 'Bulk Upload Students')
    <h1 class="heading">Student Bulk Upload</h1>

    <div class="underline mx-auto hr_line"></div>
    <div class="container1">
        @if (isset($errors) &&  $errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error )
                    {{$error}}
                @endforeach
            </div>
        @endif
        @if (session()->has('failures'))
            <table class="table table-danger">
                <tr>
                    <th>Row</th>
                    <th>Attribute</th>
                    <th>Errors</th>
                    <th>Value</th>
                </tr>
                @foreach (session()->get('failures') as $validation )
                    <tr>
                        <td>{{$validation->row()}}</td>
                        <td>{{$validation->attribute()}}</td>
                        <td>
                            <ul>
                                @foreach ($validation->errors() as $e)
                                    <li>{{$e}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                        {{ $validation->values()[$validation->attribute()] }}
                        </td>
                    </tr>
                @endforeach
            </table>    
        @endif
        <div class="button">
        <a href="{{route('student.bulkSample')}}"><button class="btn btn-success">Download Sample</button></a> 
        </div>
        <form action="{{route('student.bulkUpload')}}" method="POST" name="myform" class="form-group" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <div class="col-name  mt-4">
                    <div class="row align-items-center">
                    <label for="student_csv" class=" col-md-2 form-label" >CSV File<span class="red_text"><b>*</b></span></label>
                    <input type="file" name="student_csv" class="form-control" id="student_csv" required>
                    </div>
                </div>   
            </div>
            <div class="d-grid col-md-1 button">
                <button class="btn btn-success" type="submit">Add</button>
            </div>
        </form>
    </div>
</x-bulk-layout>
