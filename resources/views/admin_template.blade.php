<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>3rdEye | Plagiarism Checker</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <!--<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">-->
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/assets/css/font-awesome.min.css">
  <!-- Ionicons -->
  <!--<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/ionicons.min.css">
    
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
  <!--<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">-->    
  <!-- Theme style -->
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/w3.css">
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/skins/skin-purple.min.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" >
  <script src="http://3rdeye.co/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js" type="text/javascript"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
   @include('header')
  
  <!-- Left side column. contains the logo and sidebar -->
  @include('sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ $page_title or null }}
        <small>{{ $page_description or null}}</small>
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">

        @yield('content')
      <!-- Your Page Content Here -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('footer')

  <!-- Control Sidebar -->
    @include('control_sidebar')
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->

<!-- Bootstrap 3.3.6 -->
<script src="http://3rdeye.co/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="http://3rdeye.co/bower_components/AdminLTE/dist/js/app.min.js"></script>
<script src="http://3rdeye.co/bower_components/AdminLTE/dist/js/dialog.js"></script>
<script src="http://3rdeye.co/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="http://3rdeye.co/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable();
  });
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
