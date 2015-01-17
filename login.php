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
		<div id="header">
		<img src="photos/bookbook-logo.png" width="30%">
		</div>
		<div class="container">
			<div class="col-md-4">

				<!-- sign up link -->
			
		
				<div id="emailsignup" class="form-style">
					<form method="post" action="login.php">
						
						<!-- email -->
						<div class="form-group">
							<label>Email</label><br />
							<input name="email" type="text" value="<?php echo $_POST['email']; ?>" class="form-control input-style" />
							<span class="text-danger"><?php echo $error['email']; ?></span>
						</div>
						
						<!-- password -->
						<div class="form-group">
							<label for="password">Password</label><br />
							<input id="password" name="userpass" type="password" class="form-control input-style" value="<?php echo $error['userpass']; ?>" />
							<span class="text-danger"><?php echo $error['userpass']; ?></span>
						</div>
						
						<!-- submit button -->
						<div class="form-group">
							<input name="submit" type="submit" value="Sign in" class="btn btn-primary sub-style" />
						</div>

                        
					</form>
				</div>
                
                	<p style="font-size:1.3em;color:grey;margin-top:10px;">Don't already have an account? <a href="signup.php">Sign up</a>!</p>
			</div>

			<div class="col-md-8">
                <h1 style="margin-top:0px;">Welcome to bookbook!</h1>
			<p style="font-weight: 200; font-size: 24pt; line-height: 52px;">Searching for books?</br>
Looking to sell?<br />
Start searching your local neighborhood!</p>
			</div>

		</div>
	</body>
</html>