@extends('admin_template')

@section('content')
   <script>
       $(document).ready(function(){
          $("li").removeClass("active");
          $("#search").addClass("active");
          $(".hidelater").css("display", "block");


          if($("textarea").val().trim() != "") {
                $(".search").css("display", "block");
                $(".upload-doc").css("display", "none");
            }else{
                $(".search").css("display", "none");
                $(".upload-doc").css("display", "block");
          }

          $("textarea").on("change keyup paste", function() {
                if($(this).val().trim() != "") {
                    $(".search").css("display", "block");
                    $(".upload-doc").css("display", "none");
                }else{
                    $(".search").css("display", "none");
                    $(".upload-doc").css("display", "block");
                }
           });
          
           $( ".btn-submit" ).click(function() {
               $(".hidelater").css("display", "none");
               $(".showlater").css("display", "block");
               $("#myModal").modal('hide');
               $("#myUploadModal").modal('hide');
            });
       });
   </script>
<style>
    #preloader{
        padding-top: 50px;
        margin: 0 auto; 
        display:block;
        width: 120px;
        height: auto;
    }
    p{
        text-align: center;
    }
    textarea.entertext{
        width: 100% !important;
        height: 320px !important;
        box-shadow: 2px 2px 2px #888888 !important;
    }
    ::-webkit-input-placeholder {
        padding-top: 120px !important;
        text-align: center !important;
        font-size: 20px;
        font-family: 'Raleway';
    }
    :-moz-placeholder {
        padding-top: 120px !important;
        text-align: center !important;
        font-size: 20px;
        font-family: 'Raleway';
    }
    ::-moz-placeholder {
        padding-top: 120px !important;
        text-align: center !important;
        font-size: 20px;
        font-family: 'Raleway';
    }
    :-ms-input-placeholder {
        padding-top: 120px !important;
        text-align: center !important;
        font-size: 20px;
        font-family: 'Raleway';
    }
    
    div.upload-doc, div.search{
        margin-top: 20px;
        font-size: 20px;
    }
    .btn-primary{
        background-color: mediumpurple !important;
        border-color: mediumpurple !important;
        box-shadow: 1px 1px 1px #888888 !important;
    }
    .progress-bar-primary{
        background-color: mediumpurple !important;
        border-color: mediumpurple !important;
    }
    .btn-primary:hover{
        background-color: purple !important;
        border-color: purple !important;
        box-shadow: 1px 1px 1px #888888 !important;
    }
    input[type="file"] {
    display: none;
    }
    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
        font-weight: lighter;
        color: white !important;
        background-color: mediumpurple !important;
    }
    .custom-file-upload:hover{
         background-color: purple !important;
    }
    input:focus{
        outline: none;
    }
    #title{
        width: 100%; background: transparent; font-weight: bold; text-align: center; border: none;
        border-color: transparent; margin-bottom: 10px;
    }
    .search-mode-header{
      background-color: mediumpurple !important;
      color: white;
    }
    button.close{
      color: white;
    }
    .search-question{
      font-size: 16px;
    }
    .modal-body{
      padding: 20px;
    }
    .search-logo{
      height: 60px;
      width: auto;
      margin: 0 auto;
      display: block;
    }

