@extends('admin_template')

@section('content')
   <script>
       $(document).ready(function(){
          $("li").removeClass("active");
          $("#account").addClass("active");      
           
   });
   </script>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                  <div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;NEW USER ACCOUNT</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="/account/newuser">
                        {{csrf_field()}}
                      <div class="box-body">
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Account Type</label>
                            <select id="type" class="form-control" name="type" required>
                                <option value="" disabled selected>Choose Account Type</option>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>
                            </select>
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