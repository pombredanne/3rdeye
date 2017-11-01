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
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-pencil"></i>&nbsp;&nbsp;UPDATE REFERENCE</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="/reference/edit/{{$reference->id}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        {{method_field("PUT")}}
                      <div class="box-body">
                        <div class="form-group">
                          <label for="title">Project Title</label>
                          <input type="text" class="form-control" id="title" name="title" placeholder="Project Title" value="{{$reference->title}}" required>
                        </div>
                        <div class="form-group">
                          <label for="author">Author Name</label>
                          <input type="text" class="form-control" id="author" name="author" placeholder="Author Name" value="{{$reference->author}}" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" class="form-control" name="category" required>
                                <option selected="selected" value="{{$reference->category}}">{{$reference->category}}</option>
                                <option value="Artificial Intelligence">Artificial Intelligence</option>
                                <option value="Theory">Theory</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="institution">Institution</label>
                            <select id="institution" class="form-control" name="institution" required>
                                <option selected="selected" value="{{$reference->institution}}">{{$reference->institution}}</option>
                                <option value="University of Lagos">University of Lagos</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="pdfupload">File</label>
                          <input name="pdfupload" id="pdfupload" type="file" accept="application/pdf" value="{{$reference->link}}">
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