</style>
        <div class="row">
            <div class="col-md-2 hidelater"></div>
            <div class="col-md-8 hidelater">
                <form role="form" class="entertexthere" method="post" action="/report/text" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                        <input type="text" id="title" name="title" placeholder="Enter Report Title (optional)"/>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control entertext" rows="3" placeholder="Enter Your Text Here" required name="textpaper" id="textpaper"></textarea>
                        <!--type="submit" btn-submit  search-btn -->
                        <div class="text-center search" style="display: none;"><a class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" ></i>&nbsp;&nbsp; SEARCH</a></div>
                    </div>
                    <!-- Trigger the modal with a button 
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>-->

                    <!-- Modal -->
                    <div id="myModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header search-mode-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">One more thing...</h4>
                          </div>
                          <div class="modal-body">
                            <img src="{{ url('/images/3e.png')}}" class="search-logo"/> <br />
                            <p class="search-question"><strong>PLEASE CHOOSE SEARCH MODE</strong> </p>
                            <br />
                            <input type="radio" name="searchmode" value="Universal" checked>&nbsp;&nbsp;&nbsp;Universal <span style="color: #500056">(This mode searches the entire repository)</span><br>
                            <input type="radio" name="searchmode" value="Categorical">&nbsp;&nbsp;&nbsp;Categorical <span style="color: #500056">(This mode narrows the search down to a particular category)</span>
                            <br /><br />

                            <div class="form-group" style="display: none;" id="category" >
                                <label for="category">Choose Category</label>
                                <select class="form-control" name="category">
                                    @foreach ($categories as $category)
                                      <option value="{{$category->category_name}}">{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br />
                              <button type="submit" class="btn btn-primary btn-lg search-btn btn-submit">SUBMIT</button>
                            </div>
                        </div>

                      </div>
                    </div>
                </form>
                <form role="form" method="post" action="/report/pdf" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                        <canvas id="the-canvas" style="display: none; margin: 0 auto;"></canvas>
                        <div class="text-center upload-doc"><p id="or">or</p> 
                            <div class="text-center searchpdf" style="display: none;"><a class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myUploadModal"><i class="fa fa-search"></i>&nbsp;&nbsp; SEARCH</a></div>
                            <br />
                            <label for="file-upload" class="custom-file-upload">
                                <i class="fa fa-cloud-upload"></i>&nbsp;&nbsp; <span id="uploadtext">UPLOAD DOCUMENT</span>
                            </label>
                            <input id="file-upload" name="document" type="file" accept="application/pdf" required/>
                            
                            
                        </div>
                        <div id="myUploadModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header search-mode-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">One more thing...</h4>
                                </div>
                                <div class="modal-body">
                                  <img src="http://3rdeye.co/images/3e.png" class="search-logo"/> <br />
                                  <p class="search-question"><strong>PLEASE CHOOSE SEARCH MODE</strong> </p>
                                  <br />
                                  <input type="radio" name="uploadsearchmode" value="Universal" checked>&nbsp;&nbsp;&nbsp;Universal <span style="color: #500056">(This mode searches the entire repository)</span><br>
                                  <input type="radio" name="uploadsearchmode" value="Categorical">&nbsp;&nbsp;&nbsp;Categorical <span style="color: #500056">(This mode narrows the search down to a particular category)</span>
                                  <br /><br />

                                  <div class="form-group" style="display: none;" id="uploadcategory" >
                                      <label>Choose Category</label>
                                      <select class="form-control" name="category">
                                          @foreach ($categories as $category)
                                            <option value="{{$category->category_name}}">{{$category->category_name}}</option>
                                          @endforeach
                                          <!--
                                          <option value="Technology" selected>Technology</option>
                                          <option value="Arts">Arts</option>
                                          -->
                                      </select>
                                  </div>

                                  <br />
                                    <button type="submit" class="btn btn-primary btn-lg search-btn btn-submit">SUBMIT</button>
                                  </div>
                              </div>

                      </div>
                    </div>
                        
                    </div>
                </form>
            </div>
            <div class="col-md-2 hidelater"></div>
            <div class="col-md-12 showlater" style="display: none;">
                <img src="http://3rdeye.co/images/preloader.gif" id="preloader"/><br />
                 <p>We are searching our repository for matches <br /> Please be patient</p>
            </div>
        </div>


<!--<script src="http://cdnjs.cloudflare.com/ajax/libs/processing.js/1.4.1/processing-api.min.js"></script>-->
<script src="http://3rdeye.co/assets/js/processing-api.min.js"></script>
<!--<script type="text/javascript" src="https://rawgithub.com/mozilla/pdf.js/gh-pages/build/pdf.js"></script>-->
<script type="text/javascript" src="http://3rdeye.co/assets/js/pdf.js"></script>
<script type="text/javascript" src="http://3rdeye.co/assets/js/pdfworker.js"></script>
  <script type="text/javascript">

    //
    // Disable workers to avoid yet another cross-origin issue (workers need the URL of
    // the script to be loaded, and dynamically loading a cross-origin script does
    // not work)
    //
    PDFJS.disableWorker = true;

    //
    // Asynchronous download PDF as an ArrayBuffer
    //
    var pdf = document.getElementById('file-upload');
      
    pdf.onchange = function(ev) {
        var val = $("#file-upload").val().toLowerCase();
        var regex = new RegExp("(.*?)\.(pdf)$");   //docx|doc|pdf|xml|bmp|ppt|xls
        if(!(regex.test(val))) {
            $("#file-upload").val('');
            alert('Please select correct file format');
        }
        else{ 
          waitingDialog.show('Uploading PDF...', {dialogSize: 'sm', progressType: 'primary'});
          if (file = document.getElementById('file-upload').files[0]) {
            fileReader = new FileReader();
            fileReader.onload = function(ev) {
              console.log(ev);
              PDFJS.getDocument(fileReader.result).then(function getPdfHelloWorld(pdf) {
                //
                // Fetch the first page
                //
                console.log(pdf)
                pdf.getPage(1).then(function getPageHelloWorld(page) {
                  var scale = 0.5;
                  var viewport = page.getViewport(scale);

                  //
                  // Prepare canvas using PDF page dimensions
                  //
                  var canvas = document.getElementById('the-canvas');
                  var context = canvas.getContext('2d');
                  canvas.height = viewport.height;
                  canvas.width = viewport.width;
                  $("#the-canvas").css("display", "block");
                  //
                  // Render PDF page into canvas context
                  //
                  var task = page.render({canvasContext: context, viewport: viewport})
                  task.promise.then(function(){
                    //console.log(canvas.toDataURL('image/jpeg'));
                      waitingDialog.hide();
                      $(".entertexthere").css("display", "none");
                      $("p#or").css("display", "none");
                      $(".searchpdf").css("display", "block");
                      $("#uploadtext").text("REUPLOAD");

                  });
                });
              }, function(error){
                console.log(error);
              });
            };
            fileReader.readAsArrayBuffer(file);
            }
        }
    }

    $("input[name=searchmode]").change(function () {
        if(this.value == "Universal")
        {
            $("#category").css("display", "none");
        }
        else if(this.value == "Categorical")
        {
            $("#category").css("display", "block");
        }
    });
    $("input[name=uploadsearchmode]").change(function () {
        if(this.value == "Universal")
        {
            $("#uploadcategory").css("display", "none");
        }
        else if(this.value == "Categorical")
        {
            $("#uploadcategory").css("display", "block");
        }
    });

  </script>
@endsection
