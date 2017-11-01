<!DOCTYPE HTML>
<html>
	<head>
		<title>3rdEye Plagiarism Checker</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <style>
        body{
            
            
        }
        #preloader{
            padding-top: 90px;
           margin: 0 auto; 
            display:block;
            width: 180px;
            height: auto;
        }
        p.bepatient{
            text-align: center;
            font-family: 'Raleway', sans-serif;
        }
        .showlater{
            z-index: 9999999999999;
        }
    </style>
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<!--<div class="logo">-->
							<!--<span class="icon fa-eye"></span>-->
							<span><img src="http://localhost/3rdeye/public/images/3eye.PNG" style="width: 140px; height: auto;"/></span>
						<!--</div>-->
						<div class="content">
							<div class="inner">
								<!--<h1>3rd Eye</h1>-->
                                <span><img src="http://localhost/3rdeye/public/images/eyelogo.PNG" style="width: 200px; height: auto; margin-bottom: 10px;"/></span>
            
								<p style="font-size: 20px; font-family: 'Raleway'; font-weight: lighter;"><!--A Document Matching tool used for local content Plagiarism Detection.-->
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Plagiarism Detection Tool.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</p>
									<p>
									"The unique must be fulfilled."
								</p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="#login">Login</a></li>
								<li><a href="#register">Register</a></li>
								<li><a href="#demo">Demo</a></li>
								<!--<li><a href="#contact">Features</a></li>
								<li><a href="#elements">Elements</a></li>-->
							</ul>
						</nav>
					</header>
                <section class="showlater" style="display: none;">
                    <img id="preloader" src="http://3rdeye.co/images/preloader.gif"/><br />
                    <p class="bepatient">We are searching our repository for matches <br /> Please be patient</p>
        </section>

				<!-- Main -->
					<div id="main">

						<!-- Intro -->
							<article id="login">
								<h2 class="major">Login</h2>
								<form method="post" action="/login">
                                    {{ csrf_field() }}
									<div class="field">
										<label for="email">Email</label>
                                        <input id="email" type="email" name="email" required >
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
									</div>
									<div class="field">
										<label for="password">Password</label>
										<input type=password name="password" id="password" required/>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
									</div>
									<ul class="actions">
										<li><input type="submit" value="Login" /></li>
									</ul>
								</form>
							</article>

						<!-- Work -->
							<article id="register">
								<h2 class="major">Register</h2>
								<form method="post" action="/register">
                                    {{ csrf_field() }}
									<div class="field half first">
										<label for="name">Username</label>
										<input type="text" name="name" id="name" required/>
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
									</div>
									<div class="field half">
										<label for="email">Email</label>
										<input type="email" name="email" id="email" required />
                                        <span style="color: red;" id="foremail"></span>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
									</div>
									<div class="field">
										<label for="password">Password</label>
										<input type=password name="password" id="password" required/>
                                        <span style="color: red;" id="forpassword"></span>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
									</div>
                                    <div class="field">
										<label for="password-confirm">Re-type Password</label>
										<!--<input type=password name="retypepassword" id="retypepassword" />-->
                                        <input id="password-confirm" type="password" name="password_confirmation" required>
                                        <span style="color: red;" id="forretypepassword"></span>
                                        <input type="text" name="type" id="type" value="User" style="display: none;"/>
									</div>
									<ul class="actions">
										<li><input type="submit" value="Register" class="special" /></li>
										<li><input type="reset" value="Reset" /></li>
									</ul>
								</form>
							</article>

						<!-- Demo -->
							<article id="demo">
								<h2 class="major">Demo</h2>
                                <form action="/demo/result" method="post">
                                    {{csrf_field()}}
                                    <textarea style="width: 100%; height: 300px;" placeholder="Enter text here to check for plagiarism  (Maximum of 1250 characters)" name="docCorpus" id="docCorpus" maxlength="1250" required></textarea>
                                    <br />
                                    <input type=submit value="CHECK" name="btnSubmit" id="btnSubmit" class="submitdemo"/>
                                </form>                              
							</article>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">Copyright &copy; 2017. 3rd Eye</p>
					</footer>

			</div>
            
		<!-- BG -->
			<div id="bg"></div>
            

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
        <script>
       $(document).ready(function(){
           $( ".submitdemo" ).click(function() {
               $("#header").css("display", "none");
               $("#main").css("display", "none");
               $(".showlater").css("display", "block");
            });
       });
   </script>

	</body>
</html>
