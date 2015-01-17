<?php
 
	// start or continue session
	session_start(); 
 
	// include configuration file
	include('config.php');

	// connect to the database
	$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
 

	// if the submit button has been pressed
	if(isset($_POST['submit']))
	{
		// create an empty error array
		$error = array();
		// check for a email
		if(empty($_POST['email']))
		{
			$error['email'] = 'Required field';
		} 
		
		// check for a password
		if(empty($_POST['userpass']))
		{
			$error['userpass'] = 'Required field';
		} 

		// check signin credentials
		if(!empty($_POST['email']) && !empty($_POST['userpass']))
		{
			// get user_id from the users table
			$sql = "SELECT 
						user_id, 
						first, 
						last 
					FROM 
						users 
					WHERE 
						email = '{$_POST['email']}' AND pass = sha1('{$_POST['userpass']}') 
					LIMIT 1";
			$result = mysqli_query($db, $sql);
			$row = mysqli_fetch_assoc($result);
			
			// if the user is not found
			if(!$row['user_id'])
			{
				$error['user'] = 'Invalid username and/or password';
				echo $error['user'];
			}
		}
		
		// if there are no errors
		if(sizeof($error) == 0)
		{
			// append user variables to session
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['firstname'] = $row['first'];
			$_SESSION['lastname'] = $row['last'];
			
			// redirect user to profile page
			header("Location: index.php");
			exit();

		} 
	}
?>
 
<!DOCTYPE html>
<html>
	<head>
		<title>BookBook</title>
		<!-- jQuery -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

		<!-- bootstrap -->
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css.css">
	</head>
	<body>
		<div id="header" style="text-align:center;background-color:rgba(0,0,55,0.3);color:white;margin-bottom:60px;">
			<h1 style="font-size:4em;margin:0px;padding:10px;">BookBook</h1>
		</div>
		<div class="container">
			<div class="col-md-4">

				<!-- sign up link -->
				<p style="font-size:.7em;color:grey;margin-bottom:10px;">Don't already have an account? <a href="signup.php">Sign up</a>!</p>
		
				<div id="emailsignup" style="background-color:grey;padding:8px 10px 1px 10px;border-radius:5px;">
					<form method="post" action="login.php">
						
						<!-- email -->
						<div class="form-group">
							<label>Email</label><br />
							<input name="email" type="text" value="<?php echo $_POST['email']; ?>" class="form-control" />
							<span class="text-danger"><?php echo $error['email']; ?></span>
						</div>
						
						<!-- password -->
						<div class="form-group">
							<label for="password">Password</label><br />
							<input id="password" name="userpass" type="password" class="form-control" value="<?php echo $error['userpass']; ?>" />
							<span class="text-danger"><?php echo $error['userpass']; ?></span>
						</div>
						
						<!-- submit button -->
						<div class="form-group">
							<input name="submit" type="submit" value="Sign in" class="btn btn-primary" />
						</div>

					</form>
				</div>
			</div>

			<div class="col-md-8">
				<h1 style="margin-top:0px;">Welcome to BookBook!<h1>
				<h2 style="margin-top:0px;font-weight:100;">Searching for books?<br>Start searching your local neighborhood!</h2>
			</div>

		</div>
	</body>
</html>