<!DOCTYPE HTML>
<html>
	<head>
		<title>3rdEye Plagiarism Checker</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
					</header>

				<!-- Main -->
					
                        <article style="background: #000; padding: 20px; opacity: 0.7; margin-top: -30px;">
                            <h2 class="major">Register</h2>
                            <form method="post" action="/register">
                                    {{ csrf_field() }}
									<div class="field half first">
										<label for="name">Username</label>
										<input type="text" name="name" id="name" />
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
									</div>
									<div class="field half">
										<label for="email">Email</label>
										<input type="email" name="email" id="email" />
                                        <span style="color: red;" id="foremail"></span>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
									</div>
									<div class="field">
										<label for="password">Password</label>
										<input type=password name="password" id="password" />
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
                                        <input type=text name="type" id="type" value="user" style="display: none;"/>
									</div>
									<ul class="actions">
										<li><input type="submit" value="Register" class="special" /></li>
										<li><input type="reset" value="Reset" /></li>
									</ul>
								</form>
                            <span>Already have an account? <a href="/login"><strong>Login here</strong></a></span>
                        </article>
					
			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>








