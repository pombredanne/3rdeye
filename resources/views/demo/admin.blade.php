<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>3rdEye | Demo</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/w3.css">
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/assets/css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/AdminLTE.min.css">
  <link type="text/css" rel="stylesheet" href="http://3rdeye.co/bower_components/AdminLTE/dist/css/skins/skin-purple.min.css">
  <script src="http://3rdeye.co/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js" type="text/javascript"></script>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="http://3rdeye.co" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels 
      <span class="logo-mini"><b>3</b>RD</span>-->
      <span class="logo-mini"><img src="http://localhost/3rdeye/public/images/3eye.PNG" style="width: 40px; height: auto;"/></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><span><img src="http://localhost/3rdeye/public/images/eyelogo.PNG" style="width: 140px; height: auto; margin-bottom: 10px;"/></span></span>
      <!--<span class="logo-lg"><b>Admin</b>LTE</span>-->
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
          </li>
        </ul>
      </div>
    </nav>
  </header>
  
  <!-- Left side column. contains the logo and sidebar -->

    <!-- Main content -->
    <section class="content" style="background: #FAFAFA; min-height: 568px;">

          <script>
       var record = 0;
           
       var data = {!! $search_result !!}
       $(document).ready(function(){
          $("li").removeClass("active");
          $("#reports").addClass("active");     
           
           
           var i;
           if(data.length > 0){
               $("#passage").css("display", "block");
               document.getElementById("matchno").innerHTML = record + 1;
               document.getElementById("rtitle").innerHTML = "Title: "+data[record].title;
               document.getElementById("rauthor").innerHTML = "Author: "+data[record].author;
               document.querySelector("#rlink").setAttribute("href", data[record].link);
               document.getElementById("rpage").innerHTML = data[record].page;
               document.getElementById("rindex").innerHTML = data[record].offset;
               document.getElementById("rmatch").innerHTML = ucwords(data[record].match);
           }else{
               document.getElementById("matchno").innerHTML = record;
           }
           
           for(i=0; i<data.length; i++)
            {
                var text = data[i].match;
                inputText = document.getElementById("inputText")
                var innerHTML = inputText.innerHTML;
                var index = inputText.innerHTML.toLowerCase().indexOf(text.toLowerCase());
                
                //console.log("Index is "+index);
                if ( index >= 0 )
                { 
                    innerHTML = innerHTML.substring(0,index) + "<span onclick='gotoRecord("+i+"); return false' class='highlight' data-toggle='tooltip' title='100% match' style='cursor: pointer;'>" + innerHTML.substring(index,index+text.length) + "</span>" + innerHTML.substring(index + text.length);
                    inputText.innerHTML = innerHTML; 
                }
            }


           $("#showreportinfo").click(function(){
               $("#reportInfoModal").modal();
           });
           $("#showallmatches").click(function(){
               $("#allMatchesModal").modal();
           });
           $("#deletereport").click(function(){
               $("#deleteReportModal").modal();
           });
           
           function ucwords(strr) {
              str = strr.toLowerCase();
              return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                function(s){
                  return s.toUpperCase();
                });
            };  
           
   });
   
   function ucwords(strr) {
              str = strr.toLowerCase();
              return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                function(s){
                  return s.toUpperCase();
                });
            };      

   function gotoRecord(i){
       record = i;
       document.getElementById("matchno").innerHTML = record + 1;
       document.getElementById("rtitle").innerHTML = "Title: "+data[record].title;
       document.getElementById("rauthor").innerHTML = "Author: "+data[record].author;
       document.querySelector("#rlink").setAttribute("href", data[record].link);
       document.getElementById("rpage").innerHTML = data[record].page;
       document.getElementById("rindex").innerHTML = data[record].offset;
       document.getElementById("rmatch").innerHTML = ucwords(data[record].match);
   }
       
       function decrease(){
           record = record - 1;
           if(record >= 0){
               document.getElementById("rtitle").innerHTML = "Title: "+data[record].title;
               document.getElementById("rauthor").innerHTML = "Author: "+data[record].author;
               document.querySelector("#rlink").setAttribute("href", data[record].link);
               document.getElementById("rpage").innerHTML = data[record].page;
               document.getElementById("rindex").innerHTML = data[record].offset;
               document.getElementById("rmatch").innerHTML = ucwords(data[record].match);
           }else{ record = 0;}
           
           document.getElementById("matchno").innerHTML = record + 1;
       }
       function increase(){
           record = record + 1;
           if(record < data.length && data.length > 0){
               document.getElementById("rtitle").innerHTML = "Title: "+data[record].title;
               document.getElementById("rauthor").innerHTML = "Author: "+data[record].author;
               document.querySelector("#rlink").setAttribute("href", data[record].link);
               document.getElementById("rpage").innerHTML = data[record].page;
               document.getElementById("rindex").innerHTML = data[record].offset;
               document.getElementById("rmatch").innerHTML = ucwords(data[record].match);
           }else{ record = (data.length - 1);}
           
           document.getElementById("matchno").innerHTML = record + 1;
       }

   </script>
    <!--<div class="row">
        <div class="col-md-12">
            <p id="inputText">
                This is how to show everyone what we are made OF yes mehnnnn
            </p>
        </div>
    </div>-->
    <!-- Report Info Modal -->
    <div id="reportInfoModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;Report Info</h4>
          </div>
          <div class="modal-body">
            <p><strong>Report Title: </strong><span>{{$title}}</span></p>
            <p><strong>Score: </strong><span>{{$plagiarism_percentage}} %</span></p>
            <p><strong>Character Count: </strong><span>{{$character_count}}</span></p>
            <p><strong>Word Count: </strong><span>{{$word_count}}</span></p>
            <p><strong>Sentence Count: </strong><span>{{$sentence_count}}</span></p>
            <p><strong>Matching Sentences: </strong><span>{{$matching_sentences}}</span></p>
            <p><strong>Search Type: </strong><span>{{$search_type}}</span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <div id="allMatchesModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-2">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;All Matches</h4>
          </div>
          <div class="modal-body modal-body-2">
            @foreach ($search_result_array as $result)
                  <div class="row">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                          <div class="panel panel-default">
                            <div class="panel-heading"><strong>100% similar (exact match)</strong></div>
                                <div class="panel-body">
                                    <p><strong><em>"{{ ucwords( $result['match'] ) }}"</em></strong></p>
                                    <a href="{{$result['link']}}" target="_blank"><strong> {{$result['title']}} </strong></a> <br />
                                    <strong>Author: </strong> {{$result['author']}} <br />
                                    Found on Page {{$result['page']}} <br />
                                    Found on Index {{$result['offset']}}
                                </div>
                          </div>
                      </div>                 
                      <div class="col-md-2"></div>
                 </div><br />
              @endforeach
         </div>
      
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <div id="deleteReportModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;Please Confirm</h4>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this report?</p>
                <p>You cannot undo this action.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal" style="margin-left: 5px;">Cancel</button>
                <button type="button" class="btn btn-danger pull-right deleteReport" style="margin-left: 5px;">Delete</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
     </div>
        <!-- /.modal -->


    <div class="w3-container row">
        <div class="col-md-8 pad-top">
            <h2>REGISTER <a href="http://3rdeye.co/index#register">HERE</a> TO GAIN ACCESS TO MORE FEATURES</h2>
            <div class="w3-panel w3-card-2">
                <p style="padding: 15px;" id="inputText">
                    {{$content}}
                </p>
            </div>
        </div>
        <div class="col-md-4 pad-top" style="width: 26%; position: fixed; right: 2.3%;">
            <div class="row">
                <div class="col-sm-12 boxx">
                    <div class="pull-left">
                        <h2><strong style="color: mediumpurple;">{{$plagiarism_percentage}}%&nbsp;</strong><i class="fa fa-question" style="color: #ccc; cursor: help;" aria-hidden="true" data-toggle='tooltip' title='The score is positively correlated with the probability of plagiarism; A high score means a high probability of significant plagiarism'></i></h2>
                    </div>
                    <div class="pull-right tobottom">
                        <span data-toggle='tooltip' title='Report Info' id="showreportinfo"><i class="fa fa-info-circle" aria-hidden="true"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span data-toggle='tooltip' title='List all results' id='showallmatches'><i class="fa fa-list" aria-hidden="true"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span data-toggle='tooltip' title='Download PDF report (Registered users only)' id="downloadpdfreport"><a target="_blank" style="color: black;"><i class="fa fa-download" aria-hidden="true"></i></a></span>&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 grayy">
                    <div class="pull-left">&nbsp;&nbsp;<i class="fa fa-angle-left" aria-hidden="true" onclick="decrease();" style="cursor: pointer;"></i><div id="matchno">Match</div><i class="fa fa-angle-right" aria-hidden="true" onclick="increase();" style="cursor: pointer;"></i>
                    </div>
                    <div class="pull-right">{{$matching_sentences}} matches from {{$matching_sources}} source(s)</div>
                </div>
            </div>
            <div class="row marg-top" id="passage" style="display: none;">
                <div class="col-sm-3">
                    <h3><strong>100%</strong></h3>
                    similar
                </div>
                <div class="col-sm-9" style="border-left: 2px solid black;">
                    <strong id="rtitle">Title of Reference</strong><br />
                    <span id="rauthor">Author of Reference </span><br />
                    <a href="#" id="rlink">Go to Reference</a><br />
                    Found on Page <span id="rpage"></span><br />
                    Found on Index <span id="rindex"></span>
                    
                </div>
            </div>
            <div class="row marg-top">
                <div class="col-sm-12 red">
                    <p><strong class="plagiarizedtext" id="rmatch"></strong></p>
                </div>
            </div>
        </div>
