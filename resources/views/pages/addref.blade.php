@extends('admin_template')

@section('content')
   <script>
       $(document).ready(function(){
          $("li").removeClass("active");
          $("#ref").addClass("active");      
           
   });
   </script>
    <style>
        .box-header, .btn-primary{
            background: #605ca8;
            border-color: #605ca8;
            color: white;
        }
        .btn-primary:hover{
            background: purple;
            border-color: purple;
            color: white;
        }
    </style>
        <div class="row" style="margin: -25px;">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;ADD REFERENCE</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="/add-reference" enctype="multipart/form-data">
                        {{csrf_field()}}
                      <div class="box-body">
                        <div class="form-group">
                          <label for="title">Project Title</label>
                          <input type="text" class="form-control" id="title" name="title" placeholder="Project Title" required>
                        </div>
                        <div class="form-group">
                          <label for="author">Author Name</label>
                          <input type="text" class="form-control" id="author" name="author" placeholder="Author Name" required>
                        </div>
                        <div class="form-group">
                          <label for="author">Supervisor Name</label>
                          <input type="text" class="form-control" id="supervisor" name="supervisor" placeholder="Supervisor Name" required>
                        </div>
                        <div class="form-group">
                            <label for="institution">Institution</label>
                            <select id="institution" class="form-control" name="institution" required>
                                <option value="" disabled selected>Choose Institution</option>
                                @foreach ($institutions as $institution)
                                    <option value="{{$institution->institution_name}}">{{$institution->institution_name}}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" class="form-control" name="category" required>
                                <option value="" disabled selected>Choose Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->category_name}}">{{$category->category_name}}</option>
                                @endforeach    
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="pdfupload">File</label>
                          <input name="pdfupload" id="pdfupload" type="file" accept="application/pdf" required>
                        </div>
                      </div>
                      <!-- /.box-body -->

                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
            </div>
            <div class="col-md-2"></div>
        </div>

@endsection