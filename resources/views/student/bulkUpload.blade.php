<x-bulk-layout>
    @section('title', 'Bulk Upload Students')
    <h1 class="heading">Student Bulk Upload</h1>
    <div class="underline mx-auto hr_line"></div>
    <div class="container1">
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
