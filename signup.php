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
	if(empty($_POST['firstname']))
	{
		$error['firstname'] = 'Required field';
	} 
	
	// check for a lastname
	if(empty($_POST['lastname']))
	{
		$error['lastname'] = 'Required field';
	} 
	
	// check for a email
	if(empty($_POST['email']))
	{
		$error['email'] = 'Required field';
	} else {
	
		// check to see if email address is unique
		$sql = "SELECT user_id FROM users WHERE email = '{$_POST['email']}'";
		$result = mysqli_query($db, $sql);
		if(mysqli_num_rows($result) > 0)
		{
			$error['email'] = 'You already have an account';
		}
	}
	
	// check for a password
	if(empty($_POST['userpass']))
	{
		$error['userpass'] = 'Required field';
	} 
	
	// if there are no errors
	if(sizeof($error) == 0)
	{
		// insert user into the users table
		$sql = "INSERT INTO users (
					user_id, 
					first, 
					last, 
					email, 
					pass
				) VALUES (
					null,
					'{$_POST['firstname']}',
					'{$_POST['lastname']}',
					'{$_POST['email']}',
					sha1('{$_POST['userpass']}')
					)";
		$result = mysqli_query($db, $sql);
		
		// obtain user_id from table
		$user_id = mysqli_insert_id($db);
		
		// send a signup e-mail to user
		$message = "Dear {$_POST['firstname']} {$_POST['lastname']},\n";
		$message = $message . "Thank you for signing up!\n";
		mail($_POST['email'], 'Sign up confirmation', $message, "From: name@yourdomain.com");
		
		// append user_id to session array
		$_SESSION['user_id'] = $user_id;
		$_SESSION['firstname'] = $_POST['firstname'];
		$_SESSION['lastname'] = $_POST['lastname'];
		
		// redirect user to profile page
		header("Location: index.php");
		exit();
				
	} 
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
        
        <div id="header">
		<img src="photos/bookbook-logo.png" width="30%">
		</div>
		
		<!-- content -->	
		<div class="container" style="margin-top:65px;background: none;border-radius:5px;">
		<div class="col-md-6 col-md-offset-3">   
			<h2>Sign up</h2>

			<!-- signup form -->
			<form method="post" class="form-style sign-up" action="signup.php" >
				
				<!-- first name -->
				<div class="form-group">
					<label>First Name</label>
					<input name="firstname" type="text" value="<?php echo $_POST['firstname']; ?>" class="form-control input-style" />
					<span class="text-danger"><?php echo $error['firstname']; ?></span>
				</div>
							
				<!-- last name -->
				<div class="form-group">
					<label>Last Name</label>
					<input name="lastname" type="text" value="<?php echo $_POST['lastname']; ?>" class="form-control input-style" />
					<span class="text-danger"><?php echo $error['lastname']; ?></span>
				</div>
				
				<!-- e-mail -->
				<div class="form-group">
					<label>E-mail</label>
					<input name="email" type="text" value="<?php echo $_POST['email']; ?>" class="form-control input-style" />
					<span class="text-danger"><?php echo $error['email']; ?></span>
				</div>
				
				<!-- password -->
				<div class="form-group">
					<label>Password</label>
					<input name="userpass" type="password" class="form-control input-style" />
					<span class="text-danger"><?php echo $error['userpass']; ?></span>
				</div>
				
				<!-- submit button -->
				<div class="form-group">
					<input name="submit" type="submit" value="Sign up" class="btn btn-primary sub-style" />
				</div>
				
			</form>
			
			<!-- sign in link -->
			<p>Already have an account? <a href="index.php">Sign in</a>!</p>
            </div>
		</div>
	
	</body>
</html>