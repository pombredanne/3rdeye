@extends('admin_template')

@section('content')
   <script>
       $(document).ready(function(){
          $("li").removeClass("active");
          $("#reports").addClass("active");      
           
   });
   </script>
<style>
.pagination > li.active > a, .pagination > li.active > span{
     background-color:mediumpurple !important; 
    border-color: mediumpurple !important;
    color: white !important;
}
 .pagination > li > a:hover, .pagination > li > span:hover,.pagination > li > a:focus, .pagination > li > span:focus {
     background-color:rebeccapurple!important; 
    border-color: rebeccapurple !important;
     color: white !important;
}
</style>

        <div class="row">
            <div class="col-md-12">
                      <div class="box">
            <div class="box-header">
              <h3 class="box-title">My Reports</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Report Title</th>
                  <th>Score</th>
                  <th>Progress</th>
                  <th>Search Type</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                      <td>{{$report->title}}</td>
                      <td>{{round($report->plagiarism_percentage)}}%</td>
                      <td>{{$report->status}}</td>
                      <td>{{$report->search_type}}</td>
                      <td>{{$report->created_at}}</td>
                      <td>
                         <a href="/report/view/{{$report->id}}" data-toggle="tooltip" title="View Report">
                             <i class="fa fa-file-text-o" style="font-size: 17px; color:black;"></i>
                         </a>&nbsp;&nbsp;&nbsp;
                         <a href="{{$report->pdf_report}}" target="_blank" data-toggle="tooltip" title="Download PDF Report">
                             <i class="fa fa-download" style="font-size: 17px; color:black;"></i>
                         </a>&nbsp;&nbsp;&nbsp;
                          <a href="/report/delete/{{$report->id}}" data-toggle="tooltip" title="Delete">
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