</div>

<script>


</script>
<style>
.highlight
{
background-color: rgba(255, 0, 0, 0.4);
}
    .pad-top{
        padding-top: 40px;
    }
    .marg-top{
        margin-top: 10px;
    }
    .w3-card-2{
        background: white !important;
        min-height: 350px !important;
    }
    .boxx{
        position: relative;
    }
    .tobottom{
        position: absolute;
        bottom: 0;
        right: 0;
        font-size: 20px;
    }
    h3, h2{
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
    .fa-angle-left, .fa-angle-right{
        font-size: 25px;
        font-weight: bold;
    }
    .grayy{
        background: #DFD8E1; padding: 15px 10px; margin-top: 10px;
    }
    #matchno{
        font-size: 20px;
        text-align: center;
        display: inline;
        padding-left: 20px;
        padding-right: 20px;
    }
    .red{
        color: red;
    }
    span{
        cursor: pointer;
    }
    .modal-header{
        background-color: mediumpurple;
        color: white;
    }
    .modal {
    text-align: center;
    padding: 0!important;
    }

    .modal:before {
    content: '';
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    margin-right: -4px;
    }

    .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
    }
    
    .modal-dialog-2{
    overflow-y: initial !important
    }
    .modal-body-2{
        height: 400px;
        overflow-y: auto;
    }
    footer{
        background: rgb(34,45,50) !important;
        color: white!important;
    }
</style>


      <!-- Your Page Content Here -->

    </section>
    <!-- /.content -->


  <!-- Main Footer -->
    <footer class="main-footer" >
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <strong>Copyright &copy; 2017 <a href="#">3rd Eye</a>.</strong>&nbsp;&nbsp;&nbsp;All rights reserved.
    </div>
    <!-- Default to the left -->
    <p></p>
  </footer>
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->

<!-- Bootstrap 3.3.6 -->
<script src="http://localhost/3rdeye/resources/assets/js/app.js"></script>
<script src="http://3rdeye.co/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="http://3rdeye.co/bower_components/AdminLTE/dist/js/app.min.js"></script>
<script src="http://3rdeye.co/js/vue.min.js"></script>
<script src="http://3rdeye.co/bower_components/AdminLTE/dist/js/dialog.js"></script>
</body>
</html>
