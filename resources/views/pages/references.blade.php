@extends('admin_template')

@section('content')
   <script>
       $(document).ready(function(){
          $("li").removeClass("active");
          $("#ref").addClass("active");      
           
   });
   </script>
<style>
.pagination > li.active > a, .pagination > li.active > span, a.btn-primary{
     background-color:mediumpurple !important; 
    border-color: mediumpurple !important;
    color: white !important;
}
 .pagination > li > a:hover, .pagination > li > span:hover,.pagination > li > a:focus, .pagination > li > span:focus, a.btn-primary: hover, a.btn-primary: focus {
     background-color:rebeccapurple!important; 
    border-color: rebeccapurple !important;
     color: white !important;
}
</style>

        <div class="row">
            <div class="col-md-12">
                <h2>REFERENCES</h2>
                      <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Category</th>
                  <th>Supervisor</th>
                  <th>Institution</th>
                  <th>Date Uploaded</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                   @foreach($references as $reference)
                    <tr>
                      <td>{{$reference->title}}</td>
                      <td>{{$reference->author}}</td>
                      <td>{{$reference->category}}</td>
                      <td>{{$reference->supervisor}}</td>
                      <td>{{$reference->institution}}</td>
                      <td>{{$reference->created_at}}</td>
                      <td>
                         <a href="{{$reference->link}}" data-toggle="tooltip" title="View Reference">
                             <i class="fa fa-eye" style="font-size: 17px; color:black;"></i>
                         </a>&nbsp;&nbsp;&nbsp;
                         <a href="/reference/edit/{{$reference->id}}" data-toggle="tooltip" title="Edit Reference">
                             <i class="fa fa-pencil" style="font-size: 17px; color:black;"></i>
                         </a>&nbsp;&nbsp;&nbsp;
                          <a href="/reference/delete/{{$reference->id}}" data-toggle="tooltip" title="Delete">
                             <i class="fa fa-trash-o" style="font-size: 17px; color:black;"></i>
                         </a>
                      </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
            </div>
        </div>

@endsection