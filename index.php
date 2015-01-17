<?php
 
	// start or continue session
	session_start(); 
 
	// include configuration file
	include('config.php');

	// connect to the database
	$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

    if(empty($_SESSION['user_id']))
    {

        // redirect user to profile page
        header("Location: login.php");
        exit();

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
		<p><?php echo "{$_SESSION['firstname']} "; echo "{$_SESSION['lastname']}";?></p>
        <p>Do you want to buy or sell your book?</p>
        <a href="sell.php">Sell</a>
        <a href="buy.php">Buy</a>
	</body>
</html>