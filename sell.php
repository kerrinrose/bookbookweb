<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();

// if the form has been submitted
if(isset($_POST['submit']))
{
	// create an empty error array
	$error = array();

	// check for a firstname
	if(empty($_POST['isbn']))
	{
		$error['isbn'] = 'Required field';
	}
    
    // redirect user to profile page
    header("Location: sellablebooks.php?isbn={$_POST['isbn']}");
    exit();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Shoutbox</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- jQuery -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

		<!-- bootstrap -->
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css.css">			
	</head>
	<body>
		
		<!-- content -->	
		<div class="container" style="margin-top:65px;background-color:white;border-radius:5px;">
		
			<h2>Search</h2>

			<!-- ISBN form -->
			<form method="post" action="sell.php">
				
				<!-- first name -->
				<div class="form-group">
					<label>ISBN</label>
					<input name="isbn" type="text" value="ISBN #" class="form-control" />
					<span class="text-danger"><?php echo $error['isbn']; ?></span>
				</div>
				
				<!-- submit button -->
				<div class="form-group">
					<input name="submit" type="submit" value="Sign up" class="btn btn-primary" />
				</div>
				
			</form>
			
			
		</div>
	
	</body>
</html>