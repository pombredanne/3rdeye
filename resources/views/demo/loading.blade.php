<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>3rdEye | Demo</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link type="text/css" rel="stylesheet" href="{{url('/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Raleway:600" rel="stylesheet" type="text/css">
    <style>
        body{
            background-color: rgba(173,255,47, 0.6);
            font-family: 'Raleway', sans-serif;
        }
        img{
            padding-top: 180px;
           margin: 0 auto; 
            display:block;
            width: 200px;
            height: auto;
        }
        p{
            text-align: center;
        }
    </style>
</head>
    <body>
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <img src="{{url('images/preloader.gif')}}"/><br />
                    <p>We are searching our repository for matches <br /> Please be patient</p>
                </div>
            </div>
        </section>

    </body>
      <script src="{{url('bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js')}}" type="text/javascript"></script>
    <script src="{{url('bower_components/AdminLTE/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{url('js/vue.min.js')}}"></script>
    <script src="http://localhost/3rdeye/resources/assets/js/app.js"></script>


</html>
