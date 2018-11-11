<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>3rdEye | Plagiarism Report</title>
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <link href="https://fonts.googleapis.com/css?family=Montserrat:300|Raleway:500" rel="stylesheet">
    </head>
    <body>

		<table style="border: none; width: 100%;">
			<tr>
               <td style="width: 10%;"></td>
			   <td style="width: 80%; padding-top: 40px; background-color: #eee; font-family: 'Raleway', sans-serif;">
					<img src="{{url('images/3e.png')}}" style="text-align: center; width: 160px; height: auto; display: block; margin:0 auto;"/>
					<br />
					<h1 style="text-align: center; font-family: 'Montserrat', sans-serif; font-size: 60px; font-weight: lighter;">Plagiarism Report</h1>
					<br />
					Dear {{$user}},
					<br /><br />
					Thank you for using 3rdeye. Please see a summary of your search below: <br />
					
					<h2 style="text-align: center; font-family: 'Montserrat', sans-serif; font-size: 40px; font-weight: lighter; background: #722F37; color: white;">Report from 3rd Eye</h2>
					<br />
					Report Title: {{$title}}<br /><br />
					Plagiarism Percentage: {{$percentage}}%<br /><br />
					Word Count: {{$wordcount}} <br /><br />
					Sentence Count: {{$sentencecount}}<br /><br />
					Character Count: {{$charactercount}}<br /><br />
					Matching Sentences: {{$matchingsentences}} <br /><br />
					Matching Sources: {{$matchingsources}}<br /><br />
					Search Type: {{$searchtype}} <br /><br />
					Upload Time: {{$uploadtime}} <br /><br />

					<br /><br /><br /><br />
					<a href="{{$pdflink}}">Click here </a> to view (or download) detailed report in PDF.
					<br /><br />
					<p style="font-size: 15px; color: white; background: black; text-align: center; padding: 10px; margin-bottom: -10px;"><a href="{{url('/')}}" style="font-weight: bold; color: white; text-decoration: none;" >3rdeye</a> Copyright &copy; <?php echo date("Y"); ?></p>
					
				</td>
				<td style="width: 10%;"></td>
			</tr>
		</table>
    </body>
</html>
