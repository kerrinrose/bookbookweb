<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();


$page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q={$_GET['isbn']}");

$data = json_decode($page, true);

$title = $data['items'][0]['volumeInfo']['title'];
$authors = @implode(",", $data['items'][0]['volumeInfo']['authors']);
$imgurl = $data['items'][0]['volumeInfo']['imageLinks']['smallThumbnail'];

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
    <script>
        function selectbook(isbn){
            alert(isbn)
        }
    </script>
	<body>
		
		<!-- content -->	
		<div id="<?php echo $_GET['isbn']; ?>" class="container" style="margin-top:65px;background-color:white;border-radius:5px;padding:10px;" onclick="selectbook(this.id);">
            
            <img src="<?php echo $imgurl; ?>" class="pull-right">
            
            <h1 class="pull-left"><?php echo $title; ?></h1>
            
            <h1 class="pull-left"><?php echo $authors; ?></h1>
			
		</div>
	
	</body>
</html>